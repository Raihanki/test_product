<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => Storage::disk('public')->url($this->image),
            'created_at' => [
                'formatted' => $this->created_at->format('d-m-Y H:i:s'),
                'raw_timestamp' => $this->created_at->timestamp,
            ],
        ];
    }
}
