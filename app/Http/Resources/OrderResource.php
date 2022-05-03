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
            'picture_id' => $this->picture->id,
            'picture_name' => $this->picture->title,
            $this->mergeWhen(auth()->user()->hasRole('admin'), [
                'user_id' => $this->user->id,
                'user_name' => $this->user->surname.' '.$this->user->name,
            ]),
            'amount' => $this->amount,
            'discount' => $this->discount,
            'total' => $this->total,
            'address' => $this->address,
            'status' => $this->status,
            'created_at' => Carbon::now()->sub($this->created_at)->diffForHumans()
        ];
    }
}
