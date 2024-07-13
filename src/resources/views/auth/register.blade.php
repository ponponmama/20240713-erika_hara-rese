<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
</head>
<body>
    <div class="registration-form">
        <div class="title-box">
            <h2 class="form-title">Registration</h2>
        </div>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="icon-container">
                <img src="{{ asset('images/human.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required>
            </div>
            <div class="form__error">
                @error('username')
                    {{ $message }}
                @enderror
            </div>
            <div class="icon-container">
                <img src="{{ asset('images/mail.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
            </div>
            <div class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="icon-container">
                <img src="{{ asset('images/key.png') }}" alt="">
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}" required>
            </div>
            <div class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            <div class="button-container">
                <button type="submit">登録</button>
            </div>
        </form>
    </div>
</body>
</html>