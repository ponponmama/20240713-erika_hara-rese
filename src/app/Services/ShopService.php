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

        // 現在時刻が営業時間内かどうかをチェック
        if ($current->between($start, $end)) {
            // 営業時間内であれば、現在時刻から営業終了時間までの時間を計算
            $currentStart = $current->copy()->minute(0)->second(0)->addMinutes(15);
            while ($currentStart < $end) {
                $times[] = $currentStart->format('H:i');
                $currentStart->addMinutes(15);
            }
        } else {
            // 営業時間外であれば、次の営業開始時間を計算
            if ($current->lt($start)) {
                // まだ営業開始前ならその日の営業開始時間から終了時間までを表示
                while ($start < $end) {
                    $times[] = $start->format('H:i');
                    $start->addMinutes(15);
                }
            } else {
                // 営業終了後なら次の日の営業時間を表示
                $date = $current->copy()->addDay()->format('Y-m-d');
                $start = new Carbon($date . ' ' . $openTime);
                $end = new Carbon($date . ' ' . $closeTime);
                while ($start < $end) {
                    $times[] = $start->format('H:i');
                    $start->addMinutes(15);
                }
            }
        }

        return $times;
    }
}