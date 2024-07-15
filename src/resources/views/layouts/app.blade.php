<!DOCTYPE html>
<html lang="en">
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
            <h1 class="top_logo">Rese</h1>
            @yield('content')
        </div>
    </main> 
</body>
</html>