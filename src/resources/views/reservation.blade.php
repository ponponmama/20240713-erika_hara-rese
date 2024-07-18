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
                            <p>{{ $shop->description }}</p>
                        </div>
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
        </div>
    </main> 
</body>
</html>