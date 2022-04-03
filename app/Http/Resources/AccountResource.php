<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $user = auth()->user();
        if ($user){
            if ($user->hasRole('admin')){
                return [

                    'id' => $this->id,
                    'title' => $this->title,
                    'description' => $this->description,
                    'price' => $this->price,
                    'discount' => $this->discount,
                    'application_category' => new ApplicationCategoryResource($this->application_category),
                ];
            }
            else{
                return [
                    'title' => $this->title,
                ];
            }

        }
        return [
            'title' => $this->title,
        ];
    }
}
