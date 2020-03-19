<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    // 访问页面权限控制
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

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
        if(Auth::attempt($credentials, $request->has('remember'))) {
            // 判断用户是否邮箱激活
            if (Auth::user()->activated) {
                session()->flash("success", "欢迎回来！");
                $fallback = route("users.show", [Auth::user()]);
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash("warning", "你的账号未激活，请检查邮箱中的注册邮件进行激活。");
                return redirect('/');
            }
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
