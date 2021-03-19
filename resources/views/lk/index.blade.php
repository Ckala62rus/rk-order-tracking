@extends('layouts.main')

@section('content')
    <div class="">
        <h1>Информация о заказе</h1>

        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th scope="col">ПРЖ</th>
                <th scope="col">Максимальная дата поставки по ПРЖ</th>
                <th scope="col"> Запрошенная дата поставки по ПРЖ</th>
{{--                <th scope="col">SALESNAME</th> --}}
{{--                <th scope="col">CUSTACCOUNT</th>--}}
{{--                <th scope="col">PRODSTATUS</th>--}}
                <th scope="col">Количество заказанное клиентом по ПРЖ</th>
                <th scope="col">Количество по ПРЖ</th>
                <th scope="col">КПП/ИНН</th>
                <th scope="col">Менеджер</th>
{{--                <th scope="col">CREATEDATETIME</th>--}}
{{--                <th scope="col">UserID</th>--}}
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{ $order->PRJ }}</th>
                        <th scope="row">{{ $order->DlvDate }}</th>
                        <th scope="row">{{ $order->ShippingDateRequested }}</th>
{{--                        <th scope="row">{{ $order->SALESNAME }}</th>--}}
{{--                        <th scope="row">{{ $order->CUSTACCOUNT }}</th>--}}
{{--                        <th scope="row">{{ $order->PRODSTATUS }}</th>--}}
                        <th scope="row">{{ $order->QTYORDERED }}</th>
                        <th scope="row">{{ $order->QTYSCHED }}</th>
                        <th scope="row">{{ $order->REGISTRATIONNUMBER }}</th>
                        <th scope="row">{{ $order->Manager }}</th>
{{--                        <th scope="row">{{ $order->CREATEDATETIME }}</th>--}}
{{--                        <th scope="row">{{ $order->UserID }}</th>--}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
