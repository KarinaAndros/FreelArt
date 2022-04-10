<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (auth()->user()){
            if (auth()->user()->hasRole('admin')){
                return [
                    'id' => $this->id,
                    'user_email' => $this->user_email,
                    'status' => $this->status,
                    'updated_at' => $this->created_at
                ];
            }
        }
        return [
            'status' => $this->status,
        ];
    }
}
