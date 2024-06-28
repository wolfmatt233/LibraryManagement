<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Hold;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    public function index(Request $request) {
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
        $book = Book::find($id);

        if($book->num_available == 0) {
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

    public function removeLoan($id) {
        $loan = Loan::find($id);
        $book = Book::find($loan->book_id);

        $loan->status = "returned";
        $loan->return_date = date("Y-m-d");
        $book->num_available += 1;

        $book->save();
        $loan->save();

        $this->activateHold($book, $loan);

        return redirect('/dashboard');
    }

    public function createHold($id) {
        $newHold = new Hold();
        $newHold->waiting = true;
        $newHold->book_id = $id;
        $newHold->user_id = Auth::id();
        $newHold->save();

        return redirect('/books/' . $id);
    }

    public function activateHold($book, $loan) {
        //availalility goes from 0 to 1
        if($book->num_available == 1) {
            
            //get the most recent hold for this book that is currently waiting
            $hold = Hold::latest('created_at')->where('book_id', $loan->book_id)->where('waiting', true)->first();

            //if there is a hold, create a loan for this book and the user
            if(!empty($hold)) {
                $newLoan = new Loan();
                $newLoan->book_id = $loan->book_id;
                $newLoan->user_id = $hold->user_id;
                $newLoan->borrow_date = date("Y-m-d");
                $newLoan->due_date = date('Y-m-d', strtotime(date('Y-m-d') . ' + 21 days'));
                $newLoan->return_date = null;
                $newLoan->status = "borrowed";
                $newLoan->save();
                
                $book->num_available -= 1;
                $book->save();
                
                $hold->waiting = false;
                $hold->save();
            }
        }
    }

    public function viewAll(Request $request) {
        //admin: view and search all loans
        // if(Auth::user()->admin == true) {
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
                    $today = strtotime(date("m/d/Y"));
                    $due = strtotime($loan->due_date);
                    $difference = $due - $today;
                    $difference = round($difference / (60 * 60 * 24));
                    $loan->date_difference = $difference;
                }
            }
            
            return view('admin-loans', ['loans' => $loans, 'search' => $search]);
        // } else {
        //     return view('error', ['message' => "This page is for admins only."]);
        // }
    }

    public function editLoan($id) {
        $loan = Loan::find($id);
        if(Auth::user()->admin != true) {
            return view('error', ['message' => "Access denied: This page is for admins only."]);
        } else {
            return view('edit-loan', ['loan' => $loan]);
        }
    }
    
    public function updateLoan(Request $request, $id) {
        //admin: can only edit due date, return date, and status
        if(Auth::user()->admin != true) {
            return view('error', ['message' => "Access denied: This page is for admins only."]);
        } else {
            $loan = Loan::find($id);
            $loan->due_date = $request->due_date;
            $loan->return_date = $request->return_date;
            $loan->status = $request->status;
            $loan->save();
            return redirect('/viewAll');
        }
    }

    public function deleteLoan($id) {
        //admin
        if(Auth::user()->admin != true) {
            return view('error', ['message' => "Access denied: This page is for admins only."]);
        } else {
            $loan = Loan::find($id);
            $loan->delete();
            return redirect('/viewAll');
        }
        
    }
}
