<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SimpleOrderResource extends JsonResource
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
            "id" => $this["Row#"],
            "OrderNumber" => $this->OrderNumber,
            "Article" => $this->Article,
            "Color" => $this->Color,
            "Config" => $this->Config,
            "Thickness" => $this->Thickness,
            "SumQtySpeciallSku" => number_format($this->SumQtySpeciallSku, 0, ',', ' '),
            "SumQtyIzm" => number_format($this->SumQtyIzm, 0, ',', ' '),
            "SumQtySales" => number_format($this->SumQtySales, 0, ',', ' '),
            "DeliveryDate" => $this->DeliveryDate ? Carbon::parse($this->DeliveryDate)->format('d-m-Y') : "",
            "EndDate" => $this->EndDate ? Carbon::parse($this->EndDate)->format('d-m-Y') : "",
            "LeadOrLagTime" => $this->LeadOrLagTime,
            "ContractorNameActual" => $this->ContractorNameActual,
            "AccountNum" => $this->AccountNum,
            "ItemId" => $this->ItemId,
            "RegistrChange" => $this->RegistrChange,
        ];
    }
}
