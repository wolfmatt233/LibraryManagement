<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Hold;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class HoldController extends Controller
{
    public function createHold($id)
    {
        //get current loans and holds for the user
        $loans = Loan::where('user_id', Auth::id())->where('status', 'borrowed')->get();
        $holds = Hold::where('user_id', Auth::id())->where('waiting', true)->get();

        //if any current loans exist  for the book, return
        foreach ($loans as $loan) {
            if ($loan->book_id == $id) {
                return redirect('/books/' . $id);
            }
        }

        //if any current holds exist for the book, return
        foreach ($holds as $hold) {
            if ($hold->book_id == $id) {
                return redirect('/books/' . $id);
            }
        }

        //if user has 10 or more loans or holds, return
        if (count($loans) >= 10 || count($holds) >= 10) {
            return redirect('/books/' . $id);
        } else {
            //otherwise create a new hold
            $newHold = new Hold();
            $newHold->waiting = true;
            $newHold->book_id = $id;
            $newHold->user_id = Auth::id();
            $newHold->save();

            return redirect('/books/' . $id);
        }
    }

    public function cancelHold($id)
    {
        $hold = Hold::where('book_id', $id)->where('user_id', Auth::id())->where('waiting', true)->first();

        $hold->waiting = false;
        $hold->save();

        return redirect('/books/' . $id);
    }
}
