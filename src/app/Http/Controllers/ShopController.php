<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
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

    // 店舗一覧を表示,検索フォームに渡す。
    public function index(Request $request)
    {
        // 管理画面から遷移した場合、セッションを設定
        // role 1の場合はfrom_adminとshop_idの両方が必要
        // role 2の場合はshop_idがあればセッションを設定
        if (auth()->check() && (auth()->user()->role === 1 || auth()->user()->role === 2)) {
            if (auth()->user()->role === 1 && $request->has('from_admin') && $request->has('shop_id')) {
                session(['shop_success' => true, 'new_shop_id' => $request->input('shop_id')]);
            } elseif (auth()->user()->role === 2 && $request->has('shop_id')) {
                session(['shop_success' => true, 'new_shop_id' => $request->input('shop_id')]);
            }
        }

        $query = Shop::with(['areas', 'genres']); // 関連データを事前にロード

        if ($request->has('search-area') && $request->input('search-area') != '') {
            $query->whereHas('areas', function ($q) use ($request) {
                $q->where('id', $request->input('search-area'));
            });
        }

        if ($request->has('search-genre') && $request->input('search-genre') != '') {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('id', $request->input('search-genre'));
            });
        }

        if ($request->has('search-shop__name') && $request->input('search-shop__name') != '') {
            $query->where('shop_name', 'like', '%' . $request->input('search-shop__name') . '%');
        }

        $shops = $query->get();
        $areas = Area::all();
        $genres = Genre::all();

        return view('shops.index', ['shops' => $shops, 'areas' => $areas, 'genres' => $genres]);
    }

    // 店舗詳細ページで予約時の日付の日付選択の入力フィールドのonchangeイベント用
    public function updateDate(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $date = $request->input('date');
        $current = Carbon::now();

        // 選択された日付をセッションに保存
        session(['selected_date' => $date]);

        // 日付に基づいて営業時間を計算
        $selectedDate = new Carbon($date);
        $isToday = $selectedDate->isToday();
        $currentForBusinessHours = $isToday ? $current : null;

        // 営業時間の取得
        $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date, $currentForBusinessHours);

        // セッションから予約情報を取得
        $reservationDetails = session('reservation_details');

        $reservation = Reservation::where('shop_id', $id)->latest()->first();

        return view('shops.detail', [
            'shop' => $shop,
            'date' => $date,
            'times' => $times,
            'reservation' => $reservation,
            'reservationDetails' => $reservationDetails,
        ]);
    }
}
