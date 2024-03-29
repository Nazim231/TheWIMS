@extends('layouts.main')

@section('breadcrumb')
    Categories
@endsection

@section('main-content')
    <div class="text-end">
        <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add new
            category</button>
    </div>
    @error('name')
        <div class="alert alert-danger">
            <span>{{ $message }}</span>
        </div>
    @enderror

    @if (session('add_category_status'))
        @php
            $status = session('add_category_status')['status'];
            $message = session('add_category_status')['message'];
        @endphp
        <div class="mt-4 alert alert-{{ $status == 0 ? 'danger' : 'success' }}">
            <span>{{ $message }}</span>
            @if ($status == 1)
                <br>
                <span>Category ID:
                    <span class="fw-bold">
                        {{ session('add_category_status')['id'] }}
                    </span>
                </span>
            @endif
        </div>
    @endif
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addCategoryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCategoryLabel">Add new category</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categories.add') }}" method="POST" class="d-flex flex-column gap-4">
                        @csrf
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="text-end">
                            <button class="btn btn-outline" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="Add Category" class="btn btn-dark">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (sizeof($categories) > 0)
        <table class="table table-stripped">
            <thead>
                <th>ID</th>
                <th>Name</th>
            </thead>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="text-center">No Categories Found</p>
    @endif
@endsection
