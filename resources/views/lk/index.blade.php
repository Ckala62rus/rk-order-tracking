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
            <table class="table table-bordered table-checkable dataTable no-footer dtr-inline collapsed">
                <thead>
                <tr>
                    <th scope="col">OrderNumber</th>
                    <th scope="col">ProdOrderNumber</th>
                    <th scope="col">OrderQTY</th>
                    <th scope="col">OrderDate</th>
                    <th scope="col">OrderConfirmQTY</th>
                    <th scope="col">ContractorName</th>
                    <th scope="col">ManagerName</th>
		    <th scope="col">Article</th>
		    <th scope="col">ProdOrderStatus</th>
		    <th scope="col">ProdOrderQTY</th>
                    <th scope="col">Color</th>
		    <th scope="col">Config</th>
		    <th scope="col">Thickness</th>
		    <th scope="col">DeliveryDate</th>
		    <th scope="col">EndDate</th>
                    <th scope="col">LeadOrLagTime</th>
                </tr>
                </thead>
                <tbody>

                @foreach($orders as $order)
                    <tr>
                        <th scope="row">{{ $order->OrderNumber }}</th>
                        <th scope="row">{{ $order->ProdOrderNumber }}</th>
                        <th scope="row">{{ $order->OrderQTY }}</th>
                        <th scope="row">{{ $order->OrderDate }}</th>
                        <th scope="row">{{ $order->OrderConfirmQTY }}</th>
                        <th scope="row">{{ $order->ContractorName }}</th>
                        <th scope="row">{{ $order->ManagerName }}</th>
 			<th scope="row">{{ $order->Article }}</th>
		  	<th scope="row">{{ $order->ProdOrderStatus }}</th>
 			<th scope="row">{{ $order->ProdOrderQTY }}</th>
 			<th scope="row">{{ $order->Color }}</th>
 			<th scope="row">{{ $order->Config }}</th>
 			<th scope="row">{{ $order->Thickness }}</th>
 			<th scope="row">{{ $order->DeliveryDate }}</th>
 			<th scope="row">{{ $order->EndDate }}</th>
	 		<th scope="row">{{ $order->LeadOrLagTime }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
