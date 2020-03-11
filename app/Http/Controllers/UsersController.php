<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    // 跳转至注册页面
    public function create()
    {
        return view('users.create');
    }
}
