@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('shop_css/shop_dashboard.css') }}">
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <script src="{{ asset('shop_css/shop_js/shop_dashboard.js') }}"></script>
@endsection

@section('content')
    <div class="container shop_container">
        @include('custom_components.header', [
            'title' => Auth::user()->shop->shop_name,
            'additionalClass' => 'shop_manager_name',
        ])
        <p class="session-messages">
            @include('custom_components.session-messages')
        </p>
        <div class="reservations">
            <h2 class="reservations-list">予約情報</h2>
            <table class="shop_reservation">
                <thead>
                    <tr>
                        <th class="reservation_th">予約日</th>
                        <th class="reservation_th">時間</th>
                        <th class="reservation_th">人数</th>
                        <th class="reservation_th">予約ID</th>
                        <th class="reservation_th">顧客名</th>
                        <th class="reservation_th">メールアドレス</th>
                        <th class="reservation_th">詳細</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="reservation_tr">
                            <td class="reservation_td">{{ $reservation->reservation_datetime->format('Y-m-d') }}</td>
                            <td class="reservation_td">{{ $reservation->reservation_datetime->format('H:i') }}</td>
                            <td class="reservation_td">{{ $reservation->number }}</td>
                            <td class="reservation_td">{{ $reservation->id }}</td>
                            <td class="reservation_td">{{ $reservation->user->user_name }}</td>
                            <td class="reservation_td email-column">{{ $reservation->user->email }}</td>
                            <td class="reservation_td">
                                <button class="button detail-button reservation-detail-button"
                                    data-reservation-id="{{ $reservation->id }}">詳細</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="custom-count-pagination">
                {{ $reservations->links() }}
            </div>
            <!-- 予約詳細モーダル -->
            <div id="reservationModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>予約詳細</h3>
                    <div class="reservation-details-container">
                        <div class="detail-row">
                            <span class="detail-label">予約日:</span>
                            <span class="detail-value" id="modal-reservation-datetime"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">時間:</span>
                            <span class="detail-value" id="modal-reservation-time"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">人数:</span>
                            <span class="detail-value" id="modal-reservation-number"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">予約ID:</span>
                            <span class="detail-value" id="modal-reservation-id"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">顧客名:</span>
                            <span class="detail-value" id="modal-reservation-user-name"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">メールアドレス:</span>
                            <span class="detail-value" id="modal-reservation-email"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">支払い状態:</span>
                            <span class="detail-value" id="modal-reservation-payment-status"></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">合計金額:</span>
                            <span class="detail-value" id="modal-reservation-total-amount"></span>
                        </div>
                        <div class="detail-row form-row">
                            <form id="price-update-form" action="" method="POST" class="price-form">
                                @csrf
                                @method('PUT')
                                <div class="price-container">
                                    <span class="detail-label">金額設定:</span>
                                    <input type="number" name="total_amount" id="modal-reservation-total-amount-input"
                                        min="0" class="detail-value price-input">円
                                </div>
                                <div class="price-button-container">
                                    <button type="submit" class="button price-update-btn"
                                        id="price-update-button-confirm">金額確定</button>
                                    <button type="submit" class="button price-update-btn" id="price-update-button-retry"
                                        style="display: none;">再設定</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h2 id="qr-data-display" class="qr-data-display">
            QRコード照会内容
        </h2>
        <div class="reservation-details">
            <p class="qr_data_content"><strong>予約日:</strong> <span id="reservation-date"></span></p>
            <p class="qr_data_content"><strong>時間:</strong> <span id="reservation-time"></span></p>
            <p class="qr_data_content"><strong>人数:</strong> <span id="reservation-number"></span></p>
            <p class="qr_data_content"><strong>予約ID:</strong> <span id="reservation-id"></span></p>
            <p class="qr_data_content"><strong>顧客名:</strong> <span id="reservation-user-name"></span></p>
            <p class="qr_data_content"><strong>メールアドレス:</strong> <span id="reservation-email"></span></p>
        </div>
        <div class="qr-section-button">
            <button id="start-scanner-btn" class="button scanner-btn">スキャン</button>
            <button id="stop-scanner-btn" class="button scanner-btn">停止</button>
            <button id="reset-btn" class="button scanner-btn">リセット</button>
        </div>
    </div>
    <div class="camera-reader" id="reader" style="display: none; width: 0; height: 0; overflow: hidden;">
        <video id="video-preview" style="display: none;"></video>
        <canvas id="canvas-preview"></canvas>
    </div>
@endsection
