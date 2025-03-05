<?php

namespace Modules\Reservation\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Book\Transformers\Api\V1\BookVersionResource;
use Modules\Branch\Transformers\Api\V1\BranchResource;
use Modules\General\Transformers\Api\V1\DateResource;
use Modules\General\Transformers\Api\V1\EnumResource;
use Modules\User\Transformers\Api\V1\UserResource;

class LoanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'branch_id'       => $this->branch_id,
            'book_version_id' => $this->book_version_id,
            'status'          => EnumResource::make($this->status),
            'loan_date'       => DateResource::make($this->loan_date),
            'expiration_date' => DateResource::make($this->expiration_date),
            'give_status'     => EnumResource::make($this->give_status),
            'receive_status'  => EnumResource::make($this->receive_status),
            'user'            => UserResource::make($this->whenLoaded('user')),
            'branch'          => BranchResource::make($this->whenLoaded('branch')),
            'book_version'    => BookVersionResource::make($this->whenLoaded('bookVersion')),
        ];
    }
}
