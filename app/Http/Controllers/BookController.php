<?php

namespace App\Http\Controllers;

use App\Models\Book;
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

        return view('books/books', ['books' => $books, 'search' => $search]);
    }

    public function getBook($id)
    {
        $book = Book::find($id);
        $loans = Loan::where('user_id', Auth::id())->where('status', 'borrowed')->get();
        $borrowed = 'false';
        $limited = 'false';

        if (count($loans) >= 10) {
            $limited = 'true'; //user cannot have more than 10 loans at a time
        }

        foreach ($loans as $loan) {
            if ($loan->book_id == $id) {
                $borrowed = $loan->id;
            }
        }

        return view('books/view-book', ['book' => $book, 'borrowed' => $borrowed, 'limited' => $limited]);
    }

    public function editBook($id) //page router
    {
        return view('books/edit-book', ['book' => Loan::find($id)]);
    }

    public function updateBook(Request $request)
    {
        $book = Book::find($request->book_id);
        $book->title = $request->title; //name='title' etc.
        $book->description = $request->description;
        $book->author = $request->author;
        $book->genre = $request->genre;
        $book->isbn = $request->isbn;
        $book->publisher = $request->publisher;
        $book->published = $request->published;
        //upload new image and delete previous? how to differentiate? force unique name?
        $book->image = $request->image;
        $book->num_available = $request->num_available; //deal with current loans if changing number
        $book->save();
    }

    public function addBook() //page router
    {
        return view('books/add-book');
    }

    public function createBook(Request $request)
    {
        $newBook = new Book();
        $newBook->user_id = Auth::id();
        $newBook->title = $request->title;
        $newBook->description = $request->description;
        $newBook->author = $request->author;
        $newBook->genre = $request->genre;
        $newBook->isbn = $request->isbn;
        $newBook->publisher = $request->publisher;
        $newBook->published = $request->published;
        $newBook->image = $request->image;
        $newBook->num_available = $request->num_available;
        $newBook->save();
    }

    public function deleteBook($id)
    {
        //when is deleted, deal with the current loans using that book
        $book = Book::find($id);
        $book->delete();
        return redirect('/books');
    }
}
