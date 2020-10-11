<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body class="antialiased">
<table>
    <tr><th>請求書ID</th><th>発行日時</th></tr>
    @foreach ($invoices as $invoice)
    <tr>
        <td>{{ $invoice->getId() }}</td>
        <td>{{ $invoice->getCreatedAt()->format('Y-m-d') }}</td>
    </tr>
    @endforeach
</table>
</body>
</html>
