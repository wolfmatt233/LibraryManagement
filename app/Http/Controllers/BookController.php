<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Hold;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $books = [];

        if ($search) {
            $books = Book::where('title', 'like', "%$search%")->paginate(15);
        } else {
            $books = Book::paginate(15);
        }

        return view('books', ['books' => $books, 'search' => $search]);
    }

    public function getBook($id)
    {
        $book = Book::find($id);
        $loans = Loan::where('user_id', Auth::id())->where('status', 'borrowed')->get();
        $hold = Hold::where('user_id', Auth::id())->where('book_id', $id)->where('waiting', true)->get();
        $borrowed = 'false';
        $limited = 'false';

        Log::info($hold);

        if (count($loans) >= 10) {
            $limited = 'true'; //user cannot have more than 10 loans at a time
        }

        foreach ($loans as $loan) {
            if ($loan->book_id == $id) {
                $borrowed = $loan->id;
            }
        }


        return view('view-book', ['book' => $book, 'borrowed' => $borrowed,'limited' => $limited]);
    }
}
