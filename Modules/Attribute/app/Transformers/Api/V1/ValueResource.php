<?php

namespace Modules\Attribute\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'attribute_id' => $this->attribute_id,
            'title'        => $this->title,
        ];
    }
}
