<?php

namespace Modules\General\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (null === $this)
            return [];

        return [
            'value' => $this->resource->value,
            'label' => $this->resource->label(),
        ];
    }
}
