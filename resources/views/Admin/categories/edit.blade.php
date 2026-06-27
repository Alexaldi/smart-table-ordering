@extends('layouts.admin')
@section('content')
<div class="side-app">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card mt-5">
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