<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KamarUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nomor_kamar'     => 'required|string|max:50|unique:kamar,nomor_kamar,' . $this->id_kamar . ',id_kamar'
        ];
    }
}
