@extends('layouts.employee')

@section('breadcrumb')
    Stocks
@endsection

@section('main-content')
    <div class="p-1">
        <div class="d-flex align-items-center">
            <p class="display-4 fw-medium m-0">Products</p>
            <a href="{{ route('employee.products.request.page') }}" class="btn btn-outline-primary ms-auto">
                <span class="me-2">+</span>Request more products
            </a>
        </div>
        <hr>
        @if (Session::get('order_placed'))
        <div class="alert alert-success">
            {{ Session::get('order_placed') }}
        </div>
        @endif

        @if ($shopStocks != null && sizeof($shopStocks) > 0)
            <table class="table table-striped border">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>MRP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shopStocks as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->name }}</td>
                            <td>{{ $stock->quantity }}</td>
                            <td>{{ $stock->min_price . ' to ' . $stock->max_price }}</td>
                            <td>{{ $stock->min_mrp . ' to ' . $stock->max_mrp }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-danger">Shop doesn't have any products</p>
        @endif
    </div>
@endsection
