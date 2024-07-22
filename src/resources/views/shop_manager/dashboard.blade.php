@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shop Manager Dashboard</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <p>Welcome to your Dashboard.</p>
    <a href="{{ route('shop_manager.manage.shop') }}">Manage Shop</a>
</div>
@endsection