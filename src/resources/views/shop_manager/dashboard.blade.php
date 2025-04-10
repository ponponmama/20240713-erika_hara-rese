@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin_shop_css/shop.css') }}">
@endsection

@section('content')
<div class="container shop_container">
    <h1 class="form-title">{{ Auth::user()->shop->shop_name }}</h1>
    <p class="user__name shop_manager_name">
        お疲れ様です！{{ Auth::user()->user_name }}さん
    </p>
    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert-danger">
            {{ session('error') }}
        </div>
    @endif
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
                    <th class="reservation_th">予約詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>
                    <td class="reservation_td">{{ $reservation->reservation_datetime->format('Y-m-d') }}</td>
                    <td class="reservation_td">{{ $reservation->reservation_datetime->format('H:i') }}</td>
                    <td class="reservation_td">{{ $reservation->number }}</td>
                    <td class="reservation_td">{{ $reservation->id }}</td>
                    <td class="reservation_td">{{ $reservation->user->user_name }}</td>
                    <td class="reservation_td">{{ $reservation->user->email}}</td>
                    <td class="reservation_td">
                        <button onclick="openReservationModal({{ $reservation->id }})" class="detail-button">詳細</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 予約詳細モーダル -->
        <div id="reservationModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>予約詳細</h2>
                <div id="reservationDetails">
                    <!-- ここに予約詳細が動的に表示されます -->
                </div>
            </div>
        </div>

        <script>
            // モーダルを開く関数
            function openReservationModal(reservationId) {
                const modal = document.getElementById('reservationModal');
                const detailsContainer = document.getElementById('reservationDetails');

                // 予約詳細を取得して表示
                fetch(`/shop-manager/reservations/${reservationId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        detailsContainer.innerHTML = `
                            <div class="detail-row">
                                <span class="detail-label">予約日:</span>
                                <span>${data.reservation_datetime}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">時間:</span>
                                <span>${data.time}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">人数:</span>
                                <span>${data.number}人</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">予約ID:</span>
                                <span>${data.id}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">顧客名:</span>
                                <span>${data.user_name}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">メールアドレス:</span>
                                <span>${data.email}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">支払い状態:</span>
                                <span>${data.payment_status}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">合計金額:</span>
                                <span>${data.total_amount}円</span>
                            </div>
                        `;
                        modal.classList.add('modal-show');
                    });
            }

            // モーダルを閉じる
            document.querySelector('.close').onclick = function() {
                document.getElementById('reservationModal').classList.remove('modal-show');
            }

            // モーダルの外をクリックしても閉じる
            window.onclick = function(event) {
                const modal = document.getElementById('reservationModal');
                if (event.target == modal) {
                    modal.classList.remove('modal-show');
                }
            }
        </script>
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
        <button id="start-scanner-btn" class="start-scanner-btn">スキャン</button>
        <button id="stop-scanner-btn" class="stop-scanner-btn">停止</button>
        <button id="reset-btn" class="reset-btn">リセット</button>
    </div>
</div>
<div class="camera-reader" id="reader">
    <video id="video-preview" style="display: none;"></video>
    <canvas id="canvas-preview"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const videoElement = document.getElementById('video-preview');
    const canvasElement = document.getElementById('canvas-preview');
    const canvas = canvasElement.getContext('2d');
    const qrDataDisplay = document.getElementById('qr-data-display');
    let stream = null;

    // キャンバスのサイズを動的に調整する関数
    function resizeCanvas() {
        canvasElement.width = window.innerWidth * 0.8;
        canvasElement.height = window.innerHeight * 0.8;
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    const startButton = document.getElementById('start-scanner-btn');
    const stopButton = document.getElementById('stop-scanner-btn');
    const resetButton = document.getElementById('reset-btn');

    startButton.addEventListener('click', function() {
        if (!stream) {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment",
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                }
            })
                .then(function(s) {
                    stream = s;
                    videoElement.srcObject = stream;
                    videoElement.play();
                    scanQRCode();
                    stopButton.style.display = 'block';
                    startButton.style.display = 'none';
                }).catch(function(error) {
                    console.error('Error accessing the camera: ', error);
                    alert('カメラへのアクセスに失敗しました。カメラの権限を確認してください。');
                });
        }
    });

    stopButton.addEventListener('click', function() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            videoElement.srcObject = null;
            stopButton.style.display = 'none';
            startButton.style.display = 'block';
        }
    });

    // 表示をクリアする関数
    function clearDisplay() {
        document.getElementById('reservation-date').textContent = '';
        document.getElementById('reservation-time').textContent = '';
        document.getElementById('reservation-number').textContent = '';
        document.getElementById('reservation-id').textContent = '';
        document.getElementById('reservation-user-name').textContent = '';
        document.getElementById('reservation-email').textContent = '';
        qrDataDisplay.textContent = 'QRコード照会内容'; // QRコード表示部分もクリア
    }

    resetButton.addEventListener('click', function() {
        localStorage.removeItem('reservationData'); // ローカルストレージからデータを削除
        clearDisplay(); // 表示をクリア
        location.reload(); // ページをリロード
    });

    function scanQRCode() {
        if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
            canvasElement.height = videoElement.videoHeight;
            canvasElement.width = videoElement.videoWidth;
            canvas.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

            // QRコードの読み取り精度を向上させるための処理
            var code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "attemptBoth"
            });

            if (code) {
                // 赤枠を描画
                drawBox(code.location);

                // QRコードの内容を表示
                qrDataDisplay.textContent = 'QRコードの内容: ' + code.data;

                // 予約詳細を取得
                fetchReservationDetails(code.data);

                // スキャンを停止
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                    stream = null;
                    videoElement.srcObject = null;
                    stopButton.style.display = 'none';
                    startButton.style.display = 'block';
                }
            }
        }
        requestAnimationFrame(scanQRCode);
    }

    function drawBox(location) {
        // キャンバスをクリア
        canvas.clearRect(0, 0, canvasElement.width, canvasElement.height);

        // 赤枠を描画
        canvas.beginPath();
        canvas.moveTo(location.topLeftCorner.x, location.topLeftCorner.y);
        canvas.lineTo(location.topRightCorner.x, location.topRightCorner.y);
        canvas.lineTo(location.bottomRightCorner.x, location.bottomRightCorner.y);
        canvas.lineTo(location.bottomLeftCorner.x, location.bottomLeftCorner.y);
        canvas.lineTo(location.topLeftCorner.x, location.topLeftCorner.y);
        canvas.strokeStyle = "#FF3B58";
        canvas.lineWidth = 4;
        canvas.stroke();
    }

    // 予約詳細を取得する関数
    function fetchReservationDetails(qrData) {
        const match = qrData.match(/(\d+)$/);
        if (match) {
            const reservationId = match[1];
            fetch(`/api/reservation/${reservationId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        const reservationDate = new Date(data.reservation_datetime);
                        document.getElementById('reservation-date').textContent = reservationDate.toLocaleDateString('ja-JP');
                        document.getElementById('reservation-time').textContent = reservationDate.toLocaleTimeString('ja-JP', { hour: '2-digit', minute: '2-digit', hour12: false });
                        document.getElementById('reservation-number').textContent = data.number;
                        document.getElementById('reservation-id').textContent = data.id;
                        document.getElementById('reservation-user-name').textContent = data.user_name;
                        document.getElementById('reservation-email').textContent = data.email;
                    } else {
                        console.error('No data received');
                    }
                })
                .catch(error => {
                    console.error('Error fetching reservation details:', error);
                    alert('予約詳細の取得に失敗しました。');
                });
        } else {
            console.error('Invalid QR data format:', qrData);
            alert('QRコードの形式が正しくありません。');
        }
    }
});
</script>
@endsection

