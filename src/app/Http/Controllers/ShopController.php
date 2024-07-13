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
}
