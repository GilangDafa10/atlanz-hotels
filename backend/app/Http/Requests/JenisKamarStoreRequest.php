<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisKamarStoreRequest extends FormRequest
{
    public function rules(): array
    {
        $method = $this->method();

        switch ($method) {
            case 'POST':
                return [
                    'jenis_kasur'     => 'required|string|max:255',
                    'harga_permalam'  => 'required|numeric|min:0',
                    'deskripsi'       => 'nullable|string',
                    'url_gambar'      => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                    'fasilitas'       => 'nullable',
                    'fasilitas.*'     => 'integer|exists:fasilitas,id_fasilitas',
                ];
            case 'PUT':
                return [
                    'jenis_kasur'     => 'required|string|max:255',
                    'harga_permalam'  => 'required|numeric|min:0',
                    'deskripsi'       => 'nullable|string',
                    'url_gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                    'fasilitas'       => 'nullable|array',
                    'fasilitas.*'     => 'integer|distinct|exists:fasilitas,id_fasilitas',
                ];
            default:
                return [];
        }
    }
}
