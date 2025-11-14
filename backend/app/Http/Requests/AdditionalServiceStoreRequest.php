<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdditionalServiceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_service' => 'required|string|max:255',
            'deskripsi_service' => 'required|string', 
            'harga_service' => 'required|integer|min:0',
            'url_gambar' => 'required|string'
        ];
    }
}
