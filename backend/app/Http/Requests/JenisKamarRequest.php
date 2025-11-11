<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisKamarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenis_kasur'     => 'required|string|max:255',
            'harga_permalam'  => 'required|numeric|min:0',
            'deskripsi'       => 'nullable|string',
            'url_gambar'      => 'nullable|string',
        ];
    }
}
