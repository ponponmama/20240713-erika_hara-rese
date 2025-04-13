@php
    $title = $title ?? '';
    $userName = $userName ?? Auth::user()->user_name;
    $additionalClass = $additionalClass ?? '';
    $message = $message ?? 'お疲れ様です！';
    $showMessage = $showMessage ?? true;
    $useFormTitle = $useFormTitle ?? true;
@endphp

@if($title)
    @if($useFormTitle)
        <h1 class="title-name">{{ $title }}</h1>
    @else
        <h1>{{ $title }}</h1>
    @endif
@endif
<p class="user__name {{ $additionalClass }}">
    @if($showMessage){{ $message }}@endif{{ $userName }}さん
</p>
