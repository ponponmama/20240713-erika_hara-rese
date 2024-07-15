@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_list.css') }}">
@endsection

@section('content')
    <div class="shop_table">
        @foreach ($shops as $shop)
        <div class="shop_card">
            <img src="{{ asset($shop->image) }}" alt="{{ $shop->shop_name }}">
            <div class="shop_info">
                <h3>{{ $shop->shop_name }}</h3>
                <p>{{ $shop->area }} | {{ $shop->genre }}</p>
                <a href="{{ route('shop.detail', ['id' => $shop->id]) }}">詳しく見る</a>
            </div>
        </div>
        @endforeach
    </div>
@endsection
