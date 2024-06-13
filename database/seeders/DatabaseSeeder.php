<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'admin' => true,
            'password' => Hash::make('password'),
        ]);

        Book::factory()->count(5)->create();

        Loan::factory()->create([
            'user_id' => 1,
            'book_id' => 2,
            'due_date' => date('Y-m-d', strtotime(date('Y-m-d') . ' + 21 days')),
        ]);
    }
}
