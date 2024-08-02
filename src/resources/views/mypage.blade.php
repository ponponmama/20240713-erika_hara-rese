@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <p class="user__name">{{ Auth::user()->user_name }}さん</p>
    <div class="sections-container">
        @if ($hideReservation == 0)
            <div class="reservation-section">
                <h2 class="section-title">予約状況</h2>
                @if (session('success'))
                    <div class="reservation-success">
                        {{ session('success') }}
                    </div>
                @endif
                @foreach ($reservations as $reservation)
                    <div class="reservation-summary">
                        <a href="{{ route('mypage', ['hide_reservation' => 1]) }}" class="close-button">
                            <img src="{{ asset('images/cross.png') }}" alt="Close">
                        </a>
                        <div class="reservation-summary-item">
                            <img src="{{ asset('images/clock.svg') }}" alt="Clock Icon" class="clock-icon">
                            <span class="reservation-summary-date">予約{{ $reservation->shop->id }}</span>
                        </div>
                        <div class="summary-item">
                            <label>Shop</label>
                            <span class="summary-date">{{ $reservation->shop->shop_name }}</span>
                        </div>
                        <div class="summary-item">
                            <label>Date</label>
                            <span class="summary-date">{{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('Y-m-d') }}</span>
                        </div>
                        <div class="summary-item">
                            <label>Time</label>
                            <span class="summary-date">{{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('H:i') }}</span>
                        </div>
                        <div class="summary-item">
                            <label>Number</label>
                            <span class="summary-date">{{ $reservation->number . '人' }}</span>
                        </div>
                        <div class="edit-button-container">
                            <a href="{{ route('reservations.edit', $reservation->id) }}" class="edit-reservation-button">変更</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="favorite-shops-section">   
            <h2 class="section-title">お気に入り店舗</h2>
            <div class="favorite-shops">
                @foreach ($favorites as $favorite)
                <div class="shop_card">
                    <img src="{{ asset($favorite->image) }}" alt="{{ $favorite->shop_name }}">
                    <div class="shop_info">
                        <h3 class="shop-name">{{ $favorite->shop_name }}</h3>
                        <p class="shop-guide">＃{{ $favorite->area }}  ＃{{ $favorite->genre }}</p>
                        <div class="button-container">
                            <a href="{{ route('shop.details', ['shop_id' => $favorite->id]) }}" class="shop-detail">詳しくみる</a>
                            @auth
                                @if(auth()->user()->favorites->contains($favorite))
                                    <form action="{{ route('shops.unfavorite', $favorite) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="favorite-button favorited">❤</button>
                                    </form>
                                @else
                                    <form action="{{ route('shops.favorite', $favorite) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="favorite-button">❤</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection