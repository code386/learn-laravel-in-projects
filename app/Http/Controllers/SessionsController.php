<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    // 跳转至登录页面
    public function create()
    {
        return view('sessions.create');
    }

    // 验证用户提交的信息，判断是否允许登录
    public function store(Request $request)
    {
        // 验证用户提交的数据是否合法
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        // 验证用户提交的信息是否和数据库中的一致
        if(Auth::attempt($credentials)) {
            session()->flash("success", "欢迎回来！");
            return redirect()->route("users.show", [Auth::user()]);
        } else {
            session()->flash("danger", "很抱歉，邮箱或密码不匹配");
            return redirect()->back()->withInput();
        }
    }

    // 用户退出
    public function destroy()
    {
        Auth::logout();
        session()->flash("success", "您已成功退出！");
        return redirect('login');
    }
}
