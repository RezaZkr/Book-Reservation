<?php

namespace Modules\Reservation\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'branch_id'       => ['required', 'integer', 'exists:branches,id'],
            'book_version_id' => ['required', 'integer', 'exists:book_versions,id'],
        ];
    }

}
