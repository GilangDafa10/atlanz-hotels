<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisKamarStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'jenis_kasur'     => 'required|string|max:255',
            'harga_permalam'  => 'required|numeric|min:0',
            'deskripsi'       => 'nullable|string',
            'url_gambar'      => 'nullable|string',
            'fasilitas'       => 'nullable',
            'fasilitas.*'     => 'integer|exists:fasilitas,id_fasilitas',
        ];
    }
}
