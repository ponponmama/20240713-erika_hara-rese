<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了</title>
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
</head>
<body>
    <div class="registration-form">
        <h1>会員登録ありがとうございます</h1>
    </div>
    <div class="button-container">
        <a href="{{ route('login') }}" class="login-button">ログインする</a>
    </div>
</body>
</html>