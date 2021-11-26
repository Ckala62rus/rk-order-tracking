<?php

namespace App\Http\Resources\WinService;

use Illuminate\Http\Resources\Json\JsonResource;

class WinServerWithServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'server_name' => $this->server_name,
            'description' => $this->description,

            'services' => WinServiceResource::collection($this->services)
        ];
    }
}
