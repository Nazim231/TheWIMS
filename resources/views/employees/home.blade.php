@extends('layouts.employee')

@section('breadcrumb')
Home
@endsection

@section('main-content')
    <h1>Hello, {{ auth()->user()->name }}</h1>
@endsection