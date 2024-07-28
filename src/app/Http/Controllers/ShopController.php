<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ShopController extends Controller
{
    // 店舗の詳細と予約ページを表示
    public function showDetails($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        $current = Carbon::now();  // 現在の日時を取得
        $closingTime = Carbon::parse($current->format('Y-m-d') . ' ' . $shop->close_time);

        // 営業時間外であれば翌日の日付をデフォルトとする
        $date = $current->lt($closingTime) ? $current->format('Y-m-d') : $current->addDay()->format('Y-m-d');

        $times = $this->getBusinessHours($shop->open_time, $shop->close_time, $date);

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

    
    private function getBusinessHours($openTime, $closeTime, $date)
    {
        $times = [];
        $current = Carbon::now();
        $targetDate = Carbon::parse($date);
        $start = new Carbon($date . ' ' . $openTime);
        $end = new Carbon($date . ' ' . $closeTime);

        Log::info("Open time: " . $start->toDateTimeString());
        Log::info("Close time: " . $end->toDateTimeString());

        // 現在の時間が営業終了時間を過ぎているか確認
        if ($current->gt($end)) {
            // 翌日の営業時間を設定
            $start->addDay();
            $end->addDay();
            Log::info("Adjusted to next day's start time: " . $start->toDateTimeString());
            Log::info("Adjusted to next day's end time: " . $end->toDateTimeString());
        } else if ($current->lt($start)) {
            // 現在の時間が営業開始時間より前の場合、営業開始時間から計算
            Log::info("Before opening hours, using regular start time.");
        } else {
            // 営業時間内であれば、現在の時間から次の整時まで待つ
            $start = $current->copy()->minute(0)->second(0)->addHour();
            Log::info("Adjusted start time for today: " . $start->toDateTimeString());
        }

        while ($start <= $end) {
            $times[] = $start->format('H:i');
            $start->addMinutes(30);
            Log::info("Adding time slot: " . $start->format('H:i'));
        }

        if (empty($times)) {
            Log::info("No times available for date: " . $date);
        }

        return $times;
    }
}
