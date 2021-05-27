<?php

namespace App\Http\Resources\RealisationStatus;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed RealizationStatus
 * @property mixed Decription
 */
class RealisationStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'realisation_status' => $this->RealizationStatus,
            'decription' => $this->Decryption,
        ];
    }
}
