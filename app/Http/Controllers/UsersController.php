<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    // 利用中间件进行登录验证
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

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

    // 跳转至用户信息编辑页面
    public function edit(User $user)
    {
        // 验证要编辑的用户id和传递的用户id是否一致
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // 更新用户信息
    public function update(User $user, Request $request)
    {
        // 验证要编辑的用户id和传递的用户id是否一致
        $this->authorize('update', $user);
        // 验证信息
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        // 判断password是否有值
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        // 执行更新操作
        $user->update($data);
        // 提示更新成功
        session()->flash('success', '个人信息更新成功！');
        return redirect()->route('users.show', $user);
    }

}
