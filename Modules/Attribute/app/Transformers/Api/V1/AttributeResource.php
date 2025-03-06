<?php

namespace Modules\Attribute\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'value' => $this->whenPivotLoaded('attribute_book_version', $this->resource->pivot->value_title),
        ];
    }
}
