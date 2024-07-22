@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the Admin Dashboard.</p>
    <a href="{{ route('admin.manage.shop_managers') }}">Shop Managers Management</a>
</div>
@endsection