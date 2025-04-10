@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <h1 class="user__name">{{ Auth::user()->user_name }}さん</h1>
    <div class="container sections-container">
        @if ($hideReservation == 0)
            <div class="reservation-section">
                <h2 class="section-title">予約状況</h2>
                @if (session('success'))
                    <div class="reservation-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('success_message'))
                    <div class="payment-success">
                        {{ session('success_message') }}
                    </div>
                @endif
                @foreach ($reservations as $reservation)
                    <div class="reservation-summary">
                        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" id="update-form-{{ $reservation->id }}" class="update_form">
                            @csrf
                            @method('PUT')
                            <a href="{{ route('mypage', ['hide_reservation' => 1]) }}" class="close-button">
                                <img src="{{ asset('images/cross.png') }}" alt="Close" class="cross_image">
                            </a>
                            <div class="reservation-summary-item">
                                <img src="{{ asset('images/clock.svg') }}" alt="Clock Icon" class="clock-icon">
                                <p class="reservation-summary-date">予約{{ $reservation->shop->id }}</p>
                            </div>
                            <div class="form-group">
                                <label class="label_shop_name">Shop</label>
                                <div class="select-wrapper">
                                    <span class="summary-name">
                                        {{ $reservation->shop->shop_name }}
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date" class="label_date">Date</label>
                                <div class="select-wrapper">
                                    <input type="date" name="date" class="input_date" value="{{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="time" class="label_time">Time</label>
                                <div class="select-wrapper">
                                    <select id="time" name="time" class="select_time">
                                        @foreach ($reservation->times as $time)
                                            <option value="{{ $time }}"  {{ \Carbon\Carbon::parse($reservation->reservation_datetime)->format('H:i') == $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label_number">Number</label>
                                <div class="select-wrapper" >
                                    <select id="number" name="number" class="select_number">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ $reservation->number == $i ? 'selected' : '' }}>
                                                {{ $i }}人
                                            </option>
                                        @endfor
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>
                            </div>
                        </form>
                        <div class="reservation-button-container">
                            <button type="submit" class="edit-reservation-button" form="update-form-{{ $reservation->id }}">
                                変更
                            </button>
                            <button type="submit" class="delete-reservation-button" form="delete-form-{{ $reservation->id }}">
                                削除
                            </button>
                            @if($reservation->payment_status !== 'completed')
                                <a href="{{ route('payment.form', ['reservation_id' => $reservation->id]) }}" class="edit-reservation-button">
                                    支払う
                                </a>
                            @endif
                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"  id="delete-form-{{ $reservation->id }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                        <img src="{{ asset($reservation->qr_code) }}" alt="QR Code for Reservation {{ $reservation->id }}" class="qr_code_image">
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST" class="store_form">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{ $last_visited_shop_id }}">
                        <div class="rating-group">
                            <label for="rating" class="label_rating">
                                評価
                            </label>
                            <div class="select-wrapper">
                                <select name="rating" id="rating" class="select_rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <span class="rating-select-icon"></span>
                            </div>
                        </div>
                        <div class="rating-group">
                            <label for="comment" class="label_comment">コメント</label>
                            <textarea name="comment" id="comment" class="text_comment"></textarea>
                        </div>
                        <button type="submit" class="review-button">レビューを投稿</button>
                    </form>
                @endforeach
            </div>
        @endif
        <div class="favorite-shops-section">
            <h2 class="favorite-title">お気に入り店舗</h2>
            <div class="favorite-shops">
                @foreach ($favorites as $favorite)
                    <div class="shop_card">
                        <img src="{{ asset('storage/' . $favorite->image) }}" alt="{{ $favorite->shop_name }}" class="shop_card_image">
                        <div class="shop_info">
                            <h3 class="shop-name">
                                {{ $favorite->shop_name }}
                            </h3>
                            <p class="shop-guide">
                                @foreach ($favorite->areas as $area)
                                    ＃{{ $area->area_name }}
                                @endforeach
                                @foreach ($favorite->genres as $genre)
                                    ＃{{ $genre->genre_name }}
                                @endforeach
                            </p>
                            <div class="button-container">
                                @include('custom_components.shop-buttons', [
                                    'shop' => $favorite,
                                    'routeName' => 'shop.details',
                                    'showFavoriteForm' => true
                                ])
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
@endsection
