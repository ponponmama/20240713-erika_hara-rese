<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shop_list</title>
    <link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
</head>
<body>
    <div class="shop_table">
        @foreach ($shops as $shop)
        <div class="shop_card">
            <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}">
            <div class="shop_info">
                <h3>{{ $shop->shop_name }}</h3>
                <p>{{ $shop->area }} | {{ $shop->genre }}</p>
                <a href="{{ route('shop.detail', ['id' => $shop->id]) }}">詳しく見る</a>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>