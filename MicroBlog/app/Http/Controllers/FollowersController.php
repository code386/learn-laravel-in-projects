<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 关注操作
    // 获得要关注的用户id
    public function store(User $user)
    {
        // 验证用户id和登录的id不一致
        $this->authorize('follow', $user);
        // 关注操作
        if ( !Auth::user()->isFollowing($user->id) ) {
            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }

    // 取消关注操作
    public function destroy(User $user)
    {
        $this->authorize('follow', $user);
        if ( Auth::user()->isFollowing($user->id) ) {
            Auth::user()->unfollow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }
}
