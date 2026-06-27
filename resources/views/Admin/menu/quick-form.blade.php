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

                            <div class="col-md-12 mt-4">
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

                {{-- DISCOUNT SECTION --}}
                <div class="col-md-12 mb-5">
                    <hr>
                    <h6 class="mb-3">Discount</h6>

                    @php
                        // Hapus otomatis diskon yang sudah expired
                        $menuItem->menuDiscounts()
                            ->whereHas('discount', fn($q) => $q->where('end_date', '<', now()))
                            ->each(function ($md) {
                                $md->discount->delete();
                                $md->delete();
                            });

                        $activeDiscount = $menuItem->menuDiscounts()
                            ->with('discount')
                            ->whereHas('discount', fn($q) => $q->where('end_date', '>=', now()))
                            ->first();
                    @endphp

                    @if ($activeDiscount)
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <span class="badge bg-warning text-dark fs-6">
                                    {{ $activeDiscount->discount->percentage }}% OFF
                                </span>
                                <span class="ms-2 text-muted small">
                                    {{ $activeDiscount->discount->name }}
                                    &bull;
                                    @if ($activeDiscount->discount->start_date->isFuture())
                                        Mulai {{ $activeDiscount->discount->start_date->format('d M Y') }}
                                        s/d {{ $activeDiscount->discount->end_date->format('d M Y') }}
                                    @else
                                        s/d {{ $activeDiscount->discount->end_date->format('d M Y') }}
                                    @endif
                                </span>
                            </div>

                            <form
                                action="{{ route('menu.discount.destroy', [$menuItem, $activeDiscount->discount]) }}"
                                method="POST"
                                class="delete-form" data-type="Discount"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash-alt me-1"></i> Hapus Diskon
                                </button>
                            </form>
                        </div>
                    @else
                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalTambahDiskon"
                        >
                            <i class="fe fe-percent me-1"></i> Tambah Diskon
                        </button>
                    @endif
                </div>

                {{-- MODAL TAMBAH DISKON --}}
                <div class="modal fade" id="modalTambahDiskon" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Diskon — {{ $menuItem->name }}</h5>
                                <button type="button" class="btn-close btn-close" data-bs-dismiss="modal"><i class="fa fa-close"></i></button>
                            </div>

                            <form action="{{ route('menu.discount.store', $menuItem) }}" method="POST">
                                @csrf

                                <div class="modal-body">
                                    @if ($errors->hasBag('discount'))
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->getBag('discount')->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label">Nama Diskon</label>
                                        <input
                                            type="text"
                                            name="discount_name"
                                            class="form-control"
                                            placeholder="cth: Promo Weekend"
                                            value="{{ old('discount_name') }}"
                                            required
                                        >
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Persentase (%)</label>
                                        <div class="input-group">
                                            <input
                                                type="number"
                                                name="percentage"
                                                class="form-control"
                                                placeholder="10"
                                                min="1"
                                                max="100"
                                                step="0.01"
                                                value="{{ old('percentage') }}"
                                                required
                                            >
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Mulai</label>
                                                <input
                                                    type="datetime-local"
                                                    name="start_date"
                                                    class="form-control"
                                                    value="{{ old('start_date') }}"
                                                    id="start_date"
                                                    required
                                                >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Selesai</label>
                                                <input
                                                    type="datetime-local"
                                                    name="end_date"
                                                    class="form-control"
                                                    value="{{ old('end_date') }}"
                                                    required
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Diskon</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Set min start_date ke waktu sekarang
    document.getElementById('start_date').min = new Date().toISOString().slice(0, 16);

    @if ($errors->hasBag('discount'))
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('modalTambahDiskon'));
            modal.show();
        });
    @endif
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