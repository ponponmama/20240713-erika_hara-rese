@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>Edit Shop Information</h1>
    <form action="{{ route('shop_manager.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div>
            <label>店舗名:</label>
            <span>{{ $shop->name }}</span>
        </div>
        <div>
            <label for="description">店舗紹介</label>
            <textarea id="description" name="description">{{ $shop->description }}</textarea>
        </div>
        <div>
            <h3>営業時間</h3>
        </div>
        <div>
            <label for="open_time">オープン</label>
            <input type="time" id="open_time" name="open_time" value="{{ $shop->open_time }}">
        </div>
        <div>
            <label for="close_time">クローズ</label>
            <input type="time" id="close_time" name="close_time" value="{{ $shop->close_time }}">
        </div>
        <div>
            <label for="image">写真</label>
            <input type="file" id="image" name="image">
        </div>
        <button type="submit">更新する</button>
    </form>
</div>
@endsection