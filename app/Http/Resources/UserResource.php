<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserResource extends BaseResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at,
            'last_login_ip' => $this->last_login_ip,
            'password_expires_at' => $this->password_expires_at,
            'password_changed_at' => $this->password_changed_at,
            'must_change_password' => $this->must_change_password,
            'is_default_password' => $this->is_default_password,
            'email_notifications' => $this->email_notifications,
            'fund_request_notifications' => $this->fund_request_notifications,
            'document_notifications' => $this->document_notifications,
            'manuscript_notifications' => $this->manuscript_notifications,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
