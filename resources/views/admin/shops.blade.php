@extends('layouts.admin')

@section('breadcrumb')
    Shops
@endsection

@section('main-content')
    {{-- Add shop button --}}
    <div class="mt-3 d-flex align-items-center justify-content-between">
        <p class="h4 fw-semibold">Shops</p>
        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addShopModal">
            Add new shop
        </button>
    </div>

    {{-- Shop form modal --}}
    {{-- ! Model gets hidden even if there is error for the fields --}}
    <div class="modal fade" id="addShopModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addShopLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addShopLabel">Add new shop</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.shops.add') }}" method="POST" class="d-flex flex-column gap-4">
                        @csrf
                        <div class="form-group">
                            <label for="name">Shop Name</label>
                            <input type="text" name="name" id="name" class="form-control mt-2"
                                placeholder="New Shop Name" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="address">Shop Address</label>
                            <input type="text" name="address" id="address" class="form-control mt-2"
                                placeholder="Shop Address" value="{{ old('address') }}">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="Add Shop" class="btn btn-dark">
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @if (sizeof($shops) > 0)
        <table class="table table-striped table-bordered table-hover mt-4 cursor-default">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Assigned Employee</th>
                <th>Address</th>
            </thead>
            <tbody>
                @foreach ($shops as $shop)
                    <tr>
                        <td>{{ $shop->id }}</td>
                        <td>{{ $shop->name }}</td>
                        <td>{!! $shop->shopOwner->name ?? '<b>Not Assigned</b>' !!}</td>
                        <td>{{ $shop->address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted text-center">No Shops Found</p>
    @endif
@endsection
