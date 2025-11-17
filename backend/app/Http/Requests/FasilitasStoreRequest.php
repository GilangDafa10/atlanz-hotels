<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FasilitasStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama_fasilitas' => 'required|string|max:255',
            'icon_fasilitas' => 'required|string|max:255',
        ];
    }
}
