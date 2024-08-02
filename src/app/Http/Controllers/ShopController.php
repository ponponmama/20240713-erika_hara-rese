<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Carbon\Carbon;
use App\Services\ShopService;
use Illuminate\Support\Facades\Log;


class ShopController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    // 店舗の詳細と予約ページを表示
    public function shopDetails($id)
    {
        $shop = Shop::findOrFail($id);
        $current = Carbon::now();
        $startToday = Carbon::parse($current->format('Y-m-d') . ' ' . $shop->open_time);
        $endToday = Carbon::parse($current->format('Y-m-d') . ' ' . $shop->close_time);

        // 営業終了時間が翌日にまたがる場合、終了時間に1日を加算
        if ($shop->close_time < $shop->open_time) {
            $endToday->addDay();
        }

        // 現在時刻が0時から営業終了時間（翌日の2時など）の間である場合、前日の日付を使用
        if ($current->hour < $endToday->hour && $current->hour < 6) {  // 6時までを深夜と仮定
            $date = $current->copy()->subDay()->format('Y-m-d');
        } else if ($current->gt($endToday)) {
            // 現在時刻が営業終了時間を過ぎている場合、翌日の日付を使用
            $date = $current->copy()->addDay()->format('Y-m-d');
        } else {
            // それ以外の場合は、同日の日付を使用
            $date = $current->format('Y-m-d');
        }

        $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date, $current);

        return view('shops.detail', [
            'shop' => $shop,
            'date' => $date,
            'times' => $times,
        ]);
    }

    // 店舗一覧を表示,検索フォームに渡す。
    public function index(Request $request)
    {
        $query = Shop::query();

        $filterApplied = false;

            if ($request->has('search-area') && $request->input('search-area') != '') {
            $query->where('area', $request->input('search-area'));
            $filterApplied = true;
        }

        if ($request->has('search-genre') && $request->input('search-genre') != '') {
            $query->where('genre', $request->input('search-genre'));
            $filterApplied = true;
        }

        if ($request->has('search-shop__name') && $request->input('search-shop__name') != '') {
            $query->where('shop_name', 'like', '%' . $request->input('search-shop__name') . '%');
            $filterApplied = true;
        }

        $shops = $filterApplied ? $query->get() : Shop::all();

        $areas = Shop::distinct()->pluck('area');
        $genres = Shop::distinct()->pluck('genre');

        return view('shops.index', ['shops' => $shops, 'areas' => $areas, 'genres' => $genres]);
    }
}
