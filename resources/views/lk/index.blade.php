@extends('layouts.main')

@section('content')
    <div class="">
        <h1>Информация о заказе компании {{ $companyName }}</h1>

        <table class="table table-bordered table-checkable dataTable no-footer dtr-inline collapsed">
            <thead>
            <tr>
                <th scope="col">Номер заказа покупателя</th>
                <th scope="col">Максимальная дата поставки</th>
                <th scope="col"> Запрошенная дата поставки</th>
                <th scope="col">Количество заказанное клиентом (дм<sup>2</sup>)</th>
                <th scope="col">Количество планируемое к производству (дм<sup>2</sup>)</th>
                <th scope="col">КПП/ИНН</th>
                <th scope="col">Менеджер</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{ $order->PRJ }}</th>
                        <th scope="row">{{ $order->DlvDate }}</th>
                        <th scope="row">{{ $order->ShippingDateRequested }}</th>
                        <th scope="row">{{ $order->QTYORDERED }}</th>
                        <th scope="row">{{ $order->QTYSCHED }}</th>
                        <th scope="row">{{ $order->REGISTRATIONNUMBER }}</th>
                        <th scope="row">{{ $order->Manager }}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
