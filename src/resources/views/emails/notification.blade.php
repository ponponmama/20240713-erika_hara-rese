<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        .container {
            width: 100%;
            text-align: center;
        }
        .message {
            display: inline-block;
            text-align: left;
            max-width: 600px;
            font-size:1rem;
        }

        .hello {
            display: inline-block;
            text-align: left; /* メッセージ内容を左寄せ */
            max-width: 600px;
            font-size: 1.3rem;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <p class="hello">こんにちは、{{ $reservation->user->user_name }}さん</p>
            <p>以下の予約が確定しました。</p>
            <p>予約日時: {{ $reservation->reservation_datetime->format('Y-m-d H:i') }}</p>
            <p>人数: {{ $reservation->number }}</p>
        </div>
    </div>
</body>
</html>