<?php

namespace App\Http\Resources\WinService;

use Illuminate\Http\Resources\Json\JsonResource;

class WinServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'server_id' => $this->server_id,
            'server' => $this->server,
            'service_name' => $this->service_name,
            'display_name' => $this->display_name,
            'status' => $this->status,
            'visible' => $this->visible,
        ];
    }
}
