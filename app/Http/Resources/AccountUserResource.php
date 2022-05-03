<?php

namespace App\Http\Resources;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::find($this->user_id);
        $account = Account::find($this->account_id);
        return[
            'user' => $user->surname.' '.$user->name,
            'user_avatar' => $user->avatar,
            'account' => $account->title
        ];
    }
}
