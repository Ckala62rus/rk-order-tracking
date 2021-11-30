<?php

namespace App\Http\Resources\WinService;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

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
            'is_not_running' =>  count($this->services) > 0 ? $this->isNotRunning($this->services) : false,

            'services' => WinServiceResource::collection($this->services)
        ];
    }

    /**
     * If exist status != Running returh true
     * @param Collection $services
     * @return bool
     */
    public function isNotRunning(Collection $services): bool
    {
        foreach ($services as $service){
            if ($service->status != 'Running') {
                return true;
            }
        }
        return false;
    }
}
