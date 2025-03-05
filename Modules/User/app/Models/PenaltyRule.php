<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class PenaltyRule extends Model
{
    protected $fillable = [
        'delay_days',
        'penalty_rate',
    ];

    protected function casts(): array
    {
        return [
            'delay_days' => 'integer',
            'penalty_rate' => 'integer',
        ];
    }

}
