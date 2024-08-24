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
                <p>0</p>
            </div>
            <div class="m-card-footer">
                <div class="profit">
                    <img src="{{ asset('images/icons/ic-profit.svg') }}" alt="">
                    <span>{{ '+0.0% Last Week' }}</span>
                </div>
                <div class="loss">
                    <img src="{{ asset('images/icons/ic-loss.svg') }}" alt="">
                    <span>{{ '-0.0% Last Month' }}</span>
                </div>
            </div>
        </div>
        <div class="m-card">
            <div class="m-card-header">
                <h3>Total Expenses</h3>
                <span class="time-period-filter">(This week)</span>
            </div>
            <div class="m-card-body">
                <p>0</p>
            </div>
            <div class="m-card-footer">
                <div class="profit">
                    <img src="{{ asset('images/icons/ic-profit.svg') }}" alt="">
                    <span>{{ '+0.0% Last Week' }}</span>
                </div>
                <div class="loss">
                    <img src="{{ asset('images/icons/ic-loss.svg') }}" alt="">
                    <span>{{ '-0.0% Last Month' }}</span>
                </div>
            </div>
        </div>
        <div class="m-card">
            <div class="m-card-header">
                <h3>Est. Revenue</h3>
                <span class="time-period-filter">(This week)</span>
            </div>
            <div class="m-card-body">
                <p>0</p>
            </div>
            <div class="m-card-footer">
                <div class="profit">
                    <img src="{{ asset('images/icons/ic-profit.svg') }}" alt="">
                    <span>{{ '+0.0% Last Week' }}</span>
                </div>
                <div class="loss">
                    <img src="{{ asset('images/icons/ic-loss.svg') }}" alt="">
                    <span>{{ '-0.0% Last Month' }}</span>
                </div>
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
