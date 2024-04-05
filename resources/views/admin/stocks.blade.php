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

    {{-- Error Showing --}}
    @if ($errors->any())
        <div class="alert alert-danger my-2">
            @foreach ($errors->all() as $error)
                <span>{{ $error }}</span><br>
            @endforeach
        </div>
    @enderror

    {{-- Add Shop Modal --}}
    <div class="modal fade" id="addStockModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addStockLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addStockModal">Add Stock</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Add Product Form --}}
                    {{-- TODO :: Change the form to the Multi-Step Form for better User Accessibility --}}
                    <form action="{{ route('stocks.add') }}" method="post" class="d-flex flex-column gap-4"
                        id="addProductForm">
                        @csrf
                        {{-- input product name --}}
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control mt-2"
                                value="{{ old('name') }}">
                        </div>
                        <div class="d-flex gap-4 align-items-center">
                            {{-- Category --}}
                            <div class="form-group flex-grow-1">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-select mt-2">
                                    <option value="0" {{ old('category') == 0 ? 'selected' : '' }}>Select Product
                                        Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Brand --}}
                            <div class="form-group flex-grow-1">
                                <label for="brand">Brand</label>
                                <select name="brand" id="brand" class="form-select mt-2">
                                    <option value="0" {{ old('brand') == 0 ? 'selected' : '' }}>Select Product
                                        Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Checkbox (Add Variation) --}}
                        <div class="form-check">
                            <input type="checkbox" name="variation" id="variation" class="form-check-input"
                                {{ old('variation') == 'on' ? 'checked' : '' }}>
                            <label for="variation" class="form-check-label">Product have variation</label>
                        </div>
                        {{-- Variation Container --}}
                        <div id="variation-container" class="row">
                            {{-- dropdown select variation --}}
                            <div class="form-group col-7">
                                <label for="variation_type">Variation Type</label>
                                <select name="variation_type" id="variation_type" class="form-select mt-2">
                                    <option value="0" {{ old('variation') == 0 ? 'selected' : '' }}>Select
                                        Variation Type</option>
                                    <option value="size" {{ old('variation') == 'size' ? 'selected' : '' }}>Size
                                    </option>
                                    <option value="color" {{ old('variation') == 'color' ? 'selected' : '' }}>Color
                                    </option>
                                </select>
                            </div>
                            {{-- input no. of variations --}}
                            <div class="form-group col-5">
                                <label for="variation_numbers">No. of Variations</label>
                                <input type="number" name="variation_numbers" id="variation_numbers"
                                    class="form-control mt-2" value = "{{ old('variation_numbers') }}">
                            </div>
                            {{-- Checkbox (Add Sub Variation) --}}
                            <div class="form-check mt-4 ms-3">
                                <input type="checkbox" name="sub_variation" id="sub_variation" class="form-check-input"
                                    {{ old('sub_variation') == 'on' ? 'checked' : '' }}>
                                <label for="sub_variation" class="form-check-label">Product have sub variations</label>
                            </div>
                        </div>
                        {{-- Sub Variation Container --}}
                        <div id="sub-variation-container" class="row">
                            {{-- dropdown select sub variation --}}
                            <div class="form-group col-7">
                                <label for="sub_variation_type">Sub Variation Type</label>
                                <select name="sub_variation_type" id="sub_variation_type" class="form-select mt-2">
                                    <option value="0" {{ old('sub_variation') == 0 ? 'selected' : '' }}>Select Sub
                                        Variation Type</option>
                                    <option value="size" {{ old('sub_variation') == 'size' ? 'selected' : '' }}>Size
                                    </option>
                                    <option value="color" {{ old('sub_variation') == 'color' ? 'selected' : '' }}>
                                        Color
                                    </option>
                                </select>
                            </div>
                            {{-- input no. of sub variations --}}
                            <div class="form-group col-5">
                                <label for="sub_variation_numbers">No. of Sub Variation</label>
                                <input type="number" name="sub_variation_numbers" id="sub_variation_numbers"
                                    class="form-control mt-2" value="{{ old('sub_variation_numbers') }}">
                            </div>
                        </div>
                        {{-- Generate Variations Table Button --}}
                        <button type="button" class="btn btn-primary" id="generateVTable">Generate Variations
                            Table</button>

                        {{-- Variation Table Container --}}
                        <div id="variationContainer">
                            {{-- ! When page is reloaded back with error the variations details doesn't exists, work over this --}}
                        </div>
                        {{-- Buttons --}}
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
        <table class="table table-bordered table-striped mt-4">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Brand</th>
                <th>Category</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach ($stocks as $stockItem)
                    <tr>
                        <td>{{ $stockItem->id }}</td>
                        <td>{{ $stockItem->name }}</td>
                        <td>{{ $stockItem->variants_sum_quantity }}</td>
                        <td>{{ $stockItem->brand->name }}</td>
                        <td>{{ $stockItem->category->name }}</td>
                        <td><a href="{{route('stocks')}}" class="btn btn-link text-success text-decoration-none p-0">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted">Warehouse is empty</p>
    @endif
@endsection
