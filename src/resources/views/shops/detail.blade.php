@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('detail_shop')
    <div class="navigation">
        <a href="{{ route('shops.index') }}" class="back-link">＜</a>
        <h2 class="navigation_shop_name">
            {{ $shop->shop_name }}
        </h2>
    </div>
    <div class="image_section">
        <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}" class="shop_image">
        <p class="shop-guide">
            @foreach ($shop->areas as $area)
                ＃{{ $area->area_name }}
            @endforeach
            @foreach ($shop->genres as $genre)
                ＃{{ $genre->genre_name }}
            @endforeach
        </p>
        <p class="description">
            {{ $shop->description }}
        </p>
    </div>
@endsection
@section('reservation_form')
    <div class="form-section">
        <h2 class="reserve">
            予約
        </h2>
        <form action="{{ route('reservations.store') }}" method="post" id="reserve-form" class="reserve_form">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <label for="date" class="label_date"></label>
            <input type="date" id="date" name="date" class="input_date">
            @error('date')
                <div class="form__error">{{ $message }}</div>
            @enderror
            <label for="time" class="label_time"></label>
            <div class="select-wrapper">
                <select id="time" name="time" class="select_time">
                    <option value="">選択してください</option>
                    @foreach ($times ?? [] as $time)
                        <option value="{{ $time }}"  {{ old('time') == $time ? 'selected' : '' }}>
                            {{ $time }}
                        </option>
                    @endforeach
                </select>
                <span class="custom-select-icon"></span>
            </div>
            @error('time')
                <div class="form__error">{{ $message }}</div>
            @enderror
            <label for="number" class="label_number"></label>
            <div class="select-wrapper">
                <select id="number" name="number" class="select_number">
                    <option value="">選択してください</option>
                    @for ($i = 1; $i <= 20; $i++)
                        <option value="{{ $i }}" {{ old('number') == $i ? 'selected' : '' }}>
                            {{ $i }}人
                        </option>
                    @endfor
                </select>
                <span class="custom-select-icon"></span>
            </div>
            @error('number')
                <div class="form__error">{{ $message }}</div>
            @enderror
        </form>
        <div class="reservation-summary">
            @if(session('reservation_details'))
                <div class="summary-item">
                    <label class="label_shop">Shop:</label>
                    <span class="summary-date">
                        {{ session('reservation_details')->shop->shop_name }}
                    </span>
                </div>
                <div class="summary-item">
                    <label class="label_date_session">Date:</label>
                    <span class="summary-date">
                        {{ \Carbon\Carbon::parse(session('reservation_details')->reservation_datetime)->format('Y-m-d') }}
                    </span>
                </div>
                <div class="summary-item">
                    <label class="label_time_session">Time:</label>
                    <span class="summary-date">
                        {{ \Carbon\Carbon::parse(session('reservation_details')->reservation_datetime)->format('H:i') }}
                    </span>
                </div>
                <div class="summary-item">
                    <label class="label_number_session">Number:</label>
                    <span class="summary-date">
                        {{ session('reservation_details')->number . '人' }}
                    </span>
                </div>
            @endif
            <div class="qr-code">
                <h2 class="qr-text">来店時にこのQRコードを提示してください</h2>
                    @if(session('reservation_details'))
                        <img src="{{ asset(session('reservation_details')->qr_code) }}" alt="QR Code" class="qr_code_img">
                    @endif
            </div>
        </div>
        <div class="button-container">
            <button type="submit" form="reserve-form" class="reserve-button">
                予約する
            </button>
        </div>
    </div>
@endsection
