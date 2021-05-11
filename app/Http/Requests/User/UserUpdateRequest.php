<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => 'required|email|string|max:255',
            'password' => 'sometimes|confirmed',
            'is_admin' => 'nullable|integer',
            'is_manager' => 'nullable|integer',
            'account_id' => 'nullable|integer',
        ];
    }
}
