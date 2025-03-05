<?php

namespace Modules\Reservation\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Auth\Enums\GuardEnum;
use Modules\Book\Enums\BookVersionConditionEnum;
use Modules\Reservation\Enums\LoanStatusEnum;

class LoanReturnRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'loan_id'        => [
                'required',
                'integer',
                Rule::exists('loans', 'id')
                    ->where('user_id', $this->user(GuardEnum::SANCTUM)->id)
                    ->whereIn('status', [LoanStatusEnum::ACTIVE, LoanStatusEnum::LOST]),
            ],
            'receive_status' => [
                'required',
                'string',
                Rule::in(BookVersionConditionEnum::cases()),
            ],
        ];
    }

}
