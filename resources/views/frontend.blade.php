<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Monoland') }}</title>
    
    <style>
        [v-cloack] {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="/vendor/monoland/frontend.css">
</head>

<body>
    <div id="monoland"></div>

    
    <noscript>
        <strong>We're sorry but apps doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    
    @if (Route::has('login'))
        @auth
            <script>let authState = true;</script>
        @else
            <script>let authState = false;</script>
        @endif
    @else
        <script>let authState = false;</script>
    @endif

    <script src="/vendor/monoland/manifest.js"></script>
    <script src="/vendor/monoland/vendor.js"></script>
    <script src="/vendor/monoland/frontend.js"></script>
</body>
</html>