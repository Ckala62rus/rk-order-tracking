<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset("css/bootstrap.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("css/main.css") }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset("js/bootstrap.js") }}" rel="stylesheet" type="text/css"/>
    <title>Информация о заказе</title>
</head>
<body>
    @include('layouts.header')

    @yield('content')
</body>
</html>
