@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('search')
<div class="search-content">
    <form class="search-form" action="{{ route('shops.index') }}" method="get">
        <div class="search-form__section">
            <div class="select-wrapper">
                <select class="search-form__item-select" name="search-area" title="エリアで絞り込み">
                    <option value="">All area</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                    @endforeach
                </select>
                <span class="custom-select-icon"></span>
            </div>
            <div class="select-wrapper">
                <select class="search-form__item-select" name="search-genre" title="ジャンルで絞り込み">
                    <option value="">All genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                    @endforeach
                </select>
                <span class="custom-select-icon"></span>
            </div>
            <button type="submit" class="search-button" title="店舗を検索">
                <img src="{{ asset('images/search.png') }}" alt="Search" class="search_img">
            </button>
            <input class="search-form__item-input" type="text" name="search-shop__name" placeholder="{{ __('Search...') }}">
        </div>
    </form>
</div>
@endsection
@section('content')
    @include('custom_components.session-messages')
    <div class="shop_table">
        @foreach ($shops as $shop)
            <div class="shop_card">
                <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}">
                <div class="shop_info">
                    <h3 class="shop-name">{{ $shop->shop_name }}</h3>
                    <p class="shop-guide">
                        @foreach ($shop->areas as $area)
                            ＃{{ $area->area_name }}
                        @endforeach
                        @foreach ($shop->genres as $genre)
                            ＃{{ $genre->genre_name }}
                        @endforeach
                    </p>
                    @include('custom_components.shop-buttons', ['shop' => $shop])
                </div>
            </div>
        @endforeach
    </div>
@endsection
