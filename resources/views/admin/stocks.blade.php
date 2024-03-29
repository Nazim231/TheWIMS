@extends('layouts.main')

@section('breadcrumb')
    Stocks
@endsection

@section('main-content')
    <div class="text-end">
        <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addStockModal">
            Add stock
        </button>
    </div>

    {{-- Add Shop Modal --}}
    <div class="modal fade" id="addStockModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addStockLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addStockModal">Add Stock</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post" class="d-flex flex-column gap-4">
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control mt-2">
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category" id="category" class="form-select mt-2">
                                <option value="0" selected>Select Product Category</option>
                                {{-- TODO :: Get the Categories from the database and show them here --}}
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-ouline" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="Add Product" class="btn btn-dark">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (sizeof($stocks) > 0)
        <table class="table table-striped">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
            </thead>
            <tbody>
                @foreach ($stocks as $stockItem)
                    <tr>
                        <td>{{ $stockItem->id }}</td>
                        <td>{{ $stockItem->name }}</td>
                        <td>{{ $stockItem->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-center text-muted">Warehouse is empty</p>
        @endif
    @endsection
