@extends('layouts.admin')

@section('breadcrumb')
Home
@endsection

@section('main-content')
<div class="row gap-4">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <p class="h1 text-primary text-end">0</p>
                <p class="text-muted text-end">Monthly Products sold</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-body">
                <p class="h1 text-success text-end">0</p>
                <p class="text-muted text-end">Monthly profit earned</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card">
            <div class="card-body">
                <p class="h1 text-warning text-end">0</p>
                <p class="text-muted text-end">Monthly revenue earned</p>
            </div>
        </div>
    </div>
</div>
@endsection