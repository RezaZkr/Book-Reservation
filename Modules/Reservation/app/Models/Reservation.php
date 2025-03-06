<?php

namespace Modules\Reservation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Models\Branch;
use Modules\Reservation\Enums\ReservationStatusEnum;
use Modules\User\Models\User;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function bookVersion(): BelongsTo
    {
        return $this->belongsTo(BookVersion::class);
    }

}
