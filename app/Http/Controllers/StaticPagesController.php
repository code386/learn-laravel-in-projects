<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    // 访问首页
    public function home()
    {
        $feed_items=[];
        if (Auth::check()) {
            $feed_items = Auth::user()->feed()->paginate(30);
        }
        return view('static_pages/home', compact('feed_items'));
    }

    // 访问帮助页
    public function help()
    {
        return view('static_pages/help');
    }

    // 访问关于页
    public function about()
    {
        return view('static_pages/about');
    }

}
