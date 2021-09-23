<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class ZipOrderDetailGroupResource extends JsonResource
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
            "Article" => $this->Article,
            "Color" => $this->Color,
            "Config" => $this->Config,
            "Thickness" => $this->Thickness,
            "SumQtySpeciallSku" => number_format($this->SumQtySpeciallSku, 0, ',', ' '),
            "RealizationStatus" => $this->RealizationStatus,
        ];
    }
}
