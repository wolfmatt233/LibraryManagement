<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        return view('books/edit-book', ['book' => Book::find($id)]);
    }

    public function updateBook(Request $request)
    {
        $book = Book::find($request->id);
        $fileName = $book->image;

        if ($request->image) {
            $file = $request->image;
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $fileName, 'public');
        }

        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->genre = $request->genre;
        $book->isbn = $request->isbn;
        $book->publisher = $request->publisher;
        $book->published = $request->published;
        $book->image = $fileName;
        $book->num_available = $request->num_available; //deal with current loans if changing number
        $book->save();

        return redirect('/books/' . $request->id);
    }

    public function addBook() //page router
    {
        return view('books/add-book');
    }

    public function createBook(Request $request)
    {
        //store cover image
        $file = $request->image;
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('images', $fileName, 'public');

        $newBook = new Book();
        $newBook->title = $request->title;
        $newBook->description = $request->description;
        $newBook->author = $request->author;
        $newBook->genre = $request->genre;
        $newBook->isbn = $request->isbn;
        $newBook->publisher = $request->publisher;
        $newBook->published = $request->published;
        $newBook->image = $fileName;
        $newBook->num_available = $request->num_available;
        $newBook->save();

        return redirect('/books');
    }

    public function deleteBook($id)
    {
        //when is deleted, deal with the current loans using that book
        $book = Book::find($id);
        $book->delete();
        return redirect('/books');
    }
}
