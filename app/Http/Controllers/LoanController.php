<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function view() {
        $id = Auth::id();
        $loans = Loan::where('user_id', $id)->with('book')->get();
        

        return view('dashboard', ['loans' => $loans]);
    }
}
