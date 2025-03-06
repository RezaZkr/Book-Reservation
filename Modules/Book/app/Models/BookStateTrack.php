<?php

namespace Modules\Book\Models;

use Illuminate\Database\Eloquent\Model;

class BookStateTrack extends Model
{
    protected $fillable = [
        'branch_id',
        'book_version_id',
        'user_id',
        'status',
    ];
}
