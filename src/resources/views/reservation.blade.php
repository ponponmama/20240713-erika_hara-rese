<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
</head>

<body>
    <main>
        <div class="container">
            <div class="content">
                <div class="flex-container">
                    <div class="left-side">
                        <div class="header">
                            <div class="flex-low">
                                @include('partials.navbar')
                                <h1 class="top_logo">Rese</h1>
                            </div>
                        </div>
                        <div class="navigation">
                            <a href="{{ route('index') }}" class="back-link">＜</a>
                            <h2 class="s-name">{{ $shop->shop_name }}</h2>
                        </div>
                        <div class="image-section">
                            <img src="{{ asset($shop->image) }}" alt="{{ $shop->shop_name }}">
                            <p class="shop-guide">＃{{ $shop->area }}  ＃{{ $shop->genre }}</p>
                            <p class="description">{{ $shop->description }}</p>
                        </div>
                    </div>
                    <div class="reservation">
                        <div class="form-section">
                            <h2 class="reserve">予約</h2>
                            <form action="{{ route('reservations.store') }}" method="post" id="reserve-form">
                                @csrf
                                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                <label for="date" placeholder="日付"></label>
                                <input type="date" id="date" name="date" class="date-label"  value="value="{{ session('form_data.date', date('Y-m-d')) }}">
                                <label for="time"></label>
                                <div class="select-wrapper" style="position: relative;">
                                    <select id="time" name="time">
                                        @foreach ($times ?? [] as $time)
                                            <option value="{{ $time }}" {{ session('form_data.time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                        @endforeach
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>
                                <label for="number"></label>
                                <div class="select-wrapper" style="position: relative;">
                                    <select id="number" name="number">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <option value="{{ $i }}" {{ session('reservation.number') == $i ? 'selected' : '' }} >{{ $i }}人</option>
                                        @endfor
                                    </select>
                                    <span class="custom-select-icon"></span>
                                </div>    
                            </form>
                            <form action="{{ route('reservations.preview') }}" method="post" id="preview-form">
                                @csrf
                                <div class="reservation-summary">
                                    @if(session('reservation'))
                                        <div class="summary-item">
                                            <label>Shop</label>
                                            <span class="summary-date">{{ session('reservation')['shop_name'] }}</span>
                                        </div>                                 <div class="summary-item">
                                            <label>Date</label>
                                            <span class="summary-date">{{ \Carbon\Carbon::parse(session('reservation')['date'])->format('Y-m-d') }}</span>
                                        </div>
                                        <div class="summary-item">
                                            <label>Time</label>
                                            <span class="summary-date">{{ \Carbon\Carbon::parse (session('reservation')['time'])->format('H:i') }}</span>
                                        </div>
                                        <div class="summary-item">
                                            <label>Number</label>
                                            <span class="summary-date">{{ session('reservation')['number'] }}人</span>
                                        </div>
                                    @endif
                                </div>
                            <div class="button-container">
                                <button type="submit" form="reserve-form" class="reserve-button">予約する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> 
</body>
</html>