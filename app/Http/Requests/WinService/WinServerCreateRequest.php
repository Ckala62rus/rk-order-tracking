<?php

namespace App\Http\Requests\WinService;

use Illuminate\Foundation\Http\FormRequest;

class WinServerCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'server_name' => 'required|unique:win_servers,server_name',
            'description' => 'string',
            'enable' => 'boolean',
        ];
    }
}
