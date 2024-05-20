@extends('layouts.employee')

@section('breadcrumb')
    Orders
@endsection

@section('main-content')
    <div class="p-1">
        <p class="display-4 fw-medium m-0">Your Orders</p>
        <hr>
        @if ($orderHistory != null && sizeof($orderHistory) > 0)
            <table class="table table-striped table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>   
                        <th>Products Count</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderHistory->all() as $order)
                        <tr class="cursor-pointer" onclick="window.location.href = '{{ route('employee.order.show', $order->id) }}'">
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->products_count . ' Product(s)' }}</td>
                            <td>{{ $order->created_at }}</td>
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
                                <span class="badge {{ $badgeType }}">{{ $order->status }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
