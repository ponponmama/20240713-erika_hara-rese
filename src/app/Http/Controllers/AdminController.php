<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // 管理ダッシュボード表示
    public function index()
    {
        return view('admin.dashboard');
    }

    // shop_manager の管理画面
    public function manageShopManagers()
    {
        $managers = User::where('role', 'shop_manager')->get();
        return view('admin.manage-shop-managers', ['managers' => $managers]);
    }
}
