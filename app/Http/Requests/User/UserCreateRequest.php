<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'password' => 'required|integer|confirmed',
            'is_admin' => 'sometimes|integer',
            'is_manager' => 'sometimes|integer',
            'account_id' => 'sometimes|integer',
        ];
    }
}
