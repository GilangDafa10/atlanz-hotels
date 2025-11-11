<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $this->id_user . ',id_user',
            'password' => 'sometimes|string|min:6',
            'no_hp'    => 'nullable|string|max:20',
            'id_role'  => 'sometimes|exists:roles,id_role',
        ];
    }
}
