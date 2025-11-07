<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShopService
{
    public function getBusinessHours($openTime, $closeTime, $date, $current)
    {
        Log::info('getBusinessHoursメソッドが呼び出されました');
        Log::info('パラメータ:', [
            'openTime' => $openTime,
            'closeTime' => $closeTime,
            'date' => $date,
            'current' => $current ? $current->toDateTimeString() : null
        ]);

        $times = [];
        $start = new Carbon($date . ' ' . $openTime);
        $end = new Carbon($date . ' ' . $closeTime);
        $selectedDate = new Carbon($date);
        $isToday = $selectedDate->isToday();

        if ($isToday) {
            // 当日の場合、現在時刻以降の時間のみ表示
            $currentRounded = $current->copy()->addMinutes(15 - ($current->minute % 15));
            $start = $currentRounded->greaterThan($start) ? $currentRounded : $start;
        }

        while ($start <= $end) {
            $times[] = $start->format('H:i');
            $start->addMinutes(15);
        }

        Log::info('返却する営業時間:', $times);
        return $times;
    }
}