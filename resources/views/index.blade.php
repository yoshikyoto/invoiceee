<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>領収書クローラー Invoiceee</title>
    </head>
    <body class="antialiased">
    @if($user === null)
    <div>
        <a href="{{ $freeeAuthUrl }}">Freeeでアカウント作成・ログイン</a>
    </div>
    @else
    ログインしています
    @endif
    <!--
    <div>
        <a href="{{ $herokuAuthUrl }}">Herokuと連携</a>
    </div>
    -->
    </body>
</html>
