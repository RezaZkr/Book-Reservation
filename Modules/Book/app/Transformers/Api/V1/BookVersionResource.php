<?php

namespace Modules\Book\Transformers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Attribute\Transformers\Api\V1\AttributeResource;
use Modules\Branch\Transformers\Api\V1\BranchResource;
use Modules\General\Transformers\Api\V1\EnumResource;

class BookVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'branch_id'  => $this->branch_id,
            'book_id'    => $this->book_id,
            'condition'  => EnumResource::make($this->condition),
            'status'     => EnumResource::make($this->status),
            'vip'        => $this->vip,
            'branch'     => BranchResource::make($this->whenLoaded('branch')),
            'book'       => BookResource::make($this->whenLoaded('book')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
        ];
    }
}
