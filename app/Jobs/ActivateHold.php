<?php

namespace App\Jobs;

use App\Models\Hold;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivateHold implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $book_id;


    /**
     * Create a new job instance.
     */
    public function __construct($book_id)
    {
        $this->book_id = $book_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $book = Book::find($this->book_id);

        //availalility goes from 0 to 1
        if ($book->num_available == 1) {

            //get the most recent hold for this book that is currently waiting
            $hold = Hold::latest('created_at')->where('book_id', $book->id)->where('waiting', true)->first();

            //if there is a hold, create a loan for this book and the user
            if (!empty($hold)) {
                $newLoan = new Loan();
                $newLoan->book_id = $book->id;
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
}
