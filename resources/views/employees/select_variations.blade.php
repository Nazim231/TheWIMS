@extends('layouts.employee')

@section('breadcrumb')
    Select Product Variations
@endsection

@section('main-content')
    <div class="p-1">
        <h3>Select Variations</h3>
        <hr>
        @if ($productVariations != null && sizeof($productVariations) > 0)
            <form action="{{ route('employee.stocks.order') }}" method="post">
                @csrf
                <table class="table table-striped border">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Width</th>
                            <th>Length</th>
                            <th>MRP</th>
                            <th>Selling Price</th>
                            <th>Select Variation</th>
                            <th>Order Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productVariations as $variation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $variation->product->name }}</td>
                                <td>{{ $variation->size ?? '--' }}</td>
                                <td>{{ $variation->color ?? '--' }}</td>
                                <td>{{ $variation->weight ?? '--' }}</td>
                                <td>{{ $variation->height ?? '--' }}</td>
                                <td>{{ $variation->width ?? '--' }}</td>
                                <td>{{ $variation->length ?? '--' }}</td>
                                <td>{{ $variation->MRP }}</td>
                                <td>{{ $variation->price }}</td>
                                <td>
                                    <input type="checkbox" name="selected_variations[]" value="{{ $variation->id }}"
                                        class="form-check-input">
                                </td>
                                <td>
                                    <input type="number" name="ordered_quantity[]" class="form-control w-25">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    <input type="submit" value="Place Order" class="btn btn-dark">
                </div>
            </form>
            @else
            <p class="text-danger text-center">Failed to get variations, please try again</p>
        @endif
    </div>
@endsection
