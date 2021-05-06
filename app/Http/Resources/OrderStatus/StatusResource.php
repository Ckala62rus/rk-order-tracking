<?php

namespace App\Http\Resources\OrderStatus;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed IDProdOrderStatus
 * @property mixed ProdOrderStatus
 */
class StatusResource extends JsonResource
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
            'id' => $this->IDProdOrderStatus,
            'status' => $this->ProdOrderStatus,
        ];
    }
}
