@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="content">
        <h1 class="top_logo">Rese</h1>
        <div class="shop_table">
            @foreach ($shops as $shop)
            <div class="shop_card">
                <img src="{{ asset($shop->image) }}" alt="{{ $shop->shop_name }}">
                <div class="shop_info">
                    <h3 class="shop-name">{{ $shop->shop_name }}</h3>
                    <p class="shop-guide">＃{{ $shop->area }}  ＃{{ $shop->genre }}</p>
                    <div class="button-container">
                        <a href="{{ route('shop.detail', ['id' => $shop->id]) }}" class="shop-detail">詳しくみる</a>
                        <form action="{{ route('favorite.add', ['id' => $shop->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="favorite-button">❤</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
