<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
    <title>{{ $shop->shop_name }}</title>
</head>
    <body>
        <div class="container">
            <header>
                <h1>Rese</h1>
            </header>
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
    </body>
</html>