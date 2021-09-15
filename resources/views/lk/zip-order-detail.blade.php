@extends('template.main2')

@section('content')
{{--    <div class="d-flex flex-column-fluid">--}}
{{--        <div class="container">--}}
{{--            <h1>Подробная информация по заказам</h1>--}}
{{--            <p>{{$id}}</p>--}}
{{--            <zip-detail-orders-table :id="{{$id}}"></zip-detail-orders-table>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="jumbotron jumbotron-fluid">
        <zip-detail-orders-table :id="{{$id}}"></zip-detail-orders-table>
    </div>
@endsection
