<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShopService
{
    public function getBusinessHours($openTime, $closeTime, $date)
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