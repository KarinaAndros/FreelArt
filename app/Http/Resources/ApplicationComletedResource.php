<?php

namespace App\Http\Resources;

use App\Models\Application;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationComletedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $application = Application::findOrFail($this->application_id);
        $user = User::findOrFail($this->user_id);
        return [
            'id' => $this->id,
            'description' => $application->description,
            'genre' => $application->genre->title,
            'category' => $application->application_category->title,
            'payment' => $application->payment,
            'user' => $user->surname . ' ' . $user->name,
            'user_avatar' => $user->avatar,
            'complete' => Carbon::parse($this->created_at)->format('d.m.Y')
        ];
    }
}
