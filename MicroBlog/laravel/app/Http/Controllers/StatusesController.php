<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 发布微博
    public function store(Request $request)
    {
        // 验证
        $this->validate($request, [
            'content' => 'required|max:140',
        ]);
        // 数据库添加数据
        Auth::user()->statuses()->create([
            'content' => $request['content'],
        ]);
        // 提示成功
        session()->flash('success', '微博发布成功');
        // 返回之前的页面
        return redirect()->back();
    }

    // 删除指定微博
    public function destroy(Status $status)
    {
        // 验证用户id和文章所属id是否一致
        $this->authorize('destroy', $status);
        // 验证通过执行删除操作
        $status->delete();
        // 通知删除成功
        session()->flash('success', '微博删除成功！');
        // 跳转上一页
        return redirect()->back();
    }
}
