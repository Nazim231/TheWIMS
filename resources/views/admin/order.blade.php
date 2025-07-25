@extends('layouts.admin')

@section('breadcrumb')
    Order
@endsection

@section('main-content')

    @php
        $orderStatus = $order->status;
        $isOrderProcessing = $orderStatus == 'Processing' || $orderStatus == 'Partially Delivered';
        $badgeType = 'text-bg-';
        if (!$isOrderProcessing) {
            switch ($orderStatus) {
                case 'Completed':
                    $badgeType .= 'success';
                    break;
                case 'Cancelled':
                    $badgeType .= 'warning';
                    break;
                default:
                    $badgeType .= 'danger';
                    break;
            }
        }
    @endphp

    <p class="h4 fw-semibold mt-3 d-flex align-items-center">Order Details</p>
    <hr>

    <div class="row my-4">
        <div class="col">
            <p>Order ID : <span class="fw-semibold">{{ $order->id }}</span></p>
            <p>Shop : <span class="fw-semibold">{{ $order->shop->name }}</span></p>
            <p>Order date : <span class="fw-semibold">{{ $order->created_at }}</span></p>
            <p>Last update : <span class="fw-semibold">{{ $order->updated_at }}</span></p>
        </div>
        <div class="col">
            <p class="d-flex align-items-center">Status :
                <span class="badge {{ $badgeType }} ms-2 ">{{ $orderStatus }}</span>
            </p>
            <p>Total Items :
                <span class="fw-semibold">{{ $order->requested_items + $order->approved_items }}</span>
            </p>
            <p>Total Cost price :
                <span class="fw-semibold" id="totalCost">{{ $order->total_cost }}</span>
            </p>
            <p>Est. Revenue :
                <span class="fw-semibold" id="estRevenue">{{ $order->est_revenue }}
                    <span
                        class="text-success">({{ (($order->est_revenue - $order->total_cost) / $order->est_revenue) * 100 }}%
                        Profit)</span>
                </span>
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mt-3 pt-2 pb-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="mb-2">{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($order->products != null && sizeof($order->products) > 0)
        {{-- If the Order is not completed then the table will be represented as a form else normal table --}}
        @if ($isOrderProcessing)
            <form action="{{ route('admin.order.approve') }}" method="post">
                @csrf
        @endif

        <table class="table table-striped table-bordered mt-4">
            <thead>
                <tr class="table-secondary">
                    <th>#</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Requested Quantity</th>
                    <th>Approved Quantity</th>

                    @if ($isOrderProcessing)
                        <th>Available Quantity</th>
                        <th>Qty. To Approve</th>
                    @else
                        <th>Order Date</th>
                        <th>Completion Date</th>
                    @endif

                    <th>Cost Price</th>
                    <th>Revenue</th>
                    <th>Profit</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($order->products as $product)
                    @php
                        $whAvailableQty = $product->variation->quantity;
                        $haveSufficientQty = $whAvailableQty >= $product->requested_quantity;

                        if ($isOrderProcessing && $product->requested_quantity != 0) {
                            /**
                             * putting some data to the session for further use in form validation
                             */
                            $stock_quantities = Session('wh_stock_quantities') ?? [];
                            $stock_names = Session('wh_stock_names') ?? [];
                            $order_product_ids = Session('order_product_ids') ?? [];

                            $stock_quantities[] = $whAvailableQty;
                            $stock_names[] = $product->variation->product->name;
                            $order_product_ids[] = $product->id;

                            session()->put('wh_stock_quantities', $stock_quantities);
                            session()->put('wh_stock_names', $stock_names);
                            session()->put('order_product_ids', $order_product_ids);
                        }

                        $qty = $product->requested_quantity + $product->approved_quantity;
                        $cost_price = $product->variation->cost_price * $qty;
                        $revenue = $product->variation->price * $qty;
                        $profit = ($product->variation->price - $product->variation->cost_price) * $qty;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('admin.stocks.product', $product->variation->product->id) }}">
                                {{ $product->variation->product->name }}
                            </a>
                        </td>
                        <td>{{ $product->variation->SKU }}</td>
                        <td>{{ $product->requested_quantity + $product->approved_quantity }}</td>
                        <td> {{ $product->approved_quantity }} </td>
                        @if ($isOrderProcessing)
                            <td>
                                {{ $product->variation->quantity }}

                                @if (!$haveSufficientQty && $isOrderProcessing)
                                    <span class="badge text-bg-warning fw-normal ms-2">Insufficient Quantity</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->requested_quantity == 0)
                                    <span class="badge text-bg-info">Product Delivered</span>
                                @else
                                    <input type="number" name="approved_quantity[]" class="form-control"
                                        value="{{ $haveSufficientQty ? $product->requested_quantity : 0 }}">
                                @endif
                            </td>
                        @else
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->updated_at }}</td>
                        @endif
                        <td> {{ $cost_price }} </td>
                        <td> {{ $revenue }}</td>
                        <td> {{ $profit }} </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        @if ($isOrderProcessing)
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" id="rejectOrder" class="btn btn-outline-danger">Reject Order</button>
                <input type="submit" class="btn btn-success" value="Complete Order"></input>
            </div>
            </form>
        @endif
    @endif
@endsection


@section('scripts')
    <script>
        $('#rejectOrder').on('click', () => {
            const data = {
                orderId: '{{ $order->id }}'
            };
            $.ajax({
                url: "{{ route('admin.order.reject') }}",
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                formData: data,
                type: JSON,
                success: function(response) {
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
