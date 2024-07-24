@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
    @if (session('success'))  <!-- 成功メッセージが存在する場合に表示 -->
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="shop_table">
        @foreach ($shops as $shop)
            <div class="shop_card">
                <img src="{{ asset($shop->image) }}" alt="{{ $shop->shop_name }}">
                <div class="shop_info">
                    <h3 class="shop-name">{{ $shop->shop_name }}</h3>
                    <p class="shop-guide">＃{{ $shop->area }}  ＃{{ $shop->genre }}</p>
                    <div class="button-container">
                        <a href="{{ route('shop.detail', ['id' => $shop->id]) }}" class="shop-detail">詳しくみる</a>
                        @auth
                            @if(auth()->user()->favorites->contains($shop))
                                <form action="{{ route('shops.unfavorite', $shop) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="favorite-button favorited">❤</button>
                                </form>
                            @else
                                <form action="{{ route('shops.favorite', $shop) }}" method="POST">
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
@endsection
