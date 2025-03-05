<?php

namespace Modules\Book\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Attribute\Models\Value;
use Modules\Book\Models\AttributeBookVersion;
use Modules\Book\Models\BookVersion;

class AttributeBookVersionSeeder extends Seeder
{
    public function run(): void
    {

        for ($i = 1; $i <= 2; $i++) {

            $bookVersion = BookVersion::query()->find($i);

            for ($x = 1; $x <= 10; $x++) {
                $value = Value::query()->inRandomOrder()->with('attribute')->first();

                AttributeBookVersion::query()->firstOrCreate([
                    'book_version_id' => $bookVersion->id,
                    'attribute_id'    => $value->attribute_id,
                    'value_id'        => $value->id,
                ], [
                    'attribute_title' => $value->attribute->title,
                    'value_title'     => $value->title,
                ]);
            }

        }
    }
}
