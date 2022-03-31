<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'picture_id' => new PictureResource($this->picture),
            'user_id' => new UserResource($this->user),
            'amount' => $this->amount,
            'discount' => $this->discount,
            'total' => $this->total,
            'address' => $this->address,
            'status' => $this->status,
            'created_at' => Carbon::now()->sub($this->created_at)->diffForHumans()
        ];
    }
}
