<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FasilitasJenisKamarResource;

class JenisKamarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_jenis_kamar' => $this->id_jenis_kamar,
            'jenis_kasur'    => $this->jenis_kasur,
            'harga_permalam' => $this->harga_permalam,
            'deskripsi'      => $this->deskripsi,
            'url_gambar'     => $this->url_gambar,
            'fasilitas_jenis_kamar'    => new FasilitasJenisKamarResource($this->id_fasilitas_jenis_kamar),
        ];
    }
}
