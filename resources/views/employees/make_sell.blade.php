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
        <button type="button" class="btn btn-danger ms-3 px-4">Clear Cart</button>
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
            <button type="button" id="btnCheckout" class="btn btn-success flex-shrink-0">Checkout Cart</button>
        </div>
    </div>

    <div class="modal fade" id="confirmCheckoutDialog" tabindex="-1" data-bs-keyboard="true" data-bs-backdrop="static" aria-labelledby="lblConfirmCheckoutDialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="lblConfirmCheckoutDialog">Confirm Checkout</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive">
                        <thead>
                            <th>#</th>
                            <th>Name</th>
                            <th>Qty.</th>
                            <th>Price</th>
                        </thead>
                        <tbody id="confirm-selected-items">
                        </tbody>
                    </table>
                    <div class="mt-2 d-flex justify-content-end gap-2">
                        <p class="fw-semibold fs-6">Total Items:&nbsp;<span class="fw-normal" id="total-items"></span></p>
                        <p class="fw-semibold fs-6">Total Qty:&nbsp;<span class="fw-normal" id="total-qty"></span></p>
                        <p class="fw-semibold fs-6">Total Amount:&nbsp;<span class="fw-normal" id="total-amount"></span></p>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="btnFinalizeCheckout" class="btn btn-success" data-ref="{{ csrf_token() }}" data-url="{{ route('employee.sell.checkout') }}">Confirm Checkout</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/makesell.js'])
@endsection
