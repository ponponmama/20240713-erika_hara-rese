<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
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

        $reservation = new Reservation();
        $reservation->shop_id = $request->shop_id;
        $reservation->reservation_datetime = $request->date . ' ' . $request->time;
        $reservation->number = $request->number;
        $reservation->user_id = auth()->id();
        $reservation->save();

        $reservation->load('shop');

        return redirect()->route('reservations.show', ['id' => $reservation->id]);
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

    
}
