@extends('layouts.admin')

@section('breadcrumb')
    Home
@endsection

@section('main-content')
    <div class="page-title">
        <h1>Hi, {{ Auth::user()->name }}</h1>
        <h3>Let's have a quick look over the warehouse</h3>
    </div>
    <div class="m-card-group">
        <div class="m-card">
            <div class="m-card-header">
                <h3>Item Sold</h3>
                <span class="time-period-filter">(This week)</span>
            </div>
            <div class="m-card-body">
                <p>{{ $item_sold->current }}</p>
            </div>
            <div class="m-card-footer">
                <x-profit-loss-percent value="{{ $item_sold->first_period_percent }}" period="Week" />
                <x-profit-loss-percent value="{{ '+0.0' }}" period="Month" />
            </div>
        </div>
        <div class="m-card">
            <div class="m-card-header">
                <h3>Total Expenses</h3>
                <span class="time-period-filter">(This week)</span>
            </div>
            <div class="m-card-body">
                <p>{{ 'Rs. ' . number_format($expense->current, 1) }}</p>
            </div>
            <div class="m-card-footer">
                <x-profit-loss-percent value="{{ $expense->first_period_percent }}" period="Week" />
                <x-profit-loss-percent value="{{ '+0.0' }}" period="Month" />
            </div>
        </div>
        <div class="m-card">
            <div class="m-card-header">
                <h3>Est. Revenue</h3>
                <span class="time-period-filter">(This week)</span>
            </div>
            <div class="m-card-body">
                <p>{{ 'Rs. ' . number_format($revenue->current, 1) }}</p>
            </div>
            <div class="m-card-footer">
                <x-profit-loss-percent value="{{ $revenue->first_period_percent }}" period="Week" />
                <x-profit-loss-percent value="{{ '+0.0' }}" period="Month" />
            </div>
        </div>
        <div class="m-card">
            <div class="m-card-header">
                <h3>Profit</h3>
                <span class="time-period-filter">(This Week)</span>
            </div>
            <div class="m-card-body">
                <p>{{ 'Rs. ' . $profit->current }}</p>
            </div>
            <div class="m-card-footer">
                <x-profit-loss-percent value="{{ $profit->first_period_percent }}" period="Week" />
                <x-profit-loss-percent value="{{ '+0.0' }}" period="Month" />
            </div>
        </div>
    </div>

    <div>
        <h2 class="section-heading">Trending Products</h2>
        <div class="table-container">
            <table class="table table-stripped">
                <thead>
                    <th>S. No</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Sales</th>
                    <th>Cost Price</th>
                    <th>Profit</th>
                    <th>Sales Week</th>
                </thead>
                <tbody>
                    <tr></tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
