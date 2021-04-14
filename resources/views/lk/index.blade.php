@extends('template.main')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Информация о заказе компании {{ $companyName }}
                </h3>
            </div>
        </div>
        <div class="card-body" style="overflow: auto">
            <table class="table table-bordered table-checkable dataTable no-footer dtr-inline collapsed orders__tracking__table">
                <thead>
                <tr class="">
{{--                    <th scope="col">Дата размещения ПРЖ</th>--}}
                    <th scope="col">Номер заказа ПРЖ</th>
                    <th scope="col">Заказанный обьем в ПРЖ</th>
                    <th scope="col" class="order_table_header">Артикул</th>
                    <th scope="col">Производственный заказ</th>
                    <th scope="col">Статус производственного заказа</th>
                    <th scope="col">Объем производственного заказа</th>

                    <th scope="col">Цвет</th>
                    <th scope="col">Конфигурация</th>
                    <th scope="col">Толщина</th>

                    <th scope="col">Дата поставки</th>
                    <th scope="col">Дата окончания</th>
                    <th scope="col">Дата поставки/Дата окончания</th>
                    <th scope="col">Менеджер</th>
                </tr>
                </thead>
                <tbody>

                @foreach($orders as $order)
                    <tr>
{{--                        <th scope="row">{{ $order->OrderDate }}</th>--}}
                        <th scope="row">{{ $order->OrderNumber }}</th>
                        <th scope="row">{{ $order->OrderQTY }}</th>
                        <th scope="row">{{ $order->Article }}</th>
                        <th scope="row">{{ $order->ProdOrderNumber }}</th>
                        <th scope="row">{{ $order->ProdOrderStatus }}</th>
                        <th scope="row">{{ $order->ProdOrderQTY }}</th>

                        <th scope="row">{{ $order->Color }}</th>
                        <th scope="row">{{ $order->Config }}</th>
                        <th scope="row">{{ $order->Thickness }}</th>

                        <th scope="row">{{ $order->DeliveryDate }}</th>
                        <th scope="row">{{ $order->EndDate }}</th>
                        <th scope="row">{{ $order->LeadOrLagTime }}</th>
                        <th scope="row">{{ $order->ManagerName }}</th>
                    </tr>
                @endforeach

{{--                @for ($i = 0; $i < count($orders); $i++)--}}
{{--                    <tr>--}}
{{--                        <th scope="row">{{ $orders[$i]->PRJ }}</th>--}}
{{--                        <th scope="row">{{ $orders[$i]->DlvDate }}</th>--}}
{{--                        <th scope="row">{{ $orders[$i]->ShippingDateRequested }}</th>--}}

{{--                        @if ($i == 0)--}}
{{--                            <th scope="row" rowspan="3" style="text-align: center; vertical-align: middle">{{ $orders[$i]->QTYORDERED }}</th>--}}
{{--                        @endif--}}

{{--                        <th scope="row">{{ $orders[$i]->QTYSCHED }}</th>--}}
{{--                        <th scope="row">{{ $orders[$i]->REGISTRATIONNUMBER }}</th>--}}
{{--                        <th scope="row">{{ $orders[$i]->Manager }}</th>--}}
{{--                    </tr>--}}
{{--                @endfor--}}

                </tbody>
            </table>
        </div>
    </div>
@endsection
