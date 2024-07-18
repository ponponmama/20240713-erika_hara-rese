<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    @yield('css')
</head>

<body>
    <main>
        <div class="container">
            <div class="content">
                @hasSection('show_header')
                <div class="header">
                    @include('partials.navbar')
                    <h1 class="top_logo">Rese</h1>
                </div>
                @endif
                @yield('content')
            </div>
        </div>
    </main> 
</body>
</html>