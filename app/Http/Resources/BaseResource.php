<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Success status
     *
     * @var bool
     */
    protected $success = true;

    /**
     * Optional message
     *
     * @var string|null
     */
    protected $message = null;

    /**
     * Set success status
     *
     * @param bool $success
     * @return $this
     */
    public function success(bool $success): self
    {
        $this->success = $success;
        return $this;
    }

    /**
     * Set response message
     *
     * @param string $message
     * @return $this
     */
    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => parent::toArray($request),
        ];
    }
}
