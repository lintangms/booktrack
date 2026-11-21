<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'code' => 'LP',
                'theme' => 'cover-teal',
                'title' => 'Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '978-979-3062-79-2',
                'category' => 'Fiksi',
                'stock' => 12,
                'available' => 8,
            ],
            [
                'code' => 'BM',
                'theme' => 'cover-amber',
                'title' => 'Bumi Manusia',
                'author' => 'Pramoedya Ananta Toer',
                'isbn' => '978-979-97312-3-4',
                'category' => 'Sejarah',
                'stock' => 8,
                'available' => 3,
            ],
            [
                'code' => 'FT',
                'theme' => 'cover-blue',
                'title' => 'Filosofi Teras',
                'author' => 'Henry Manampiring',
                'isbn' => '978-602-412-518-9',
                'category' => 'Pengembangan Diri',
                'stock' => 10,
                'available' => 5,
            ],
            [
                'code' => 'HJ',
                'theme' => 'cover-emerald',
                'title' => 'Hujan',
                'author' => 'Tere Liye',
                'isbn' => '978-602-03-2478-4',
                'category' => 'Fiksi',
                'stock' => 9,
                'available' => 4,
            ],
            [
                'code' => 'LB',
                'theme' => 'cover-teal',
                'title' => 'Laut Bercerita',
                'author' => 'Leila S. Chudori',
                'isbn' => '978-602-424-694-5',
                'category' => 'Fiksi',
                'stock' => 7,
                'available' => 2,
            ],
            [
                'code' => 'AH',
                'theme' => 'cover-blue',
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '978-602-06-3317-6',
                'category' => 'Pengembangan Diri',
                'stock' => 6,
                'available' => 2,
            ],
        ];

        foreach ($books as $b) {
            Book::updateOrCreate(
                ['isbn' => $b['isbn']],
                $b
            );
        }
    }
}
