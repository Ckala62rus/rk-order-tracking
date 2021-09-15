@extends('template.main2')

@section('content')
    <div class="jumbotron jumbotron-fluid">
        <zip-detail-orders-group-table :id="{{$id}}"></zip-detail-orders-group-table>
    </div>
@endsection
