<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FasilitasJenisKamarStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_fasilitas'    => 'required|integer|exists:fasilitas,id_fasilitas',
            'id_jenis_kamar'  => 'required|integer|exists:jenis_kamar,id_jenis_kamar',
        ];
    }
}
