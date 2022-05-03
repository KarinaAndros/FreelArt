<?php

namespace App\Http\Resources;

use App\Models\Account;
use App\Models\AccountUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\String\ignoreCase;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $user = auth()->id();
        $account_user = null;
        $role_name = null;
        $account_pro = Account::query()->where('title', '=', 'PRO аккаунт')->first();
        foreach ($this->accounts as $account) {
            if ($account->pivot->account_id === $account_pro->id)
                $account_user = $account_pro->title;
        }
        foreach ($this->getRoleNames() as $role) {
            if ($role != 'user') {
                $role_name = $role;
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'patronymic' => $this->when($this->patronymic != '', $this->patronymic),
            'email' => $this->email,
            'phone' => $this->when($this->phone != '', $this->phone),
            'link' => $this->when($this->hasRole('executor') && $this->link != null, $this->link),
            'avatar' => $this->when(!$this->avatar == null, $this->avatar),
            'subscription' => $this->mergeWhen($this->id == $user && !$this->subscription->isEmpty(), SubscriptionResource::collection($this->subscription)),
            'accounts' => $this->when($account_user != null, $account_user),
            'role' => $role_name,
        ];


    }
}
