<?php

namespace App\Http\Requests\Rdp;

use Illuminate\Foundation\Http\FormRequest;

class RdpDetailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required',
            'date' => 'required',
        ];
    }
}
