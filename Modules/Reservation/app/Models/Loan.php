<?php

namespace Modules\Reservation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Reservation\Enums\LoanStatusEnum;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'book_version_id',
        'status',
        'loan_date',
        'expiration_date',
        'give_status',
        'receive_status',
    ];

    protected function casts(): array
    {
        return [
            'status'          => LoanStatusEnum::class,
            'loan_date'       => 'datetime',
            'expiration_date' => 'datetime',
            'give_status'     => BookVersionConditionEnum::class,
            'receive_status'  => BookVersionConditionEnum::class,
        ];
    }
}
