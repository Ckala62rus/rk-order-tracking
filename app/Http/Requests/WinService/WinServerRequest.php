<?php

namespace App\Http\Requests\WinService;

use Illuminate\Foundation\Http\FormRequest;

class WinServerRequest extends FormRequest
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
        ];
    }
}
