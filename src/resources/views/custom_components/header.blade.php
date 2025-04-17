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
        <h2 class="title-name">{{ $title }}</h2>
    @else
        <h2>{{ $title }}</h2>
    @endif
@endif
<p class="user__name {{ $additionalClass }}">
    @if($showMessage){{ $message }}@endif{{ $userName }}さん
</p>
