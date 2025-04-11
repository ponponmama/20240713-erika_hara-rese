@php
    $title = $title ?? '';
    $userName = $userName ?? Auth::user()->user_name;
    $additionalClass = $additionalClass ?? '';
    $message = $message ?? 'お疲れ様です！';
    $showMessage = $showMessage ?? true;
@endphp

<h1 class="form-title">{{ $title }}</h1>
<p class="user__name {{ $additionalClass }}">
    @if($showMessage){{ $message }}@endif{{ $userName }}さん
</p>
