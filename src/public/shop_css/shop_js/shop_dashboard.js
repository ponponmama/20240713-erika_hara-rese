// 予約詳細モーダル関連
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.reservation_detail_button');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const reservationId = this.getAttribute('data-reservation-id');
            openReservationModal(reservationId);
        });
    });
});

function openReservationModal(reservationId) {
    const modal = document.getElementById('reservationModal');
    const priceForm = document.getElementById('price-update-form');

    // 予約詳細を取得して表示
    fetch(`/shop-manager/reservations/${reservationId}/details`)
        .then(response => response.json())
        .then(data => {
            // 各要素にデータを設定
            document.getElementById('modal-reservation-datetime').textContent = data.reservation_datetime;
            document.getElementById('modal-reservation-time').textContent = data.time;
            document.getElementById('modal-reservation-number').textContent = `${data.number}人`;
            document.getElementById('modal-reservation-id').textContent = data.id;
            document.getElementById('modal-reservation-user-name').textContent = data.user_name;
            document.getElementById('modal-reservation-email').textContent = data.email;
            document.getElementById('modal-reservation-total-amount').textContent = `${data.total_amount}円`;

            // 支払い状態を設定（クラスも設定）
            const paymentStatusElement = document.getElementById('modal-reservation-payment-status');
            paymentStatusElement.textContent = data.payment_status;
            paymentStatusElement.className = 'detail-value';
            if (data.payment_status === '決済完了') {
                paymentStatusElement.classList.add('status-completed');
            } else if (data.payment_status === '金額設定済み（支払い待ち）') {
                paymentStatusElement.classList.add('status-amount-set');
            } else {
                paymentStatusElement.classList.add('status-pending');
            }

            // 支払い状態に応じて金額設定フォームの表示/非表示を制御
            const priceFormRow = document.querySelector('.form-row');
            const confirmButton = document.getElementById('price-update-button-confirm');
            const retryButton = document.getElementById('price-update-button-retry');

            if (data.payment_status_code === 'completed' || data.payment_status_code === 'amount_set') {
                // 決済完了または金額設定済み（支払い待ち）の場合は金額設定フォームを非表示
                if (priceFormRow) {
                    priceFormRow.classList.add('hide');
                }
            } else {
                // その他の場合は金額設定フォームを表示
                if (priceFormRow) {
                    priceFormRow.classList.remove('hide');
                }
                // 金額入力フィールドとフォームのアクションを設定
                document.getElementById('modal-reservation-total-amount-input').value = data.total_amount;
                priceForm.action = `/shop-manager/reservations/${data.id}/update-price`;

                // 決済失敗の場合は「再設定」ボタンを表示、それ以外は「金額確定」ボタンを表示
                if (data.payment_status_code === 'failed') {
                    if (confirmButton) {
                        confirmButton.classList.add('hide');
                    }
                    if (retryButton) {
                        retryButton.classList.remove('hide');
                    }
                } else {
                    if (confirmButton) {
                        confirmButton.classList.remove('hide');
                    }
                    if (retryButton) {
                        retryButton.classList.add('hide');
                    }
                }
            }

            modal.classList.remove('hide');
            modal.classList.add('show');
        });
}

// モーダルを閉じる
document.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.querySelector('.close');
    if (closeButton) {
        closeButton.onclick = function () {
            const modal = document.getElementById('reservationModal');
            modal.classList.add('hide');
            modal.classList.remove('show');
        }
    }

    // モーダルの外をクリックしても閉じる
    window.onclick = function (event) {
        const modal = document.getElementById('reservationModal');
        if (event.target == modal) {
            modal.classList.add('hide');
            modal.classList.remove('show');
        }
    }
});

// QRコードスキャン関連
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

    if (startButton) {
        startButton.addEventListener('click', function () {
            if (!stream) {
                const reader = document.querySelector('.camera-reader');
                reader.classList.remove('hide');
                reader.classList.add('show');

                navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment",
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                })
                    .then(function (s) {
                        stream = s;
                        videoElement.srcObject = stream;
                        videoElement.play();
                        scanQRCode();
                        stopButton.classList.remove('hide');
                        startButton.classList.add('hide');
                    }).catch(function (error) {
                        console.error('Error accessing the camera: ', error);
                        alert('カメラへのアクセスに失敗しました。カメラの権限を確認してください。');
                    });
            }
        });
    }

    if (stopButton) {
        stopButton.addEventListener('click', function () {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
                videoElement.srcObject = null;
                stopButton.classList.add('hide');
                startButton.classList.remove('hide');

                const reader = document.querySelector('.camera-reader');
                reader.classList.add('hide');
                reader.classList.remove('show');
            }
        });
    }

    // 表示をクリアする関数
    function clearDisplay() {
        document.getElementById('reservation-date').textContent = '';
        document.getElementById('reservation-time').textContent = '';
        document.getElementById('reservation-number').textContent = '';
        document.getElementById('reservation-id').textContent = '';
        document.getElementById('reservation-user-name').textContent = '';
        document.getElementById('reservation-email').textContent = '';
        qrDataDisplay.textContent = 'QRコード照会内容'; // QRコード表示部分もクリア

        // 予約詳細セクションを非表示
        const reservationDetails = document.querySelector('.reservation-details');
        if (reservationDetails) {
            reservationDetails.classList.add('hide');
        }
    }

    if (resetButton) {
        resetButton.addEventListener('click', function () {
            localStorage.removeItem('reservationData'); // ローカルストレージからデータを削除
            clearDisplay(); // 表示をクリア
            location.reload(); // ページをリロード
        });
    }

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
                    stopButton.classList.add('hide');
                    startButton.classList.remove('hide');
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
                        document.getElementById('reservation-date').textContent = reservationDate
                            .toLocaleDateString('ja-JP');
                        document.getElementById('reservation-time').textContent = reservationDate
                            .toLocaleTimeString('ja-JP', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            });
                        document.getElementById('reservation-number').textContent = data.number;
                        document.getElementById('reservation-id').textContent = data.id;
                        document.getElementById('reservation-user-name').textContent = data.user_name;
                        document.getElementById('reservation-email').textContent = data.email;

                        // 予約詳細セクションを表示
                        const reservationDetails = document.querySelector('.reservation-details');
                        if (reservationDetails) {
                            reservationDetails.classList.remove('hide');
                        }
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
