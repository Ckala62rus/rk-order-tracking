<?php

namespace App\Http\Requests\Rdp;

use Illuminate\Foundation\Http\FormRequest;

class RdpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'limit' => 'required',
            'date_start' => 'string',
            'date_end' => 'string',
            'login' => 'string',
        ];
    }
}
