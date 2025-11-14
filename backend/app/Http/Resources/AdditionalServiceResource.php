<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_service' => $this->id_service,
            'nama_service' => $this->nama_service,
            'deskripsi_service' => $this->deskripsi_service,
            'harga_service' => $this->harga_service,
            'url_gambar' => $this->url_gambar
        ];
    }
}
