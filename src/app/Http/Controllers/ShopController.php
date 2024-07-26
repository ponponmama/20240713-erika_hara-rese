<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Carbon\Carbon;


class ShopController extends Controller
{
    public function show($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        $date = Carbon::now();
        $time = Carbon::now()->format('H:i:s');  // 現在の時刻を取得 

        // 営業時間内の時間選択肢を生成
        $times = [];
        $openTime = new Carbon($shop->open_time);
        $closeTime = (new Carbon($shop->close_time))->subHour();  // 閉店時間の1時間前

        while ($openTime <= $closeTime) {
            $times[] = $openTime->format('H:i');
            $openTime->addMinutes(15);  // 15分間隔で時間を増やす
        }

        return view('reservation', [
            'shop' => $shop,
            'date' => $date, 
            'times' => $times,
            'number' => 0

        ]);
    }

    // shop_list メソッドを追加
    public function shop_list()
    {
        $shops = Shop::all();
        return view('shop_list', ['shops' => $shops]);
    }

    public function showReservation($shop)
    {
        $shop = Shop::findOrFail($shop);
        return view('reservation', ['shop' => $shop]);
    }
    
}
