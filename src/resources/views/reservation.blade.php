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
                            <form action="/reserve" method="post">
                                @csrf
                                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                <label for="date" placeholder="日付"></label>
                                <input type="date" id="date" name="date">
                                <label for="time"></label>
                                <input type="time" id="time" name="time" placeholder="予約時刻">
                                <label for="number"></label>
                                <input type="number" id="number" name="number" min="1" placeholder="人数">
                            </form>
                            <div class="reservation-summary">
                                <div class="summary-item">
                                    <label>Shop</label>
                                    <div>{{ $shop->shop_name }}</div>
                                </div>
                                <div class="summary-item">
                                    <label>Date</label>
                                    <div>{{ $date }}</div>
                                </div>
                                <div class="summary-item">
                                    <label>Time</label>
                                    <div>{{ $time }}</div>
                                </div>
                                <div class="summary-item">
                                    <label>Number</label>
                                    <div>{{ $number }}</div>
                                </div>
                            </div>
                            <button type="submit" form="reserve-form" class="reserve-button">予約する</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main> 
</body>
</html>