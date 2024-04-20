@extends('layouts.employee')

@section('breadcrumb')
    Select Products
@endsection

@section('main-content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="p-1">
        <p class="display-4 m-0">Select Products</p>
        <hr>
        @if (sizeof($products) > 0)
            <form action="{{ route('employee.stocks.request.variations') }}" method="post">
                @csrf
                <table class="table table-striped border">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <input type="checkbox" name="selected_products[]" class="form-check-input"
                                        value="{{ $product->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end">
                    <input type="submit" value="Next" class="btn btn-dark">
                </div>
            </form>
        @else
            <p class="text-center text-danger">Warehouse is currently empty</p>
        @endif
    </div>
@endsection
