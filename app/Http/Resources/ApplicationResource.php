<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ApplicationResource extends JsonResource
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
            'genre' => $this->genre->title,
            'user' => $this->user->name.' '.$this->user->surname,
            'user_avatar' => $this->user->avatar,
            'application_category' => $this->application_category->title,
            'description' => $this->description,
            'payment' => $this->payment,
            'writing_technique' => $this->writing_technique,
            'deadline' => Carbon::parse($this->deadline)->format('d.m.Y'),
            'updated_at' => Carbon::now()->sub($this->updated_at)->diffForHumans()
        ];
    }
}
