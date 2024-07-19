<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;

class ShopManagerController extends Controller
{
    // ショップ管理ダッシュボード表示
    public function index()
    {
        return view('shop_manager.dashboard');
    }

    // ショップ情報の管理
    public function manageShop()
    {
        $shops = Shop::all(); // 仮に全ショップを取得
        return view('shop_manager.manage-shop', ['shops' => $shops]);
    }
}
