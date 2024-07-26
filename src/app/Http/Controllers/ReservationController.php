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
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        $shop = Shop::find($request->shop_id);

        session([
            'reservation' => [
                'shop_id' => $shop->id,
                'shop_name' => $shop->name,
                'date' => $request->date,
                'time' => $request->time,
                'number' => $request->number
            ]
        ]);

        $reservation->load('shop');

        return redirect()->route('reservations.show', ['id' => $reservation->id]);

    }

    public function preview(Request $request)
    {
        Log::info('Preview data:', $request->all());

        // セッションから予約データを取得
        $reservationData = session('reservation');

        // 必要なデータを取得
        $shop = Shop::find($reservationData['shop_id']);

        // ビューにデータを渡して表示
        return view('reservation', [
            'reservation' => $reservationData,
            'shop' => $shop
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::with(['shop'])->findOrFail($id);
        
        return view('reservation', compact('reservation'));
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
