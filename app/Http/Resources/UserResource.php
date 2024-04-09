<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role_level' => $this->role_level,
            'name' => $this->name,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'email' => $this->email,
            'code' => $this->code,
            'is_agree' => $this->is_agree,
            'created_by_social' => $this->created_by_social,
            'code' => $this->code,
            'password' => $this->password,
            'created_at' => $this->created_at->format('d M Y H:i a'),
            'updated_at' => $this->updated_at->format('d M Y H:i a'),
        ];
        
    }
}
