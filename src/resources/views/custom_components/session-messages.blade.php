@php
    $successClass = $successClass ?? 'alert-success';
    $errorClass = $errorClass ?? 'alert-danger';
    $successMessageClass = $successMessageClass ?? 'payment-success';
@endphp

@if (session('success'))
    <div class="{{ $successClass }}">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="{{ $errorClass }}">
        {{ session('error') }}
    </div>
@endif

@if (session('success_message'))
    <div class="{{ $successMessageClass }}">
        {{ session('success_message') }}
    </div>
@endif
