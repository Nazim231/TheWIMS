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

        @if ($shopStocks != null)
        @else
            <p class="text-center text-danger">Shop doesn't have any products</p>
        @endif
    </div>
@endsection
