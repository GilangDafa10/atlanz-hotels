<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(auth()->id())
            ],

            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
        ];
    }
}
