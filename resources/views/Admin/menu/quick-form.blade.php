@extends('layouts.admin')

@section('content')
@php
    $priceValue = old('price', $menuItem->price);
@endphp
<div class="side-app">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h3 class="card-title mb-0">Quick Edit Menu</h3>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Validation failed.</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-2">
                            @if ($menuItem->image_url)
                                @php
                                    $imageUrl = $menuItem->image_url;

                                    if (\Illuminate\Support\Str::startsWith($imageUrl, ['http://', 'https://'])) {
                                        $imageSrc = $imageUrl;
                                    } elseif (\Illuminate\Support\Str::startsWith($imageUrl, 'storage/')) {
                                        $imageSrc = asset($imageUrl);
                                    } else {
                                        $imageSrc = asset('storage/' . $imageUrl);
                                    }
                                @endphp

                                <img
                                    src="{{ $imageSrc }}"
                                    alt="{{ $menuItem->name }}"
                                    width="100"
                                    height="100"
                                    style="object-fit: cover; border-radius: 8px;"
                                >
                            @else
                                <div class="text-muted">No image</div>
                            @endif
                        </div>

                        <div class="col-md-10">
                            <h4 class="mb-1">{{ $menuItem->name }}</h4>
                            <p class="text-muted mb-1">{{ $menuItem->category->name ?? '-' }}</p>
                            <p class="mb-0">{{ $menuItem->description ?? '-' }}</p>
                        </div>
                    </div>

                    <form
                        action="{{ route('menu.quick-update', $menuItem) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Price</label>

                                    <input
                                        type="text"
                                        class="form-control price-format @error('price') is-invalid @enderror"
                                        value="{{ $priceValue ? number_format((float) $priceValue, 0, ',', '.') : '' }}"
                                        placeholder="25.000"
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

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Stock</label>
                                    <input
                                        type="number"
                                        name="stock"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock', $menuItem->stock) }}"
                                        min="0"
                                    >

                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="text-muted">
                                        If stock is 0, menu will be unavailable automatically.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">Available Status</label>
                                    <div>
                                        @if ($menuItem->is_available)
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check mb-4">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        class="form-check-input"
                                        id="is_active"
                                        {{ old('is_active', $menuItem->is_active) ? 'checked' : '' }}
                                    >

                                    <label class="form-check-label" for="is_active">
                                        Menu Active
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    Update Menu
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