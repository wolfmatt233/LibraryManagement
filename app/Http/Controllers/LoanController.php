<?php

namespace App\Http\Controllers;

use App\Jobs\ActivateHold;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $uid = Auth::id();
        $search = $request->input('search');
        $past = $request->input('pastLoans');
        $sort = $request->input('sort');
        $loans = [];

        $past == "on" ? $past = "returned" : $past = "borrowed";

        if ($sort) {
            $loans = Loan::where('user_id', $uid)->where('status', $past)->whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->join('books', 'loans.book_id', '=', 'books.id')->orderBy('title', $sort)->with('book')->paginate(10);
        } else {
            $loans = Loan::where('user_id', $uid)->where('status', $past)->whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->with('book')->paginate(10);
        }

        foreach ($loans as $loan) {
            $today = strtotime(date("Y-m-d"));
            $due = strtotime($loan->due_date);
            $difference = $due - $today;
            $difference = round($difference / (60 * 60 * 24));
            $loan->date_difference = $difference;
        }

        $past == "returned" ? $past = "on" : $past = "";

        return view('loans/loans', ['loans' => $loans, 'search' => $search, 'past' => $past, 'sort' => $sort]);
    }

    public function createLoan($id)
    {
        $book = Book::find($id);
        $loans = Loan::where('user_id', Auth::id())->where('status', 'borrowed')->get();

        if ($book->num_available == 0 || count($loans) >= 10) {
            return redirect('/books/' . $id);
        } else {
            $newLoan = new Loan();
            $newLoan->book_id = $id;
            $newLoan->user_id = Auth::id();
            $newLoan->borrow_date = date("Y-m-d");
            $newLoan->due_date = date('Y-m-d', strtotime(date('Y-m-d') . ' + 21 days'));
            $newLoan->return_date = null;
            $newLoan->status = "borrowed";
            $newLoan->save();

            $book->num_available -= 1;
            $book->save();

            return redirect('/books/' . $id);
        }
    }

    public function removeLoan($id)
    {
        $loan = Loan::find($id);
        $book = Book::find($loan->book_id);

        $loan->status = "returned";
        $loan->return_date = date("Y-m-d");
        $book->num_available += 1;

        $book->save();
        $loan->save();

        //availalility goes from 0 to 1
        if ($book->num_available == 1) {
            ActivateHold::dispatchSync($loan->book_id);
        }

        return redirect('/books/' . $loan->book_id);
    }

    //Admin
    public function viewAll(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $sort = $request->input('sort');
        $loans = [];

        empty($status) ? $status = 'returned' : $status;

        if ($sort) {
            // Loan where search like book title OR search like user name
            $loans = Loan::whereHas('book', function ($query) use ($search, $status) {
                $query->where('title', 'like', "%$search%");
                $query->where('status', $status);
            })->orWhereHas('user', function ($query) use ($search, $status) {
                $query->where('name', 'like', "%$search%");
                $query->where('status', $status);
            })->join('books', 'loans.book_id', '=', 'books.id')->orderBy('title', $sort)->with('book')->with('user')->paginate(10);
        } else {
            $loans = Loan::whereHas('book', function ($query) use ($search, $status) {
                $query->where('title', 'like', "%$search%");
                $query->where('status', $status);
            })->orWhereHas('user', function ($query) use ($search, $status) {
                $query->where('name', 'like', "%$search%");
                $query->where('status', $status);
            })->with('book')->with('user')->paginate(10);

        }

        foreach ($loans as $loan) {
            $loan->user = $loan->user->name; //only get name to pass over

            if ($loan->status == "borrowed") {
                $today = strtotime(date("m/d/Y"));
                $due = strtotime($loan->due_date);
                $difference = $due - $today;
                $difference = round($difference / (60 * 60 * 24));
                $loan->date_difference = $difference;
            }
        }

        return view('loans/admin-loans', ['loans' => $loans, 'search' => $search, 'status' => $status, 'sort' => $sort]);
    }

    //Admin
    public function editLoan($id) //admin
    {
        return view('loans/edit-loan', ['loan' => Loan::find($id)]);
    }

    //Admin
    public function updateLoan(Request $request, $id) //admin
    {
        $loan = Loan::find($id);
        $loan->due_date = $request->due_date;
        $loan->return_date = $request->return_date;
        $loan->status = $request->status;
        $loan->save();
        return redirect('/viewAll');
    }

    //Admin
    public function deleteLoan($id) //admin
    {
        $loan = Loan::find($id);
        $loan->delete();
        return redirect('/viewAll');

    }
}
