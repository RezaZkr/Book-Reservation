<?php

namespace Modules\General\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (null === $this)
            return [];

        return [
            'timestamp' => $this->resource->timestamp,
            'iso8601'   => $this->resource->toIso8601String(),
            'iso'       => $this->resource->toISOString(),
            'diff'      => $this->resource->diffForHumans(),
        ];
    }
}
