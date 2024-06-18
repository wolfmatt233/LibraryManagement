<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request) {
        $search = $request->input('search');
        $books = [];

        if($search) {
            $books = Book::where('title', 'like', "%$search%")->get();
        } else {
            $books = Book::all();
        }
        
        return view('books', ['books' => $books], ['search' => $search]);
    }

    public function getBook($id) {
        $book = Book::find($id);

        return view('books/{id}', ['book' => $book]);
    }
}
