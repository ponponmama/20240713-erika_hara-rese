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
@endphp

@if ($showGeneral)
    {{-- 一般的なセッションメッセージ（shops/index.blade.php, shops/detail.blade.php, payment/form.blade.php） --}}
    @if (session('success'))
        <div class="{{ $successClass }}">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="{{ $errorClass }}">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success_message'))
        <div class="{{ $successMessageClass }}">
            {{ session('success_message') }}
        </div>
    @endif
@endif

@if ($showShopManager)
    {{-- 店舗管理者用のセッションメッセージ（shop_manager/dashboard.blade.php, shop_manager/manage-shop.blade.php） --}}
    @if (session('shop_manager_success'))
        <div class="{{ $successClass }}">
            {{ session('shop_manager_success') }}
        </div>
    @endif

    @if (session('shop_manager_error'))
        <div class="{{ $errorClass }}">
            {{ session('shop_manager_error') }}
        </div>
    @endif
@endif

@if ($showShop)
    {{-- 店舗用のセッションメッセージ（shops/detail.blade.php） --}}
    @if (session('shop_success'))
        <div class="{{ $successClass }}">
            {{ session('shop_success') }}
        </div>
    @endif

    @if (session('shop_error'))
        <div class="{{ $errorClass }}">
            {{ session('shop_error') }}
        </div>
    @endif
@endif

@if ($showReservation)
    {{-- 予約用のセッションメッセージ（shops/detail.blade.php） --}}
    @if (session('reservation_success'))
        <div class="{{ $reservationSuccessClass }}">
            {{ session('reservation_success') }}
        </div>
    @endif
@endif
