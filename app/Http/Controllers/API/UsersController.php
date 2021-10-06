<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class UsersController extends Controller
{

    use DisableAuthorization;

    protected $model = User::class;

    public function includes() : array
    {
        return ['surveys'];
    }
}
