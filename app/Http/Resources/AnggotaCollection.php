<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\AnggotaResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AnggotaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        "data" => AnggotaResource::collection($this->collection)
      ];
    }
}
