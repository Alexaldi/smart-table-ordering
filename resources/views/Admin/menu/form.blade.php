@extends('layouts.admin')

@section('content')
@php
    $isEdit = $menuItem->exists;
    $priceValue = old('price', $menuItem->price);
@endphp

<div class="side-app">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        {{ $isEdit ? 'Edit Menu Item' : 'Create Menu Item' }}
                    </h3>
                </div>

                <div class="card-body">
                    <form
                        action="{{ $isEdit ? route('menu.update', $menuItem) : route('menu.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Category</label>

                                    <div class="select-wrapper">
                                        <select name="category_id" class="form-control custom-select-arrow @error('category_id') is-invalid @enderror">
                                            <option value="" disabled {{ old('category_id', $menuItem->category_id) ? '' : 'selected' }}>
                                                Select Category
                                            </option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $menuItem->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @error('category_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Menu Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $menuItem->name) }}"
                                        placeholder="Example: Ice Latte"
                                    >

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea
                                        name="description"
                                        rows="3"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="Enter menu description"
                                    >{{ old('description', $menuItem->description) }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Price</label>

                                    <input
                                        type="text"
                                        class="form-control price-format @error('price') is-invalid @enderror"
                                        value="{{ $priceValue ? number_format((float) $priceValue, 0, ',', '.') : '' }}"
                                        placeholder="0"
                                        inputmode="numeric"
                                    >

                                    <input
                                        type="hidden"
                                        name="price"
                                        class="price-value"
                                        value="{{ $priceValue }}"
                                    >

                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Estimated Minutes</label>
                                    <input
                                        type="number"
                                        name="estimated_minutes"
                                        class="form-control @error('estimated_minutes') is-invalid @enderror"
                                        value="{{ old('estimated_minutes', $menuItem->estimated_minutes) }}"
                                        placeholder="0"
                                        min="1"
                                    >

                                    @error('estimated_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Menu Image</label>
                                    <input
                                        type="file"
                                        name="image"
                                        id="imageInput"
                                        class="form-control @error('image') is-invalid @enderror"
                                        accept="image/png, image/jpg, image/jpeg, image/webp"
                                    >

                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="text-muted">
                                        Upload only 1 image. Allowed format: JPG, JPEG, PNG, WEBP.
                                    </small>

                                    <div class="mt-3">
                                        <img
                                            id="imagePreview"
                                            src="{{ $isEdit && $menuItem->image_url ? asset('storage/' . $menuItem->image_url) : '' }}"
                                            alt="Image Preview"
                                            width="140"
                                            height="140"
                                            style="object-fit: cover; border-radius: 8px; {{ $isEdit && $menuItem->image_url ? '' : 'display: none;' }}"
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ $isEdit ? 'Update Menu' : 'Save Menu' }}
                                </button>

                                <a href="{{ route('menu.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            imagePreview.style.display = 'none';
            imagePreview.src = '';
            return;
        }

        imagePreview.src = URL.createObjectURL(file);
        imagePreview.style.display = 'block';
    });

    document.querySelectorAll('.price-format').forEach(function (input) {
        const hiddenInput = input.parentElement.querySelector('.price-value');

        function formatPrice(value) {
            let number = value.replace(/\D/g, '');

            if (!number) {
                hiddenInput.value = '';
                return '';
            }

            hiddenInput.value = number;

            return new Intl.NumberFormat('id-ID').format(number);
        }

        input.addEventListener('input', function () {
            input.value = formatPrice(input.value);
        });

        if (input.value) {
            input.value = formatPrice(input.value);
        }
    });
</script>
@endsection