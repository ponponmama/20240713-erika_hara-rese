<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
    @yield('css')
</head>

<body class="auth_body">
    <main>
        <div class="auth_content">
            @yield('content')
        </div>
    </main>
</body>

</html>
