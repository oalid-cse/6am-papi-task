<?php

namespace App\Http\Resources\Products;

use App\Enum\StatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->show_image,
            'image_url' => asset($this->show_image),
            'status' => $this->status,
            'status_text' => StatusEnum::from($this->status)->name
        ];
    }
}
