<?php

namespace App\Http\Resources\Rdp;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RdpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'date' => $this->date,
            'work_time' => Carbon::create()
                    ->addMinute((int)$this->work_time)
                    ->format('H:i') . ' ( min: ' . $this->work_time . ' )',
        ];
    }
}
