<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Orion\Http\Controllers\Controller;

class UsersController extends Controller
{
    protected $model = User::class;
}
