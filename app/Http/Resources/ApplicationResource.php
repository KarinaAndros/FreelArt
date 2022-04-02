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
            'genre' => new GenreResource($this->genre),
            'user' => new ApplicationUserResource($this->user),
            'application_category_id' => new ApplicationCategoryResource($this->application_category),
            'description' => $this->description,
            'payment' => $this->payment,
            'writing_technique' => $this->writing_techique,
            'deadline' => Carbon::parse($this->deadline)->format('d.m.y'),
            'updated_at' => Carbon::now()->sub($this->updated_at)->diffForHumans()
        ];
    }
}
