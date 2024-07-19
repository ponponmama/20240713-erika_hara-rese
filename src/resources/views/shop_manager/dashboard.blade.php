@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shop Manager Dashboard</h1>
    <p>Welcome to your Dashboard.</p>
    <a href="{{ route('shop_manager.manage.shop') }}">Manage Shop</a>
</div>
@endsection