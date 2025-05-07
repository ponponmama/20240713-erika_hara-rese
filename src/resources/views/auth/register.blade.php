@extends('layouts.auth_app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth-common-styles.css') }}">
@endsection

@section('content')
    <div class="registration_container">
        <div class="title-box">
            <h2 class="form-title">Registration</h2>
        </div>
        <form action="{{ route('register') }}" method="POST" class="register_form">
            @csrf
            <div class="input-group">
                <img src="{{ asset('images/human.png') }}" alt="" class="icon-img">
                <input type="text" id="user_name" name="user_name" placeholder="Username" value="{{ old('user_name') }}" class
                ="input_user_name">
            </div>
            <div class="form__error">
                @error('user_name')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/mail.png') }}" alt="" class="icon-img">
                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" class="input_email">
            </div>
            <div class="form__error">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
            <div class="input-group">
                <img src="{{ asset('images/key.png') }}" alt="" class="icon-img">
                <input type="password" id="password" name="password" placeholder="Password" value="{{ old('password') }}" class="input_password">
            </div>
            <div class="form__error">
                @error('password')
                    {{ $message }}
                @enderror
            </div>
            <div class="button-container">
                <button class=" register-button" type="submit">登録</button>
            </div>
        </form>
    </div>
@endsection