<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id,
            'id_role'     => $this->id_role,
            'name'     => $this->name,
            'email'    => $this->email,
            'no_hp'    => $this->no_hp,
        ];
    }
}
