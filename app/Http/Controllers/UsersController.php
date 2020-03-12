<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    // 跳转至注册页面
    public function create()
    {
        return view('users.create');
    }

    // 注册用户信息
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        return ;
    }

    // 个人用户信息界面
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

}
