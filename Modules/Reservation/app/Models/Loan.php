<?php

namespace Modules\Reservation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Book\Models\BookVersion;
use Modules\Branch\Models\Branch;
use Modules\Reservation\Enums\LoanStatusEnum;
use Modules\User\Models\User;

class Loan extends Model
{
    const int LOAN_EXPIRE__IN_DAYS = 10;
    protected $fillable = [
        'user_id',
        'branch_id',
        'book_version_id',
        'status',
        'loan_date',
        'expiration_date',
        'return_date',
        'give_status',
        'receive_status',
    ];

    protected function casts(): array
    {
        return [
            'status'          => LoanStatusEnum::class,
            'loan_date'       => 'datetime',
            'expiration_date' => 'datetime',
            'return_date'     => 'datetime',
            'give_status'     => BookVersionConditionEnum::class,
            'receive_status'  => BookVersionConditionEnum::class,
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
