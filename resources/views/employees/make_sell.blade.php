@extends('layouts.employee')

@section('breadcrumb')
    Make a sell
@endsection

@section('main-content')
    <p class="h4 mt-4">Make a Sell</p>
    <hr>
    <div class="form-group d-flex">
        <div class="flex-grow-1 search-box">
            <input type="text" name="search_text" id="search_text" class="form-control" placeholder="Search Product Name/SKU"
                data-url = "{{ route('employee.sell.product.search') }}" autofocus>
            <div id="searched-item" class="rounded-bottom bg-secondary searched-item"></div>
        </div>
        <input type="submit" value="Search" class="btn btn-primary ms-3">
    </div>
    <hr>
    <div>
        <p class="h4">Current Cart</p>
        <table class="table table-striped table-bordered mt-3">
            <thead>
                <th>#</th>
                <th>Product ID</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
            </thead>
            <tbody id="selected-items">
            </tbody>
        </table>
    </div>

    <div class="checkout-bottom-bar py-4 d-flex align-items-center justify-content-end">
        <p class="mb-0">Total:</p>
        <p class="fw-semibold fs-3 ms-2 me-4 mb-0" id="checkout-total-cost">0.00</p>
        <div class="ps-4 border-start">
            <button type="button" id="btnCheckout" data-ref="{{ csrf_token() }}" data-url="{{ route('employee.sell.checkout') }}" class="btn btn-success flex-shrink-0">Checkout Cart</button>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/makesell.js'])
    </script>
@endsection
