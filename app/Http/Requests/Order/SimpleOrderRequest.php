<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class SimpleOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "company_id" => "nullable",
            "status" => "int",
            "date_from" => "string",
            "date_to" => "string",
            "realization_status" => "int",
            "color" => "nullable",
            "article" => "nullable",
        ];
    }
}
