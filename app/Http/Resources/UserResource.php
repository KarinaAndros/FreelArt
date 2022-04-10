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
                'name' => $this->name,
                'surname' => $this->surname,
                'patronymic' => $this->patronymic,
                'email' => $this->email,
                'phone' => $this->phone,
                'link' => $this->when($this->hasRole('executor'), $this->link),
                'avatar' => $this->avatar,
                'subscription' => SubscriptionResource::collection($this->subscription),
                'accounts' => AccountResource::collection($this->accounts)
            ];


    }
}
