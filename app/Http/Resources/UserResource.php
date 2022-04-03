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
        return[
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->patronymic,
            'login' => $this->login,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'accounts' => AccountResource::collection($this->accounts)
        ];
    }
}
