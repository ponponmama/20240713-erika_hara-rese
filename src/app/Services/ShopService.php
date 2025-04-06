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
        $selectedDate = new Carbon($date);
        $currentDate = $current ? $current->format('Y-m-d') : null;
        $today = Carbon::now()->startOfDay();

        // 前日の日付の場合は空の配列を返す
        if ($selectedDate->lt($today)) {
            Log::info('前日の日付のため、空の配列を返します');
            return $times;
        }

        $start = new Carbon($date . ' ' . $openTime);
        $end = new Carbon($date . ' ' . $closeTime);

        // 営業終了時間の30分前を設定
        $end->subMinutes(30);

        Log::info('営業時間:', [
            'start' => $start->toDateTimeString(),
            'end' => $end->toDateTimeString()
        ]);

        if ($currentDate === null || $selectedDate->format('Y-m-d') != $currentDate) {
            // 選択された日付が現在の日付と異なる場合、その日の全営業時間を表示
            Log::info('選択された日付が現在の日付と異なるため、全営業時間を表示します');
            while ($start < $end) {
                $times[] = $start->format('H:i');
                $start->addMinutes(15);
            }
        } else {
            // 選択された日付が現在の日付と同じ場合
            Log::info('選択された日付が現在の日付と同じです');
            if ($current->between($start, $end)) {
                // 現在時刻を15分単位に丸める
                $currentStart = $current->copy()->second(0);
                $minute = $currentStart->minute;
                $roundedMinute = $minute + (15 - $minute % 15);
                if ($roundedMinute >= 60) {
                    $currentStart->addHour()->minute(0);
                } else {
                    $currentStart->minute($roundedMinute);
                }

                Log::info('現在時刻を15分単位に丸めました:', [
                    'current' => $current->toDateTimeString(),
                    'currentStart' => $currentStart->toDateTimeString()
                ]);

                // 営業終了時間まで15分ごとに時間を追加
                while ($currentStart < $end) {
                    $times[] = $currentStart->format('H:i');
                    $currentStart->addMinutes(15);
                }
            } else {
                Log::info('現在時刻が営業時間外です');
            }
        }

        Log::info('返却する営業時間:', $times);
        return $times;
    }
}