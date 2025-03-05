<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Attribute\Models\Attribute;
use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Book\Enums\BookVersionStatusEnum;
use Modules\Branch\Models\Branch;

class BookVersion extends Model
{
    protected $fillable = [
        'branch_id',
        'book_id',
        'condition',
        'status',
        'vip',
    ];

    protected function casts(): array
    {
        return [
            'condition' => BookVersionConditionEnum::class,
            'status'    => BookVersionStatusEnum::class,
            'vip'       => 'boolean',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function attributes(): BelongsToMany
    {
        //pivot table name 'attribute_book_version'
        return $this->belongsToMany(Attribute::class)->withPivot(['attribute_title', 'value_title']);
    }

}
