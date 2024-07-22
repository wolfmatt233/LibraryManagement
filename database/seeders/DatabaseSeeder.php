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
            'email' => 'admin@admin.com',
            'admin' => true,
            'password' => Hash::make('password'),
        ])->create([
            'name' => 'Matthew',
            'email' => 'matthewwolf56@gmail.com',
            'admin' => false,
            'password' => Hash::make('password'),
        ]);

        Book::factory()->count(5)->create();
    }
}
