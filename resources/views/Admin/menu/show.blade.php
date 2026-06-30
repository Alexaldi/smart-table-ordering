@extends('layouts.admin')

@section('content')
<div class="side-app">
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="sto-child-nav mt-5">
                <ol class="sto-breadcrumb">
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('menu.index') }}">Menus</a></li>
                    <li><span class="active">Detail Menu</span></li>
                </ol>
                <a href="{{ route('menu.index') }}" class="sto-page-back">
                    <i class="fe fe-arrow-left"></i> Kembali ke Menus
                </a>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title mb-0">Menu Detail</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
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
                                    class="img-fluid"
                                    style="width: 100%; max-height: 320px; object-fit: cover; border-radius: 10px;"
                                >
                            @else
                                <div class="border rounded d-flex align-items-center justify-content-center text-muted" style="height: 260px;">
                                    No image
                                </div>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <h3 class="mb-2">{{ $menuItem->name }}</h3>

                            <p class="text-muted mb-4">
                                {{ $menuItem->description ?? 'No description.' }}
                            </p>

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tr>
                                        <th style="width: 220px;">Category</th>
                                        <td>{{ $menuItem->category->name ?? '-' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Price</th>
                                        <td>Rp {{ number_format($menuItem->price, 0, ',', '.') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Estimated Time</th>
                                        <td>
                                            {{ $menuItem->estimated_minutes ? $menuItem->estimated_minutes . ' minutes' : '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Stock</th>
                                        <td>{{ $menuItem->stock }}</td>
                                    </tr>

                                    <tr>
                                        <th>Available</th>
                                        <td>
                                            @if ($menuItem->is_available)
                                                <span class="badge bg-success">Available</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if ($menuItem->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $menuItem->created_at?->format('d M Y H:i') }}</td>
                                    </tr>

                                    <tr>
                                        <th>Updated At</th>
                                        <td>{{ $menuItem->updated_at?->format('d M Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mt-4">
                                <!-- <a href="{{ route('menu.edit', $menuItem) }}" class="btn btn-primary text-white">
                                    Edit All
                                </a>
                                <a href="{{ route('menu.quick-edit', $menuItem) }}" class="btn btn-warning text-white">
                                    Quick Edit
                                </a> -->

                                <a href="{{ route('menu.index') }}" class="btn btn-secondary">
                                    Back to Menu List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
