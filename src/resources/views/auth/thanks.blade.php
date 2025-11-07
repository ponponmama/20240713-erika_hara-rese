@extends('layouts.auth_app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth-common-styles.css') }}">
@endsection

@section('content')
    <div class="thanks-form">
        <h2 class="form-thanks-title">会員登録ありがとうございます</h2>
        <div class="thanks-button-container">
            <a href="{{ route('login') }}" class="link thanks-button">ログインする</a>
        </div>
    </div>
@endsection