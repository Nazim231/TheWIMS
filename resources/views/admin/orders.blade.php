@extends('layouts.admin')

@section('breadcrumb')
    Orders
@endsection

@section('main-content')
    <p class="h4 fw-semibold mt-3">Orders to fulfill</p>
    @if (($orders != null) & (sizeof($orders) > 0))
        <table class="mt-4 table table-striped table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Shop Name</th>
                    <th>Order Date</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->shop->name . ' (#' . $order->shop_id . ')' }}</td>
                        <td>{{ $order->created_at }}</td>
                        <td>{{ $order->products_count . ' Product(s)' }}</td>
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
                        <td class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-sm text-success btn-link text-decoration-none">View</a>
                            @if ($order->status == 'Processing' || $order->status == 'Partially Delivered')
                            <a href="" class="btn btn-sm text-danger btn-link text-decoration-none">Reject</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
