@extends('layouts.admin')

@section('breadcrumb')
    Employees
@endsection

@section('main-content')
    {{-- Add employee button --}}
    <div class="text-end">
        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#addEmpModal">Add new
            employee</button>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger my-2">
            @foreach ($errors->all() as $error)
                <span>{{ $error }}</span><br>
            @endforeach
        </div>
    @enderror

    @if (session('create_emp_status'))
        @php
            $status = session('create_emp_status')['status'];
            $message = session('create_emp_status')['message'];
        @endphp
        <div class="mt-4 alert alert-{{ $status == 0 ? 'danger' : 'success' }}">
            <span>{{ $message }}</span><br>
            @if ($status == 1)
                <span>Password:
                    <span class="fw-bold">
                        {{ session('create_emp_status')['password'] }}
                    </span>
                </span>
            @endif
        </div>
    @endif
    <div class="modal fade" id="addEmpModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="addEmpLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addEmpLabel">Add new employee</h1>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('employees.add') }}" method="post" class="d-flex flex-column gap-4">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control mt-2"
                                value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control mt-2"
                                value="{{ old('email') }}">
                        </div>
                        <div class="text-end">
                            <button class="btn btn-outline" type="button" data-bs-dismiss="modal">Close</button>
                            <input type="submit" value="Add Employee" class="btn btn-dark">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (sizeof($employees) > 0)
        <table class="table table-striped table-hover mt-4">
            <thead>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
            </thead>
            <tbody class="text-muted">
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted">No employees found</p>
    @endif
@endsection
