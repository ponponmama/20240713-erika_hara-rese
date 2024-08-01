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

        // 営業終了時間が翌日にまたがる場合の対応
        if ($end->lt($start)) {
            $end->addDay();
        }

        Log::info("Open time: " . $start->toDateTimeString());
        Log::info("Close time: " . $end->toDateTimeString());

        if ($current->gt($end)) {
            $start->addDay();
            $end->addDay();
            Log::info("Adjusted to next day's start time: " . $start->toDateTimeString());
            Log::info("Adjusted to next day's end time: " . $end->toDateTimeString());
        } else if ($current->lt($start)) {
            Log::info("Before opening hours, using regular start time.");
        } else {
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