<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class UserRequest extends Request
{
    public function storeRules() : array
    {
        return [
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'role' => 'required|in:admin,student,training_center',
        ];
    }
}
