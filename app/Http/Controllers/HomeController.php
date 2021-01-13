<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Trang chủ
    public function GetHome() {
        return view('user.home');
    }
}
