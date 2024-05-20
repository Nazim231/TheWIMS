@extends('layouts.employee')

@section('breadcrumb')
    Order Details
@endsection

@section('main-content')
    @php
        $orderStatus = $order->status;
        $badgeType = 'text-bg-';
        switch ($orderStatus) {
            case 'Completed':
                $badgeType .= 'success';
                break;
            case 'Cancelled':
                $badgeType .= 'warning';
                break;
            case 'Rejected':
                $badgeType .= 'danger';
                break;
            case 'Partially Delivered':
                $badgeType .= 'info';
                break;
            default:
                $badgeType .= 'secondary';
                break;
        }
    @endphp

    <div>
        <p class="h4 fw-semibold mt-3 d-flex align-items-center">
            Order ID : {{ $order->id }}
            <span class="badge {{ $badgeType }} h4 fw-normal ms-4 mb-0">{{ $orderStatus }}</span>
        </p>
        <div class="mt-4">
            <p>
                <span class="fw-bold">Order date:</span> &nbsp; {{ $order->created_at }}
            </p>
            <p>
                <span class="fw-bold">Status update date:</span> &nbsp; {{ $order->updated_at }}
            </p>
        </div>
    </div>

    <hr>

    <div class="mt-3">
        <p class="h5">Products List</p>
        <table class="table table-striped table-bordered text-center">
            <thead>
                <th>#</th>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Approved Quantity</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach ($order->products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->variation->product->name }}</td>
                        <td>{{ $product->variation->SKU }}</td>
                        <td>{{ $product->requested_quantity + $product->approved_quantity }}</td>
                        <td>{{ $product->approved_quantity }}</td>
                        <td>
                            @php
                                $approvedQty = $product->approved_quantity;
                                $requestedQty = $product->requested_quantity;
                                $status = '';
                                $badgeType = 'text-bg-';
                                if ($approvedQty == 0 && ($orderStatus == 'Processing' || $orderStatus == 'Partially Delivered')) {
                                    $status = 'Processing';
                                    $badgeType .= 'warning';
                                } else if ($requestedQty == 0) {
                                    $status = 'Completed';
                                    $badgeType .= 'success';
                                } else if ($requestedQty > 0 && $approvedQty > 0) {
                                    $status = 'Partially Delivered';
                                    $badgeType .= 'info';
                                } else {
                                    $status = $orderStatus;
                                    $badgeType .= 'danger';
                                }
                            @endphp
                            <span class="badge {{ $badgeType }}">{{ $status }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
