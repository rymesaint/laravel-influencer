<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            $this->mergeWhen(Auth::user() && Auth::user()->isAdmin(), [
                'role' => $this->role,
            ]),
            $this->mergeWhen(Auth::user() && Auth::user()->isInfluencer(), [
                'revenue' => $this->revenue,
            ]),
        ];
    }
}
