@extends('layouts.admin')

@section('breadcrumb')
    Order
@endsection

@section('main-content')
    <p class="h4 fw-semibold mt-3">Order ID: {{ $order->id }}</p>
    
    @if ($order->products != null && sizeof($order->products) > 0)
    <table class="table table-striped table-bordered mt-4 text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Requested Quantity</th>
                <th>Available Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->variation->product->name }}</td>
                    <td>{{ $product->variation->SKU }}</td>
                    <td>{{ $product->requested_quantity }}</td>
                    <td>{{ $product->variation->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end gap-2 mt-4">
        <button class="btn btn-outline-danger">Reject Order</button>
        <button class="btn btn-success">Complete Order</button>
    </div>
    @endif
@endsection
