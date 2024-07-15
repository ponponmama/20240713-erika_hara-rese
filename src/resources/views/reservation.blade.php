@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')
        <div class="container">
            <div class="content">
                <div class="shop_name">
                    <h1>{{ $shop->shop_name }}</h1>
                </div>
                <div class="shop-details">
                    <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}">
                    <p>{{ $shop->description }}</p>
                </div>
                <div class="reservation-form">
                    <h2>予約</h2>
                    <form action="/reserve" method="post">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        <label for="date">日付</label>
                        <input type="date" id="date" name="date">
                        <label for="time">時間</label>
                        <input type="time" id="time" name="time">
                        <label for="number">人数</label>
                        <input type="number" id="number" name="number" min="1">
                        <button type="submit">予約する</button>
                    </form>
                </div>
            </div>
        </div>
@endsection