<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user_id'    => $this->id,
            "documento"  => $this->documento,
            "nombres"    => $this->nombres,
            "apellidos"  => $this->apellidos,
            "email"      => $this->email,
            "token"      => $this->createToken("auth_token")->plainTextToken,
            "roles"      => $this->roles->pluck('name') ?? [],
            "roles_permisos" => $this->getPermissionsViaRoles() ?? [],
            "permisos"   => $this->permissions->pluck('name') ?? []

        ];
    }
}
