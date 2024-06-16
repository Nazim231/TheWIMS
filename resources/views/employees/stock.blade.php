@extends('layouts.employee')

@section('breadcrumb')
    Stock Details - {{ $productData->name }}
@endsection

@section('main-content')
    <p class="h4 mt-4">{{ $productData->name }}</p>
    <hr class="mb-4">
    <p class="h5">Variations</p>
    <table class="table table-striped table-bordered">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Quantity</th>
            <th>Last Placed Order</th>
        </thead>
        <tbody>
            @foreach ($productData->variations as $variation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $variation->name }}</td>
                    <td>{{ $variation->sku }}</td>
                    <td>
                        {{ $variation->quantity }}
                        @if ($variation->quantity == 0)
                            <span class="badge text-bg-danger ms-2 fw-normal">Out of stock</span>
                        @endif
                    </td>
                    <td>{{ $variation->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
