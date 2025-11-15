<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FasilitasJenisKamarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_fasilitas_jenis_kamar'             => $this->id_fasilitas_jenis_kamar,
            'id_fasilitas'   => $this->id_fasilitas,
            'id_jenis_kamar' => $this->id_jenis_kamar,
            // optional include nested fasilitas & jenis_kamar minimal data
            'fasilitas'      => $this->whenLoaded('fasilitas', function () {
                return new FasilitasResource($this->fasilitas);
            }),
            'jenis_kamar'    => $this->whenLoaded('jenisKamar', function () {
                return [
                    'id_jenis_kamar' => $this->jenisKamar->id_jenis_kamar,
                    'jenis_kasur' => $this->jenisKamar->jenis_kasur,
                ];
            }),
        ];
    }
}
