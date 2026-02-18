@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('common_css/auth-styles.css') }}">
@endsection

@section('content')
    <div class="registration_container">
        <div class="title-box">
            <h2 class="form-title">Registration</h2>
        </div>
        <form action="{{ route('register') }}" method="POST" class="register_form" autocomplete="on">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/human.png') }}" alt="" class="icon-img">
                <input type="text" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}" class
                ="form-input input_user_name" autocomplete="username">
            </div>
            <p class="form__error">
                @error('user_name')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/mail.png') }}" alt="" class="icon-img">
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}"
                    class="form-input input_email" autocomplete="email">
            </div>
            <p class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </p>
            <div class="input-group">
                <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                <input type="password" id="password" name="password" placeholder="Password" class="form-input input_password" autocomplete="new-password">
            </div>
            <p class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </p>
            <div class="button-container">
                <button class="register-button button" type="submit">登録</button>
            </div>
        </form>
    </div>
@endsection
