<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>領収書クローラー Invoiceee</title>
    </head>
    <body class="antialiased">
    <h2>invoiceee</h2>
    @if($user === null)
    <div>
        <a href="{{ $freeeAuthUrl }}">Freeeでアカウント作成・ログイン</a>
    </div>
    @else
    <div>
        ログインしています<br>
        userId: {{ $user->getId() }}
    </div>
    <div>
        <a href="{{ $lineAuthUrl }}">LINEと連携</a>
    </div>
    <div>
        <h3>LINE ユーザー ID を入力して連携</h3>
        <form method="POST" action="/line-user">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input name="lineUserId"
                   placeholder="ここに LINE ユーザー ID を入力">
        </form>
    </div>
    <div>
        <h3>連携済みアカウント</h3>
        <ul>
            @foreach ($linkages as $linkage)
            <li>
                {{ $linkage->getServiceName() }}:
                {{ $linkage->getServiceAccountId() }}
            </li>
            @endforeach
        </ul>
    </div>
    <div>
        <h3>新規アカウント連携</h3>
        <div>
            <a href="{{ $herokuAuthUrl }}">Herokuと連携</a>
        </div>
    </div>
    @endif
    <!--
    <div>
        <a href="{{ $herokuAuthUrl }}">Herokuと連携</a>
    </div>
    -->
    </body>
</html>
