<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Http\Requests\StoreReservationRequest;
use Carbon\Carbon;
use App\Services\ShopService;
use Illuminate\Support\Facades\Log;


class ReservationController extends Controller
{
    protected $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //予約一覧表示
    public function index()
    {
        $reservations = Reservation::all();

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //予約作成ページ表示
    public function create(Request $request)
    {
        $reservationDetails = session()->get('reservation_details', null);
        $shop = isset($reservationDetails) ? Shop::find($reservationDetails->shop_id) : null;

        if ($shop) {
            $current = Carbon::now();
            $startToday = Carbon::parse($current->format('Y-m-d') . ' ' . $shop->open_time);
            $endToday = Carbon::parse($current->format('Y-m-d') . ' ' . $shop->close_time);

            // 営業終了時間が翌日にまたがる場合、終了時間に1日を加算
            if ($shop->close_time < $shop->open_time) {
                $endToday->addDay();
            }

            // 現在時刻が営業時間内または営業開始前の場合、同日の日付を使用
            // 現在時刻が営業終了時間を過ぎている場合、翌日の日付を使用
            if ($current->between($startToday, $endToday)) {
                $date = $current->format('Y-m-d');
            } else {
                $date = $current->copy()->subDay()->format('Y-m-d');
            }

            $times = $this->shopService->getBusinessHours($shop->open_time, $shop->close_time, $date);
        } else {
            $date = null;
            $times = [];
        }

        return view('reservation', [
            'shop' => $shop,
            'reservationDetails' => $reservationDetails,
            'date' => $date,
            'times' => $times
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //予約フォームの保存とsessionの保存。
    public function store(StoreReservationRequest $request)
    {
        Log::info('Store method called');
        $shop = Shop::find($request->shop_id);
        $reservationDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->time);

        // 営業時間が翌日にまたがる場合の日付調整
        if ($shop->close_time < $shop->open_time) {
            $closingTimeToday = Carbon::parse($request->date . ' ' . $shop->close_time)->addDay();
            if ($reservationDateTime->gt($closingTimeToday)) {
                // 予約日時が営業終了時間を超えている場合、日付を1日減らす
                $reservationDateTime->subDay();
            }
        }

        // 予約データの保存
        $reservation = new Reservation();
        $reservation->shop_id = $request->shop_id;
        $reservation->reservation_datetime = $reservationDateTime->format('Y-m-d H:i:s');
        $reservation->number = $request->number;
        $reservation->user_id = auth()->id();
        $reservation->save();
    
        session()->put('reservation_details', $reservation);
        session()->put('last_visited_shop_id', $reservation->shop_id);

        // 予約完了ページにリダイレクト
        return redirect()->route('reservation.done');
    }
    
    public function done()
    {
        return view('done'); // 予約完了ページのビューを返す
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($shopId)
    {
        $shop = Shop::find($shopId);

        return view('reservation', ['shop' => $shop]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update($request->all());
        return redirect()->route('reservations.index')->with('success', '予約が更新されました。');
    }

    /**
    * ユーザーの予約一覧を表示するメソッド。
    *
    * @return \Illuminate\Http\Response
    */
    public function myReservations()
    {
        $user_id = auth()->id(); // ログインユーザーのIDを取得
        $reservations = Reservation::where('user_id', $user_id)->with('shop')->get(); // ユーザーの予約と関連する店舗情報を取得

        return view('reservations.my', ['reservations' => $reservations]); // ビューにデータを渡す
    } 

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('mypage')->with('success', '予約が削除されました。');
    }
}
