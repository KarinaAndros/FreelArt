<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PictureResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'user' => $this->user->name,
            'user_avatar' => $this->user->avatar,
            'genre' => $this->genre->title,
            'description' => $this->description,
            'price' => $this->price,
            'discount' => $this->discount,
            'size' => $this->size,
            'writing_technique' => $this->writing_technique,
            'img' => $this->img,
            'created_at' => $this->created_at
        ];
    }
}
