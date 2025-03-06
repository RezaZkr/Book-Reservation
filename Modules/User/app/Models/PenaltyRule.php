<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

class PenaltyRule extends Model
{
    const int NEGATIVE_DAMAGE_POINT = 10;
    const int NEGATIVE_REPEAT_VIOLATION_POINT = 5;
    const int MAXIMUM_PENALTY_POINT = 100;

    const string PENALTY_CACHE_KEY = 'penalty_cache_key';

    protected $fillable = [
        'delay_days',
        'penalty_rate',
    ];

    protected function casts(): array
    {
        return [
            'delay_days'   => 'integer',
            'penalty_rate' => 'integer',
        ];
    }

}
