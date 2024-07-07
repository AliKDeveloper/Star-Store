<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'image' => $this->image,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->pivot->quantity,
            'total_price' => $this->pivot->total_price,
            'is_available' => $this->is_available,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at,
        ];
    }
}
