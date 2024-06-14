@extends('layouts.admin')

@section('breadcrumb')
    Shop - {{ $shop->name . ' (' . $shop->id . ')' }}
@endsection

@vite(['resources/js/shops.js'])

@section('main-content')
    <p class="h4 fw-semibold mt-3">Shop Details</p>
    <hr>
    <div class="my-3">
        <p>
            <span class="fw-semibold">Shop Name: </span> &nbsp; {{ $shop->name }}
        </p>
        <p>
            <span class="fw-semibold">Shop ID: </span> &nbsp; {{ $shop->id }}
        </p>
        <p>
            <span class="fw-semibold">Shop Address: </span> &nbsp; {{ $shop->address }}
        </p>
        <p>
            <span class="fw-semibold">Shop Employee: </span> &nbsp;
            @if ($shop->emp_name)
                {{ $shop->emp_name }}
            @else
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#assignEmpModal">Assign Employee</button>
                {{-- Assign employee modal --}}
                <div class="modal fade" id="assignEmpModal" tabindex="-1" aria-labelledby="assignEmpModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="assignEmpModalLabel">Assign Employee</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.shops.assign_emp') }}" method="POST">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="shop" value="{{$shop->id}}">
                                    <div class="form-group">
                                        <label for="employee">Select Employee</label>
                                        <select name="employee" id="employee" class="form-select mt-2"
                                            data-id="{{ route('admin.employees.unassigned') }}">
                                            <option value="0" selected disabled>Please choose an employee</option>
                                        </select>
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <p class="text-danger mt-2">{{ $error }}</p>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                                    <input type="submit" value="Assign Employee" class="btn btn-dark">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </p>
    </div>
    <hr>

    @php
        $isShopEmpty = sizeof($shop->products) == 1 && $shop->products[0]->product_name == null;
    @endphp

    <div class="my-3">
        <p class="d-flex align-items-center justify-content-between">
            <span>
                <span class="h4 fw-semibold">Products&nbsp;</span>
                <span class=" text-secondary fs-6">({{ $isShopEmpty ? '0' : sizeof($shop->products) }})</span>
            </span>
            <a href="#">View all</a>
        </p>
        @if ($isShopEmpty)
            <p class="text-danger text-center fw-semibold">No Products Found</p>
        @else
            <table class="table table-striped table-bordered text-center cursor-default">
                <thead>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Variations</th>
                    <th>Quantity</th>
                </thead>
                <tbody>
                    @foreach ($shop->products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->variation_count }}</td>
                            <td>{{ $product->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="pt-3">
        <div class="d-flex justify-content-between align-items-center">
            <span class="h4 fw-semibold">Recent Orders</span>
            <a href="#">View more</a>
        </div>
        @if (sizeof($shopOrders) > 0)
            <table class="table table-striped table-hover table-bordered cursor-pointer text-center mt-2">
                <thead class="cursor-default">
                    <th>#</th>
                    <th>Order ID</th>
                    <th>No. of Products</th>
                    <th>Order Date</th>
                    <th>Completion Date</th>
                    <th>Order Status</th>
                </thead>
                <tbody>
                    @foreach ($shopOrders as $order)
                        <tr onclick="window.location.href = '{{ route('admin.order.show', $order->id) }}'">
                            <td class="text-secondary">{{ $loop->iteration }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->products_count }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{!! $order->status != 'Processing' ? $order->updated_at : '<span class="fw-light">No updates</span>' !!}</td>
                            <td>
                                @php
                                    $badgeType = 'text-bg-';
                                    switch ($order->status) {
                                        case 'Processing':
                                            $badgeType .= 'warning';
                                            break;
                                        case 'Partially Delivered':
                                            $badgeType .= 'info';
                                            break;
                                        case 'Completed':
                                            $badgeType .= 'success';
                                            break;
                                        default:
                                            $badgeType .= 'danger';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeType }} fw-medium">{{ $order->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center fw-semibold text-danger">Shops doesn't have any order yet.</p>
        @endif
    </div>
@endsection
