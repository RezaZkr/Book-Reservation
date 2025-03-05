<?php

namespace Modules\Book\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Book\Models\Book;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Models\Branch;

class BookVersionSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 2; $i++) {
            BookVersion::query()->create([
                'branch_id' => Branch::query()->inRandomOrder()->first()->id,
                'book_id'   => Book::query()->inRandomOrder()->first()->id,
            ]);
        }
    }
}
