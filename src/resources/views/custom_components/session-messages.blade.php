@php
    $successClass = $successClass ?? 'alert-success';
    $errorClass = $errorClass ?? 'alert-danger';
    $successMessageClass = $successMessageClass ?? 'payment-success';
    $reservationSuccessClass = $reservationSuccessClass ?? 'reservation-success';

    // 表示するメッセージタイプを制御
    $showGeneral = $showGeneral ?? true;
    $showShopManager = $showShopManager ?? false;
    $showShop = $showShop ?? false;
    $showReservation = $showReservation ?? false;
    $showFavorite = $showFavorite ?? false;
@endphp

@if ($showGeneral)
    {{-- 一般的なセッションメッセージ（shops/index.blade.php, shops/detail.blade.php, payment/form.blade.php） --}}
    @if (session('success') && !$showReservation && !$showFavorite)
        @php
            $successClass = 'alert-success';
        @endphp
        <span class="{{ $successClass }}">
            {{ session('success') }}
        </span>
    @endif

    @if (session('error') && !$showReservation && !$showFavorite)
        @php
            $errorClass = 'alert-danger';
        @endphp
        <span class="{{ $errorClass }}">
            {{ session('error') }}
        </span>
    @endif

    @if (session('success_message'))
        <span class="{{ $successMessageClass }}">
            {{ session('success_message') }}
        </span>
    @endif

    @if (session('review_success'))
        <span class="{{ $successClass }}">
            {{ session('review_success') }}
        </span>
    @endif
@endif

@if ($showShopManager)
    {{-- 店舗管理者用のセッションメッセージ（shop_manager/dashboard.blade.php, shop_manager/manage-shop.blade.php） --}}
    @if (session('shop_manager_success'))
        <span class="{{ $successClass }}">
            {{ session('shop_manager_success') }}
        </span>
    @endif

    @if (session('shop_manager_error'))
        <span class="{{ $errorClass }}">
            {{ session('shop_manager_error') }}
        </span>
    @endif
@endif

@if ($showShop)
    {{-- 店舗用のセッションメッセージ（shops/detail.blade.php） --}}
    @if (session('shop_success'))
        <span class="{{ $successClass }}">
            {{ session('shop_success') }}
        </span>
    @endif

    @if (session('shop_error'))
        <span class="{{ $errorClass }}">
            {{ session('shop_error') }}
        </span>
    @endif
@endif

@if ($showReservation)
    {{-- 予約用のセッションメッセージ（shops/detail.blade.php） --}}
    @if (session('reservation_success'))
        <span class="{{ $reservationSuccessClass }}">
            {{ session('reservation_success') }}
        </span>
    @endif
@endif

@if ($showFavorite)
    {{-- お気に入り店舗用のセッションメッセージ --}}
    @if (session('favorite_success'))
        <span class="{{ $successClass }}">
            {{ session('favorite_success') }}
        </span>
    @endif

    @if (session('favorite_error'))
        <span class="{{ $errorClass }}">
            {{ session('favorite_error') }}
        </span>
    @endif
@endif
