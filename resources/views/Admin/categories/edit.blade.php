@extends('layouts.admin')
@section('content')
<div class="side-app">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="sto-child-nav mt-5">
                <ol class="sto-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li><span class="active">Edit Category</span></li>
                </ol>
                <a href="{{ route('categories.index') }}" class="sto-page-back">
                    <i class="fe fe-arrow-left"></i> Kembali ke Categories
                </a>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Edit Category</h3>
                </div>
                <div class="card-body">
             <form
        action="{{ route('categories.update',$category) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label>Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name',$category->name) }}">

            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>

        <div class="mb-3">

            <label>Description</label>

            <textarea
                name="description"
                class="form-control">{{ old('description',$category->description) }}</textarea>

        </div>

        <button class="btn btn-primary">
            Update
        </button>

        <a href="{{ route('categories.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
