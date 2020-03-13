<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // 验证提交的信息
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        // 将用户信息存入表中
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // 信息插入成功后，将用户自动登录
        Auth::login($user);
        // 成功后消息提示
        session()->flash("success", "欢迎，您将在这里开启一段新的旅程~");
        return redirect()->route('users.show', [$user]);
    }

    // 个人用户信息界面
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

}
