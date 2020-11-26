<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body class="antialiased">
    <div>
        <a href="{{ $freeeAuthUrl }}">Freeeでアカウント作成・ログイン</a>
    </div>
    <div>
        <a href="{{ $herokuAuthUrl }}">Herokuと連携</a>
    </div>
    </body>
</html>
