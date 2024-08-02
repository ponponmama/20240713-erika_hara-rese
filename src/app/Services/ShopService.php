<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ShopService
{
    public function getBusinessHours($openTime, $closeTime, $date, $current)
    {
        $times = [];
        $start = new Carbon($date . ' ' . $openTime);
        $end = new Carbon($date . ' ' . $closeTime);

        // 営業終了時間が翌日にまたがる場合の対応
        if ($end->lt($start)) {
            $end->addDay();
        }

        // 営業時間内であれば、現在時刻から営業終了時間までの時間を計算
        if ($current->between($start, $end)) {
            $currentStart = $current->copy()->minute(0)->second(0)->addMinutes(15);
            while ($currentStart < $end) {
                $times[] = $this->formatTime($currentStart, $start);
                $currentStart->addMinutes(15);
            }
        } else {
            // 営業時間外であれば、次の営業開始時間から終了時間までの全時間を表示
            while ($start < $end) {
                $times[] = $this->formatTime($start, $start);
                $start->addMinutes(15);
            }
        }

        return $times;
    }

    private function formatTime(Carbon $time, Carbon $start)
    {
        // 24時を超える時間を26時などと表示
        if ($time->hour >= 24) {
            $hour = $time->hour - 24;
            return sprintf('%02d:%02d', $hour, $time->minute);
        }
        return $time->format('H:i');
    }
}