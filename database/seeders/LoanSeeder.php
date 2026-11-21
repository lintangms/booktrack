<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();

        if ($users->count() < 2 || $books->count() < 4) {
            return;
        }

        $user1 = $users[1] ?? $users[0];
        $user2 = $users[2] ?? $users[0];

        // Sample data peminjaman
        $sampleLoans = [
            [
                'user_id' => $user1->id,
                'book_id' => $books[0]->id,
                'loan_date' => now()->subDays(5)->toDateString(),
                'due_date' => now()->addDays(2)->toDateString(),
                'status' => 'Dipinjam',
                'notes' => 'Peminjaman untuk tugas kuliah',
            ],
            [
                'user_id' => $user1->id,
                'book_id' => $books[1]->id,
                'loan_date' => now()->subDays(12)->toDateString(),
                'due_date' => now()->subDays(2)->toDateString(), // Terlambat
                'status' => 'Terlambat',
                'notes' => 'Belum dikembalikan',
            ],
            [
                'user_id' => $user2->id,
                'book_id' => $books[2]->id,
                'loan_date' => now()->subDays(3)->toDateString(),
                'due_date' => now()->addDays(4)->toDateString(),
                'status' => 'Dipinjam',
                'notes' => null,
            ],
            [
                'user_id' => $user2->id,
                'book_id' => $books[3]->id,
                'loan_date' => now()->subDays(1)->toDateString(),
                'due_date' => now()->addDays(6)->toDateString(),
                'status' => 'Dipinjam',
                'notes' => null,
            ],
            [
                'user_id' => $user1->id,
                'book_id' => $books[2]->id,
                'loan_date' => now()->subDays(20)->toDateString(),
                'due_date' => now()->subDays(13)->toDateString(),
                'return_date' => now()->subDays(14)->toDateString(),
                'status' => 'Dikembalikan',
                'notes' => 'Dikembalikan tepat waktu dalam kondisi baik',
            ],
        ];

        foreach ($sampleLoans as $loanData) {
            Loan::create($loanData);
        }
    }
}
