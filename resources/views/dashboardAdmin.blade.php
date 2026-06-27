@extends('layouts.admin')
@section('content')
<div class="side-app">
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard 01</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard 01</li>
            </ol>
        </div>
        <div class="ms-auto pageheader-btn">
            <a href="#" class="btn btn-primary btn-icon text-white me-2">
                <span>
                    <i class="fe fe-plus"></i>
                </span> Add Account
            </a>
            <a href="#" class="btn btn-success btn-icon text-white">
                <span>
                    <i class="fe fe-log-in"></i>
                </span> Export
            </a>
        </div>
    </div>
    <!-- END PAGE-HEADER -->
</div>
@endsection