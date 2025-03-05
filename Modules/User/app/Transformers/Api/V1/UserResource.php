<?php

namespace Modules\User\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\General\Transformers\Api\V1\EnumResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'           => $this->name,
            'email'          => $this->email,
            'type'           => EnumResource::make($this->type),
            'penalty_points' => $this->penalty_points,
            'restricted'     => $this->restricted,
        ];
    }
}
