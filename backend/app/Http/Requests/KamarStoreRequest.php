<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KamarStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id_jenis_kamar' => 'required|exists:jenis_kamar,id_jenis_kamar',
            'nomor_kamar'     => 'required|string|max:50|unique:kamar,nomor_kamar,' . $this->id_kamar . ',id_kamar',
        ];
    }
}
