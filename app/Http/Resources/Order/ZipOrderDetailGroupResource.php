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
            "ContractorNameActual" => $this->ContractorNameActual,
            "Decryption" => $this->Decryption,
            "SumQtyIzm" =>  number_format($this->SumQtyIzm, 0, ',', ' '),
            "Balance_View" => $this->Balance_View,
            "SumQtySales" => number_format($this->SumQtySales, 0, ',', ' '),
        ];
    }
}
