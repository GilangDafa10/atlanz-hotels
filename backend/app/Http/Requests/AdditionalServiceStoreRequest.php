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
        $method = $this->method();

        switch ($method) {
            case 'POST':
                return [
                    'nama_service' => 'required|string|max:255',
                    'deskripsi_service' => 'required|string',
                    'harga_service' => 'required|numeric|min:0',
                    'url_gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                ];
            case 'PUT':
                return [
                    'nama_service' => 'sometimes|string|max:255',
                    'deskripsi_service' => 'sometimes|string',
                    'harga_service' => 'sometimes|numeric|min:0',
                    'url_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                ];
            default:
                return [];
        }
    }
}
