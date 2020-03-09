<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    // 访问首页
    public function home()
    {
        return view('static_pages/home');
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
