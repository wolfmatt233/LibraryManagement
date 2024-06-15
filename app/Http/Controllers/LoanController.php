<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    public function view(Request $request) {
        $id = Auth::id();
        $search = $request->input('search');
        $loans = [];

        if($search) {
            $loans = Loan::where('user_id', $id)->where('status', 'borrowed')->whereHas('book', function($query) use($search) {
                $query->where('title', 'like', "%$search%");
            })->with('book')->get();
        } else {
            $loans = Loan::where('user_id', $id)->where('status', 'borrowed')->with('book')->get();
        }

        foreach($loans as $loan) {
            $today = strtotime(date("Y-m-d"));
            $due = strtotime($loan->due_date);
            $difference = $due - $today;
            $difference = round($difference / (60 * 60 * 24));
            $loan->date_difference = $difference;
        }
        
        return view('dashboard', ['loans' => $loans], ['search' => $search]);
    }

    public function pastLoans(Request $request) {
        $id = Auth::id();
        $search = $request->input('search');
        $loans = [];

        if($search) {
            $loans = Loan::where('user_id', $id)->where('status', 'returned')->whereHas('book', function($query) use($search) {
                $query->where('title', 'like', "%$search%");
            })->with('book')->get();
        } else {
            $loans = Loan::where('user_id', $id)->where('status', 'returned')->with('book')->get();
        }
        
        return view('past-loans', ['loans' => $loans], ['search' => $search]);
    }

    public function createLoan($id) {
        $newLoan = new Loan();
        $newLoan->book_id = $id;
        $newLoan->user_id = Auth::id();
        $newLoan->borrow_date = date("Y-m-d");
        $newLoan->due_date = date('Y-m-d', strtotime("Y-m-d" . ' + 21 days'));
        $newLoan->return_date = null;
        $newLoan->status = "borrowed";
        $newLoan->save();
        return redirect('/books/{id}');
    }

    public function removeLoan($id) {
        $loan = Loan::find($id);
        $loan->status = "returned";
        $loan->return_date = date("Y-m-d");
        $loan->save();
        return redirect('/dashboard');
    }

    public function viewAll(Request $request) {
        //admin: view and search all loans
        $id = Auth::id();

        if(Auth::user()->admin == true) {
            $search = $request->input('search');
            $loans = [];

            if($search) {
                $loans = Loan::whereHas('book', function($query) use($search) {
                    $query->where('title', 'like', "%$search%");
                })->with('book')->with('user')->get();
            } else {
                $loans = Loan::all();
            }

            foreach($loans as $loan) {
                $loan->user = $loan->user->name; //only get name to pass over

                if($loan->status == "borrowed") {
                    $today = strtotime(date("Y-m-d"));
                    $due = strtotime($loan->due_date);
                    $difference = $due - $today;
                    $difference = round($difference / (60 * 60 * 24));
                    $loan->date_difference = $difference;
                }
            }
            
            return view('admin-loans', ['loans' => $loans], ['search' => $search]);
        } else {
            return view('error', ['message' => "This page is for admins only."]);
        }
    }

    public function editLoan($id) {
        $loan = Loan::find($id);
        if(Auth::user()->admin != true) {
            return view('error', ['message' => "This page is for admins only."]);
        } else {
            return view('edit-loan', ['loan' => $loan]);
        }
    }
    
    public function updateLoan(Request $request, $id) {
        //admin: can only edit due date, return date, and status
        Log::info($request);
        $loan = Loan::find($id);
        $loan->due_date = $request->due_date;
        $loan->return_date = $request->return_date;
        $loan->status = $request->status;
        $loan->save();
        return redirect('/viewAll');
    }

    public function deleteLoan($id) {
        //admin
        $loan = Loan::find($id);
        $loan->delete();
        return redirect('/adminLoans');
    }
}
