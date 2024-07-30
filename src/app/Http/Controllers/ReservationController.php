<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Http\Requests\StoreReservationRequest;
use Illuminate\Support\Facades\Log;


class ReservationController extends Controller
{
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

        return view('reservation', ['shop' => $shop, 'reservationDetails' => $reservationDetails]);
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
        //予約データの処理
        $reservation = new Reservation();
        $reservation->shop_id = $request->shop_id;
        $reservation->reservation_datetime = $request->date . ' ' . $request->time;
        $reservation->number = $request->number;
        $reservation->user_id = auth()->id();
        $reservation->save();
        
        // 予約情報をフラッシュセッションに保存
        session()->put('reservation_details', $reservation);

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
}
