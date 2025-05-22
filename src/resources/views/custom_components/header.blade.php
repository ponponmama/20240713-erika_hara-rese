@php
    $title = $title ?? '';
    $userName = $userName ?? Auth::user()->user_name;
    $additionalClass = $additionalClass ?? '';
    $message = $message ?? 'お疲れ様です！';
    $showMessage = $showMessage ?? true;
    $useFormTitle = $useFormTitle ?? true;
    $showUserName = $showUserName ?? true;
    $headingLevel = $headingLevel ?? 2;
@endphp

@if($title)
    @if($useFormTitle)
        <h{{ $headingLevel }} class="title-name">{{ $title }}</h{{ $headingLevel }}>
    @else
        <h{{ $headingLevel }}>{{ $title }}</h{{ $headingLevel }}>
    @endif
@endif
@if($showUserName)
<p class="user__name {{ $additionalClass }}">
    @if($showMessage){{ $message }}@endif{{ $userName }}さん
</p>
@endif
