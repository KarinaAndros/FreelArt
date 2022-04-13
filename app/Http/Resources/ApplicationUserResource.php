<?php

namespace App\Http\Resources;

use App\Models\ApplicationUser;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationUserResource extends JsonResource
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
             'application' => new ApplicationResource($this->application),
            'message' => $this->message,
            'status' => $this->status
        ];
    }
}
