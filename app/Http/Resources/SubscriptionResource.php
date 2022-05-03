<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $user = auth()->user();
        $gender = $this->user->gender;
        $action = $gender == 'ж' ? 'подписалась' : 'подписался';
        return [
            'id' => $this->id,
            $this->mergeWhen($user->hasRole('admin'), [
                'user' => $this->user->surname . ' ' . $this->user->name,
                'avatar' => $this->user->avatar,
                'action' => $action
            ]),
            'user_email' => $this->user_email,
            'status' => $this->status,

            'updated_at' => Carbon::now()->sub($this->updated_at)->diffForHumans(),
        ];
    }
}
