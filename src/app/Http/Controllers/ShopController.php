<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;


class ShopController extends Controller
{
    public function show($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        return view('reservation', ['shop' => $shop]);
    }

    // shop_list メソッドを追加
    public function shop_list()
    {
        $shops = Shop::all();
        return view('shop_list', ['shops' => $shops]);
    }

    
}
