<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Todos') }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Playwrite+NZ:wght@100..400&display=swap" rel="stylesheet">

    @vite(['resources/js/app.js', 'resources/css/app.css', 'resources/scss/app.scss'])
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8600642579394173"
     crossorigin="anonymous"></script>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

</head>
<body class="font-sans antialiased">
    @include('layouts.nav')
    <div id="app" class="body-marging">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
<style>

    body {
        font-family: 'Istok Web', sans-serif;
    }
    .body-marging {
        margin-top: 90px;
    }
    @media (max-width: 1024px) {
        .body-marging {
            margin-top: 0px;
        }
    }
</style>
