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

    <p class="h4 fw-semibold mt-3 d-flex align-items-center">
        Order ID: {{ $order->id }}
        @if (!$isOrderProcessing)
            <span class="badge {{ $badgeType }} h4 fw-normal ms-4 mb-0">{{ $orderStatus }}</span>
        @endif
    </p>

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

                    @if ($isOrderProcessing)
                        <th>Available Quantity</th>
                        <th>Approved Quantity</th>
                    @else
                        <th>Order Date</th>
                        <th>Completion Date</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach ($order->products as $product)
                    @php
                        $haveSufficientQty = $product->variation->quantity >= $product->requested_quantity;

                        if ($isOrderProcessing && $product->requested_quantity != 0) {
                            /**
                             * putting some data to the session for further use in validation
                             * of form
                             */
                            $stock_quantities = Session('wh_stock_quantities') ?? [];
                            $stock_names = Session('wh_stock_names') ?? [];
                            $order_product_ids = Session('order_product_ids') ?? [];

                            $stock_quantities[] = $product->variation->quantity;
                            $stock_names[] = $product->variation->product->name;
                            $order_product_ids[] = $product->id;

                            session()->put('wh_stock_quantities', $stock_quantities);
                            session()->put('wh_stock_names', $stock_names);
                            session()->put('order_product_ids', $order_product_ids);
                        }
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('admin.stocks.product', $product->variation->product->id) }}">
                                {{ $product->variation->product->name }}
                            </a>
                        </td>
                        <td>{{ $product->variation->SKU }}</td>
                        <td>
                            {{ !$isOrderProcessing ? $product->approved_quantity : $product->requested_quantity }}

                            @if (!$haveSufficientQty && $isOrderProcessing)
                                <span class="badge text-bg-warning fw-normal ms-2">Insufficient Quantity</span>
                            @endif
                        </td>
                        @if ($isOrderProcessing)
                            <td>
                                {{ $product->variation->quantity }}
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
