@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('common_css/auth-styles.css') }}">
@endsection

@section('content')
    <div class="login_container">
        <div class="title-box">
            <h2 class="form-title">Login</h2>
        </div>
        <form action="{{ route('login') }}" method="POST" class="login_form">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/mail.png') }}" alt="email_icon" class="icon-img">
                <input type="email" id="email" name="email" placeholder="Email" class="form-input input_email"
                    value="{{ old('email') }}">
            </div>
            <p class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}"
                    class="form-input input_password">
            </div>
            <p class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </p>
            <div class="button-container">
                <button class="button login-button" type="submit">
                    ログイン
                </button>
            </div>
        </form>
    </div>
@endsection
