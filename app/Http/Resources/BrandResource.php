<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
            'id' => $this->id,
            'brand_uuid' =>$this->brand_uuid,
            'brand_name' => $this->brand_name,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
            // 'updated_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }

}
