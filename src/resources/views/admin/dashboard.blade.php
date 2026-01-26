@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_css/admin_dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('admin_css/admin_modal_common.css') }}">
@endsection

@section('js')
    <script src="{{ asset('admin_js/admin_dashboard.js') }}"></script>
@endsection

@section('content')
    <div class="container admin_container">
        <p class="greeting-title">
            お疲れ様です！{{ Auth::user()->user_name }}さん
        </p>
        <div class="management_form shop_manager_form">
            <p class="content-section-title">店舗代表者登録</p>
            <p class="session-messages">
                @include('custom_components.session-messages', [
                    'showGeneral' => false,
                    'showAdmin' => true,
                    'showShopManager' => false,
                    'showShop' => false,
                ])
            </p>
            <form action="{{ route('admin.create.shop_manager') }}" method="POST" class="admin-form create-form">
                @csrf
                <div class="form-group">
                    <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                    <div class="select-wrapper">
                        <select id="shop_id" name="shop_id" class="date-entry select_shop_id">
                            <option value="">店舗を選択してください</option>
                            @foreach ($shops ?? [] as $shop)
                                <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->shop_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="custom-select-icon"></span>
                    </div>
                </div>
                <p class="form__error">
                    @error('shop_id')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/human.png') }}" alt="" class="icon-img">
                    <input type="text" id="user_name" name="user_name" placeholder="Username"
                        value="{{ old('user_name') }}" class="date-entry" autocomplete="username">
                </div>
                <p class="form__error">
                    @error('user_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/mail.png') }}" alt="" class="icon-img">
                    <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}"
                        class="date-entry" autocomplete="email">
                </div>
                <p class="form__error">
                    @error('email')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                    <input type="password" id="password" name="password" placeholder="Password"
                        value="{{ old('password') }}" class="date-entry" autocomplete="new-password">
                </div>
                <p class="form__error">
                    @error('password')
                        {{ $message }}
                    @enderror
                </p>
                <button class="button register-button" type="submit">
                    店舗代表者登録
                </button>
            </form>
        </div>
        <div class="management_form shop_registration_form">
            <p class="content-section-title">新規店舗登録</p>
            <p class="session-messages">
                @include('custom_components.session-messages', [
                    'showGeneral' => false,
                    'showShopManager' => false,
                    'showShop' => true,
                ])
            </p>
            <form action="{{ route('admin.create.shop') }}" method="POST" class="admin-form create-shop-form"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <img src="{{ asset('images/shop.png') }}" alt="" class="icon-img">
                    <input type="text" name="shop_name" placeholder="Shop Name" value="{{ old('shop_name') }}"
                        class="date-entry">
                </div>
                <p class="form__error">
                    @error('shop_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/description.png') }}" alt="" class="icon-img">
                    <textarea name="description" placeholder="Description" class="date-entry description_text">{{ old('description') }}</textarea>
                </div>
                <p class="form__error">
                    @error('description')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/genre.png') }}" alt="" class="icon-img">
                    <input type="text" name="genre_name" placeholder="Genre" value="{{ old('genre_name') }}"
                        class="date-entry">
                </div>
                <p class="form__error">
                    @error('genre_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/area.png') }}" alt="" class="icon-img">
                    <input type="text" name="area_name" placeholder="Area" value="{{ old('area_name') }}"
                        class="date-entry">
                </div>
                <p class="form__error">
                    @error('area_name')
                        {{ $message }}
                    @enderror
                </p>
                <div class="form-group">
                    <img src="{{ asset('images/img.png') }}" alt="" class="icon-img">
                    <input type="file" id="image" name="image" class="admin-input input_image">
                    <label for="image" class="custom-file-upload date-entry">
                        <i class="fa-cloud-upload">
                            <span id="file-name" class="file-name-display"></span>
                        </i>写真を選択
                    </label>
                </div>
                <p class="form__error">
                    @error('image')
                        {{ $message }}
                    @enderror
                </p>
                <label for="open_time" class="admin-time-label">
                    オープン
                </label>
                <div class="input-group-time">
                    <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                    <input type="time" id="open_time" name="open_time" class="date-entry time-input">
                </div>
                <p class="form__error">
                    @error('open_time')
                        {{ $message }}
                    @enderror
                </p>
                <label for="close_time" class="admin-time-label">
                    クローズ
                </label>
                <div class="input-group-time">
                    <img src="{{ asset('images/clock.svg') }}" alt="" class="icon-img">
                    <input type="time" id="close_time" name="close_time" class="date-entry time-input admin-input">
                </div>
                <p class="form__error">
                    @error('close_time')
                        {{ $message }}
                    @enderror
                </p>
                <button class="button new-register-button" type="submit">新店舗登録</button>
            </form>
        </div>
    </div>

    <!-- 店舗登録確認モーダル -->
    {{-- 開発用（スタイル調整時）：モーダルを常に表示する場合
        1. 下の@if (session('shop_success') && session('new_shop_id'))をコメントアウト
        2. @if(true)のコメントアウトを外す
        本番用：セッションがある時のみ表示（現在の状態） --}}
    @if (session('shop_success') && session('new_shop_id'))
        {{-- @if (true) style調整用if--}}
        <div id="shop-registration-modal" class="registration-modal modal show">
            <div class="modal-content">
                <span class="close-modal-button">&times;</span>
                <div class="registered-shop-detail">
                    <h3 class="card-title">登録した店舗詳細情報</h3>
                    @php
                        // 開発用: セッションがない場合は最新の店舗を取得（IDが最大のもの）
                        // 本番環境では、このelseブロックを削除して、session('new_shop_id')のみを使用すること
                        // 注意: モーダルのstyleを調整したいときは下のコメントアウトしている }else{ ブロックを->find(session('new_shop_id'));の下に入れる。
                        $newShop = null;
                        if (session('new_shop_id')) {
                            $newShop = \App\Models\Shop::with(['areas', 'genres'])->find(session('new_shop_id'));
                        }

                        //} else {
                        // 自分で指定したIDの店舗を取得
                        //$shopId = 1; // ここに取得したい店舗のIDを指定
                        //$newShop = \App\Models\Shop::with(['areas', 'genres'])->find($shopId);

                    @endphp
                    @if ($newShop)
                        <div class="registered-shop-card">
                            <img src="{{ asset('storage/' . $newShop->image) }}" alt="{{ $newShop->shop_name }}"
                                class="shop-image">
                            <div class="registered-shop-info">
                                <p class="shop-name">{{ $newShop->shop_name }}</p>
                                <p class="shop-tags">
                                    @foreach ($newShop->areas as $area)
                                        ＃{{ $area->area_name }}
                                    @endforeach
                                    @foreach ($newShop->genres as $genre)
                                        ＃{{ $genre->genre_name }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <div class="detail-shop-info registered-shop-detail-info">
                            <div class="detail-item">
                                <h4 class="detail-title">店舗名</h4>
                                <p class="modal-detail-section">{{ $newShop->shop_name }}</p>
                            </div>
                            <div class="detail-item shop-description-section">
                                <h4 class="detail-title description-title">店舗紹介</h4>
                                <p class="modal-detail-section shop-description-item">
                                    {{ $newShop->description }}</p>
                            </div>
                            <div class="detail-item">
                                <h4 class="detail-title">営業時間</h4>
                                <p class="modal-detail-section">{{ $newShop->open_time }} - {{ $newShop->close_time }}
                                </p>
                            </div>
                        </div>
                        <div class="view-shop-link-container">
                            <a href="{{ route('shops.index', ['from_admin' => 'true', 'shop_id' => $newShop->id]) }}"
                                class="view-shop-link link">
                                店舗一覧ページで確認する
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
