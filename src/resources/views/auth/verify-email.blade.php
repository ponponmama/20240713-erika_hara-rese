<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            box-shadow: 2px 2px 4px #00000099;
            width: 40%;
            padding: 50px 20px !important;
            background-color: #f0f0f0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        p {
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
            font-size: 19px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        button {
            background-color: rgba(0, 0, 255, 0.7);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 13px;
        }

        button:hover {
            background-color: #003875;
        }

        @media (max-width: 768px) {
            p {
                font-size: 16px;
                /* 小さい画面でのフォントサイズ */
            }

            button {
                font-size: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <p>メールを確認して、リンクをクリックしてください。メールが届いていない場合は、以下のボタンを押して再送信してください。</p>
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit">認証リンクを再送信</button>
        </form>
    </div>
</body>

</html>
