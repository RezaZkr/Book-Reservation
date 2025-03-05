<?php

namespace Modules\Reservation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Reservation\Enums\ReservationStatusEnum;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'book_version_id',
        'user_penalty_point',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status'             => ReservationStatusEnum::class,
            'user_penalty_point' => 'integer',
        ];
    }

}
