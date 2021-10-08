<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class UsersController extends Controller
{

    use DisableAuthorization;

    protected $model = User::class;

    protected $request = UserRequest::class;

    public function includes() : array
    {
        return ['surveys'];
    }

    protected function beforeSave(Request $request, $user)
    {
        $user->password = Hash::make($request->password);
    }

    protected function beforeUpdate(Request $request, $user)
    {
        Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->email),
            ],
        ]);
    }
}
