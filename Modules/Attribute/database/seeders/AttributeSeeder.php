<?php

namespace Modules\Attribute\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Attribute\Models\Attribute;
use Modules\Attribute\Models\Value;

class AttributeSeeder extends Seeder
{

    public function run(): void
    {
        $attributes = [
            'Genre'     => [
                'Romance',
                'Biography',
                'History',
            ],
            'Language'  => [
                'English',
                'Spanish',
                'French',
            ],
            'Age Group' => [
                'Children',
                'Young',
                'Adult',
                'All',
            ],
            'Size'      => [
                'Standard',
                'Large Print',
                'Over Sized',
            ],
        ];

        foreach ($attributes as $attribute => $values) {

            $createdAttribute = Attribute::query()->firstOrCreate([
                'title' => $attribute,
            ]);

            foreach ($values as $value) {
                Value::query()->firstOrCreate([
                    'attribute_id' => $createdAttribute->id,
                    'title'        => $value,
                ]);
            }

        }

    }
}
