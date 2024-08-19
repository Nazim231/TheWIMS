@extends('layouts.employee')

@section('breadcrumb')
    Sell History
@endsection

@section('main-content')
    <p class="h4 mt-4">Sell History/Invoices</p>
    <hr>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (sizeof($invoices ?? []) > 0)
        <table class="table table-striped table-bordered">
            <thead>
                <th>#</th>
                <th>Customer Name</th>
                <th>Customer Mobile</th>
                <th>No. of Items</th>
                <th>Amount</th>
                <th>Date of Purchase</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($invoices as $invoice)
                    <tr class="cursor-pointer"
                        onclick="window.location.href = '{{ route('employee.invoices.get', $invoice->id) }}'">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $invoice->customer_name }}</td>
                        <td>{{ $invoice->customer_mobile }}</td>
                        <td>{{ $invoice->products_count }}</td>
                        <td>{{ $invoice->total_amount }}</td>
                        <td>{{ $invoice->created_at }}</td>
                        <td><a href="{{ route('employee.invoices.print', $invoice->id) }}"><i class="fa-solid fa-print text-secondary"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center text-muted fst-italic">No Transactions Found</p>
    @endif
@endsection
