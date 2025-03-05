<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeBookVersion extends Model
{
    protected $table = 'attribute_book_version';
    protected $fillable = [
        'book_version_id',
        'attribute_id',
        'value_id',
        'attribute_title',
        'value_title',
    ];

}
