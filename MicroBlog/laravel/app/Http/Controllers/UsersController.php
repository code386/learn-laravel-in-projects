<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    // 利用中间件进行登录验证
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 关注的人列表
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = $user->name . "关注列表";
        return view('users.show_follow', compact('users', 'title'));
    }

    // 粉丝列表
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = $user->name . "粉丝列表";
        return view('users.show_follow', compact('users', 'title'));
    }

    // 验证邮箱动作
    public function confirmEmail($token)
    {
        // 通过token查找是否存在，不存在抛出错误
        $user = User::where('activation_token', $token)->firstOrFail();

        // 更正激活信息
        $user->activation_token = null;
        $user->activated = true;
        $user->save();

        // 登录、提示信息和跳转页面
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', $user);
    }

    // 发送邮件验证
    protected function sendEmailConfirmationTo($user)
    {
        $view = "emails.confirm";
        $data = compact('user');
        $from = "public@ouccs.net";
        $name = "管理员";
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    // 删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash("success", "成功删除用户！");
        return back();
    }

    // 访问所有用户列表页面
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
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
        // Auth::login($user);
        // 成功后消息提示
        $this->sendEmailConfirmationTo($user);
        session()->flash("success", "邮件已发送到你的注册邮箱上，请注意查收");
        //return redirect()->route('users.show', [$user]);
        return redirect('/');
    }

    // 个人用户信息界面
    public function show(User $user)
    {
        // 个人用户发布的微博
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(10);
        return view('users.show', compact('user', 'statuses'));
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
