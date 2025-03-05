<?php

namespace Modules\Book\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Book\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            Book::query()->firstOrCreate([
                'title' => 'Book ' . $i,
            ], [
                'author' => "Book $i Author",
                'isbn'   => mt_rand(10000, 99999),
            ]);
        }
    }
}
