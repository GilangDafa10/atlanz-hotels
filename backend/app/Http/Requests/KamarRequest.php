<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KamarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nomor_kamar'     => 'required|string|max:50|unique:kamar,nomor_kamar,' . $this->id_kamar . ',id_kamar',
            'id_jenis_kamar'  => 'required|exists:jenis_kamar,id_jenis_kamar',
        ];
    }
}
