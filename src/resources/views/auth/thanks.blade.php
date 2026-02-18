@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('common_css/auth-styles.css') }}">
@endsection

@section('content')
    <div class="thanks-form">
        <h2 class="form-thanks-title">会員登録ありがとうございます</h2>
        <div class="thanks-button-container">
            <a href="{{ route('login') }}" class="thanks-button link">ログインする</a>
        </div>
    </div>
@endsection
