@extends('layouts.admin')
@section('title', 'QR Table Management')
@section('content')

    <div class="side-app tm-admin-page-shell">
        <div class="row">
            <div class="col-12 col-sm-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title mb-0">QR Table Management</h3>
                    </div>
                    <div class="card-body">
                        <div class="tm-page">
                            {{-- ── Stat Cards ─────────────────────────────────────────────────────── --}}
                            <div class="tm-stats-row">
                                <div class="tm-stat-card">
                                    <div class="tm-stat-label">Total Tables</div>
                                    <div class="tm-stat-value">{{ $tables->count() }}</div>
                                    <div class="tm-stat-sub">tables registered</div>
                                </div>
                                <div class="tm-stat-card">
                                    <div class="tm-stat-label">QR Available</div>
                                    <div class="tm-stat-value">{{ $tables->count() }}</div>
                                    <div class="tm-stat-sub">codes generated</div>
                                </div>
                            </div>

                            {{-- ── Main Layout ─────────────────────────────────────────────────────── --}}
                            <div class="tm-layout">

                                {{-- Add Table Panel --}}
                                <div class="tm-panel">
                                    <div class="tm-panel-title">
                                        <i class="bi bi-plus-circle"></i> Add Table Automatically
                                    </div>

                                    @php
                                        $existingNumbers = $tables
                                            ->pluck('table_number')
                                            ->map(function ($item) {
                                                return (int) preg_replace('/[^0-9]/', '', $item);
                                            })
                                            ->toArray();

                                        $nextNumber = 1;
                                        while (in_array($nextNumber, $existingNumbers)) {
                                            $nextNumber++;
                                        }

                                        $recommendedName = 'Table ' . sprintf('%02d', $nextNumber);
                                    @endphp

                                    <form action="{{ route('tables.store') }}" method="POST">
                                        @csrf

                                        <div class="tm-field-wrap">
                                            <label class="tm-field-label" for="table_number">Next Table Number /
                                                Name</label>

                                            <input type="text" id="table_number" name="table_number"
                                                class="tm-input @error('table_number') tm-input-error @enderror"
                                                value="{{ $recommendedName }}" readonly
                                                style="background-color: #f3f4f6; cursor: not-allowed; font-weight: bold;">

                                            @error('table_number')
                                                <div class="tm-error-msg">
                                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button type="submit" class="tm-btn-primary" style="width: 100%;">
                                            <i class="bi bi-plus-lg"></i> Click to Add {{ $recommendedName }}
                                        </button>
                                    </form>

                                    <div class="tm-info-box">
                                        <i class="bi bi-info-circle"></i>
                                        The system automatically detects missing table numbers and fills them in to keep the
                                        sequence in order.
                                    </div>
                                </div>

                                {{-- Table List --}}
                                <div class="tm-right-col">
                                    <div class="tm-right-header">
                                        <div class="tm-right-title">
                                            <i class="bi bi-grid-3x3-gap"></i> Active Table List
                                        </div>
                                    </div>

                                    <div class="tm-cards-grid" id="cardsGrid">
                                        @forelse($tables as $table)
                                            <div class="tm-table-card table-card-item"
                                                data-name="{{ strtolower($table->table_number) }}">

                                                <div class="tm-card-header">
                                                    <div class="tm-card-name">{{ $table->table_number }}</div>
                                                    <div class="tm-status-dot">
                                                        <span class="tm-dot"></span>Active
                                                    </div>
                                                </div>

                                                <div class="tm-qr-area">
                                                    <div id="qr-container-{{ $table->id }}">
                                                        {!! QrCode::size(130)->margin(0)->generate(rtrim(config('app.url'), '/') . route('customer-menu.index', ['token' => $table->qr_token], false)) !!}
                                                    </div>
                                                </div>

                                                <div class="tm-card-footer">
                                                    <button
                                                        onclick="printQR('{{ $table->table_number }}', 'qr-container-{{ $table->id }}')"
                                                        class="tm-action-btn">
                                                        <i class="bi bi-printer"></i> Print
                                                    </button>
                                                    <div class="tm-vr"></div>
                                                    <button type="submit" form="delete-form-{{ $table->id }}"
                                                        class="tm-action-btn tm-action-danger">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </div>

                                                {{-- Form --}}
                                                <form id="delete-form-{{ $table->id }}"
                                                    action="{{ route('tables.destroy', $table->id) }}" method="POST"
                                                    class="delete-form" data-type="Table {{ $table->table_number }}"
                                                    style="display:none;">
                                                    @csrf @method('DELETE')
                                                </form>

                                            </div>
                                        @empty
                                            <div class="tm-empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <p>No active tables registered yet.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
