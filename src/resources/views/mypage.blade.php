@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <p>{{ Auth::user()->user_name }}さん</p>

    <h2>予約一覧</h2>
    @foreach ($reservations as $reservation)
        <div class="reservation-view">
            <h3>{{ $reservation->shop->shop_name }}</h3>
            <p>予約日時: {{ $reservation->reservation_datetime }}</p>
            <p>人数: {{ $reservation->number }}人</p>
        </div>
    @endforeach
@endsection