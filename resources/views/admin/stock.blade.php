@extends('layouts.admin')

@section('breadcrumb')
    Product Detail
@endsection

@vite('resources/js/stock.js')

@section('main-content')
    {{-- @if($errors) 
        @php
            echo( $errors)
        @endphp
    @endif --}}
    <p class="display-3">{{ $product->name }} <span class="fs-5">(Id: {{ $product->id }})</span></p>
    <hr>
    <div class="container-fluid my-4 py-4">
        <div class="row">
            <div class="col p-2">
                <span class="fw-bold me-2">Quantity:</span>
                {{ $product->variants_sum_quantity }}
            </div>
            <div class="col p-2">
                <span class="fw-bold me-2">Variants:</span>
                {{ sizeof($product->variants) }} <span>Variants</span>
            </div>
        </div>
        <div class="row">
            <div class="col p-2">
                <span class="fw-bold me-2">Brand:</span>
                {{ $product->brand->name }}
            </div>
            <div class="col p-2">
                <span class="fw-bold me-2">Category:</span>
                {{ $product->category->name }}
            </div>
        </div>
        <div class="row">
            <div class="col p-2">
                <span class="fw-bold me-2">Added On:</span>
                {{ $product->created_at }}
            </div>
            <div class="col p-2">
                <span class="fw-bold me-2">Last Update:</span>
                {{ $product->updated_at }}
            </div>
        </div>
    </div>
    <hr>
    @if ($errors->any())
        <div class="alert alert-danger my-2">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @enderror
    <div class="px-2">
        <div class="d-flex justify-content-between align-items-center">
            <p class="fs-3 mt-4">Product Variations</p>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#addVariationModal"><span class="fs-5 me-2">+</span>Add More Variation</button>
        </div>
        <div class="modal fade" id="addVariationModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
            aria-labelledby="addVariationLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addVariationLabel">Add More Variation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.stocks.product.add') }}" method="post" id="addVariationForm" class="d-flex flex-column gap-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
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
                            <button type="button" class="btn btn-primary" id="generateVTable">Generate Variations
                                Table</button>
                            <div id="variationContainer"></div>
                            <div class="text-end pt-2">
                                <button type="button" class="btn btn-ouline" data-bs-dismiss="modal">Close</button>
                                <input type="submit" value="Add Variation" class="btn btn-dark">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    <th>MRP</th>
                    <th>Price</th>
                    <th>Cost Price</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Height</th>
                    <th>Width</th>
                    <th>Length</th>
                    <th>Added On</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($product->variants as $variation)
                    <tr>
                        <td>{{ $variation->id }}</td>
                        <td>{{ $variation->SKU }}</td>
                        <td>{{ $variation->quantity }}</td>
                        <td>{{ $variation->MRP }}</td>
                        <td>{{ $variation->price }}</td>
                        <td>{{ $variation->cost_price }}</td>
                        <td>{{ $variation->size ?? '--' }}</td>
                        <td>{{ $variation->color ?? '--' }}</td>
                        <td>{{ $variation->height ?? '--' }}</td>
                        <td>{{ $variation->width ?? '--' }}</td>
                        <td>{{ $variation->length ?? '--' }}</td>
                        <td>{{ $variation->created_at }}</td>
                        <td>{{ $variation->updated_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
