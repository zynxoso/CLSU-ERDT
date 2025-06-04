<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'last_login_at' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'unread_notifications_count' => $this->unread_notifications_count,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('scholarProfile')) {
            $data['scholar_profile'] = new ScholarProfileResource($this->scholarProfile);
        }

        if ($this->relationLoaded('customNotifications')) {
            $data['notifications'] = NotificationResource::collection($this->customNotifications);
        }

        return $data;
    }
}
