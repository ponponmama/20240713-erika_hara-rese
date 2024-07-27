<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Carbon\Carbon;


class ShopController extends Controller
{
    // 店舗の詳細と予約ページを表示
    public function showDetails($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        $date = Carbon::now()->format('Y-m-d');
        $times = $this->getBusinessHours($shop->open_time, $shop->close_time);

        return view('reservation', [
            'shop' => $shop,
            'date' => $date,
            'times' => $times,
        ]);
    }

    // 店舗一覧を表示
    public function shop_list()
    {
        $shops = Shop::all();
        return view('shop_list', ['shops' => $shops]);
    }

    
    private function getBusinessHours($openTime, $closeTime)
    {
        $times = [];
        $current = Carbon::now();
        $start = new Carbon($openTime);
        $end = new Carbon($closeTime);

        // 営業時間が現在時刻より前なら、現在時刻を開始時間とする
        if ($start < $current) {
            $start = $current->copy()->minute(0)->second(0); // 分と秒を切り捨て
            $start->addHour(); // 次の整時に設定
        }

        while ($start <= $end) {
            $times[] = $start->format('H:i');
            $start->addMinutes(30); // 30分間隔で増やす
        }
        return $times;
    }
}
