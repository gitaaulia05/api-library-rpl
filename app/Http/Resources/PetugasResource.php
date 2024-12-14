<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetugasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_petugas' => $this->id_petugas,
            'username' => $this->username,
            'password' => $this->password,
            'token' => $this->whenNotNull($this->token),


        ];
    }
}
