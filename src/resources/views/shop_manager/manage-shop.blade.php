@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('shop_css/manage-shop.css') }}">
    <link rel="stylesheet" href="{{ asset('shop_css/shops_modal_common.css') }}">
@endsection

@section('js')
    <script src="{{ asset('shop_css/shop_js/manage-shop.js') }}"></script>
@endsection

@section('content')
    <div class="container manage_shop_container">
        <p class="content-section-title">店舗情報</p>
        <p class="session-messages">
            @include('custom_components.session-messages')
        </p>
        <form action="{{ route('shop_manager.update', $shop->id) }}" method="POST" enctype="multipart/form-data"
            class="manage_form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <span class="label-title">
                    <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                    店舗名
                </span>
                <span id="shop_name" class="data-entry shop-name-entry" name="shop_name">{{ $shop->shop_name }}</span>
            </div>
            <div class="form-group">
                <label for="description" class="label-title description-title">
                    <img src="{{ asset('images/description.png') }}" alt="" class="icon-img">
                    店舗紹介
                </label>
                <textarea id="description" name="description" class="data-entry description_text">{{ $shop->description }}</textarea>
            </div>
            <p class="form__error">
                @error('description')
                    {{ $message }}
                @enderror
            </p>
            <div class="form-group">
                <span class="label-title business_hours"><img src="{{ asset('images/clock.svg') }}" alt=""
                        class="icon-img">営業時間</span>
            </div>
            <div class="form-group">
                <label for="open_time" class="label-title">
                    オープン
                </label>
                <input type="time" id="open_time" name="open_time"
                    value="{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }}" class="data-entry input_time">
            </div>
            <p class="form__error">
                @error('open_time')
                    {{ $message }}
                @enderror
            </p>
            <div class="form-group">
                <label for="close_time" class="label-title">
                    クローズ
                </label>
                <input type="time" id="close_time" name="close_time"
                    value="{{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}" class="data-entry input_time">
            </div>
            <p class="form__error">
                @error('close_time')
                    {{ $message }}
                @enderror
            </p>
            <div class="form-group">
                <label for="image" class="label-title">
                    <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
                    写真
                </label>
                <input type="file" id="image" name="image" class="input_image" accept="image/*">
                <label for="image" class="data-entry custom-file-upload">
                    <i class="fa-cloud-upload">写真を選択</i>
                </label>
            </div>
            <p class="form__error">
                @error('image')
                    {{ $message }}
                @enderror
            </p>
            <span id="file-name" class="file-name"></span>
            <span class="preview-image-container"></span>
            <div class="up_date_button_container">
                <button type="submit" class="button up_date_button">更新する</button>
            </div>
        </form>
        <p class="confirm-text">確認はこちらから</p>
        <div class="confirm-button-container" id="confirm-button-container">
            <button type="button" class="button confirm-button" id="confirm-button">更新を確認</button>
            <a href="{{ route('shops.index', ['from_admin' => 'true', 'shop_id' => $shop->id]) }}"
                class="view-shop-link link">
                店舗一覧ページで確認する
            </a>
        </div>
    </div>

    <!-- 更新確認モーダル -->
    <div id="shop-confirm-modal" class="details-modal modal">
        <div class="details-modal-content modal-content">
            <span class="close-modal-button">&times;</span>
            <h3 class="card-title">登録店舗情報</h3>
            <div id="modal-shop-image-container" class="detail-shop-cards">
                <img id="modal-shop-image" src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->shop_name }}"
                    class="shop-image">
                <div class="details-shop-info">
                    <div class="detail-item">
                        <h4 class="detail-title">店舗名</h4>
                        <p class="modal-detail-section" id="modal-shop-name">{{ $shop->shop_name }}</p>
                    </div>
                    <div class="detail-item shop-description-item">
                        <h4 class="detail-title description-title">店舗紹介</h4>
                        <p class="modal-detail-section shop-description-item" id="modal-shop-description">
                            {{ $shop->description }}</p>
                    </div>
                    <div class="detail-item">
                        <h4 class="detail-title shop-tags-title">エリア</h4>
                        <p class="modal-detail-section" id="modal-shop-area">
                            @foreach ($shop->areas as $area)
                                {{ $area->area_name }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                    </div>
                    <div class="detail-item">
                        <h4 class="detail-title shop-tags-title">ジャンル</h4>
                        <p class="modal-detail-section" id="modal-shop-genre">
                            @foreach ($shop->genres as $genre)
                                {{ $genre->genre_name }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </p>
                    </div>
                    <div class="detail-item">
                        <h4 class="detail-title shop-hours-title">営業時間</h4>
                        <p class="modal-detail-section" id="modal-shop-hours">
                            {{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
