<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KamarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_kamar'       => $this->id_kamar,
            'nomor_kamar'    => $this->nomor_kamar,
            'jenis_kamar'    => new JenisKamarResource($this->jenisKamar),
        ];
    }
}
