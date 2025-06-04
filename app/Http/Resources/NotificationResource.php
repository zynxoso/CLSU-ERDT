<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class NotificationResource extends BaseResource
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
            'user_id' => $this->user_id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data,
            'link' => $this->link,
            'is_read' => $this->is_read,
            'email_sent' => $this->email_sent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Include relationships if they are loaded
        if ($this->relationLoaded('user')) {
            $data['user'] = new UserResource($this->user);
        }

        return $data;
    }
}
