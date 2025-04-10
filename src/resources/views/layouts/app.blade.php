<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @yield('css')

</head>

<body class="common_body">
    <main>
        <div class="content">
            <div class="header">
                @include('partials.navbar')
                <h1 class="top_logo">
                    Rese
                </h1>
                <div class="detail_shop">
                    @yield('detail_shop')
                </div>
                @yield('reservation_form')
                @yield('search')
            </div>
            @yield('content')
        </div>
    </main>
</body>
</html>
