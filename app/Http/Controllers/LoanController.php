<?php

namespace App\Http\Controllers;

use App\Jobs\ActivateHold;
use App\Models\Book;
use App\Models\Hold;
use App\Models\Loan;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $uid = Auth::id();
        $search = $request->input('search');
        $loans = [];

        if ($search) {
            $loans = Loan::where('user_id', $uid)->where('status', 'borrowed')->whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->with('book')->get();
        } else {
            $loans = Loan::where('user_id', $uid)->where('status', 'borrowed')->with('book')->get();
        }

        foreach ($loans as $loan) {
            $today = strtotime(date("Y-m-d"));
            $due = strtotime($loan->due_date);
            $difference = $due - $today;
            $difference = round($difference / (60 * 60 * 24));
            $loan->date_difference = $difference;
        }

        return view('loans/loans', ['loans' => $loans, 'search' => $search]);
    }

    public function pastLoans(Request $request)
    {
        $id = Auth::id();
        $search = $request->input('search');
        $loans = [];

        if ($search) {
            $loans = Loan::where('user_id', $id)->where('status', 'returned')->whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->with('book')->get();
        } else {
            $loans = Loan::where('user_id', $id)->where('status', 'returned')->with('book')->get();
        }

        return view('loans/past-loans', ['loans' => $loans], ['search' => $search]);
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

    public function viewAll(Request $request)
    {
        $search = $request->input('search');
        $loans = [];

        if ($search) {
            $loans = Loan::whereHas('book', function ($query) use ($search) {
                $query->where('title', 'like', "%$search%");
            })->with('book')->with('user')->paginate(10);
        } else {
            $loans = Loan::paginate(10);
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

        return view('loans/admin-loans', ['loans' => $loans, 'search' => $search]);
    }

    public function editLoan($id) //admin
    {
        return view('loans/edit-loan', ['loan' => Loan::find($id)]);
    }

    public function updateLoan(Request $request, $id) //admin
    {
        $loan = Loan::find($id);
        $loan->due_date = $request->due_date;
        $loan->return_date = $request->return_date;
        $loan->status = $request->status;
        $loan->save();
        return redirect('/viewAll');
    }

    public function deleteLoan($id) //admin
    {
        $loan = Loan::find($id);
        $loan->delete();
        return redirect('/viewAll');

    }
}
