@extends('layouts.admin')

@push('styles')
<style>
    .sto-admin-page { padding: 2rem 1.5rem; max-width: 100%; overflow-x: hidden; }
    .sto-page-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
    .sto-title-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .sto-title-icon { width: 42px; height: 42px; border-radius: 10px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; }
    .sto-title-icon i { font-size: 20px; }
    .sto-page-title { font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 4px; }
    .sto-page-subtitle { color: #6b7280; font-size: 13px; margin: 0; }
    .sto-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(15,23,42,.04); overflow: hidden; }
    .sto-card-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem; border-bottom: 1px solid #f3f4f6; }
    .sto-search { position: relative; width: min(100%, 320px); }
    .sto-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; }
    .sto-input { width: 100%; height: 38px; background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 12px 0 34px; font-size: 13px; color: #111827; outline: none; }
    .sto-input:focus { background: #fff; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
    .sto-table-wrap { overflow-x: auto; }
    .sto-table { width: 100%; margin: 0; color: #111827; }
    .sto-table th { color: #6b7280; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; font-weight: 700; background: #f9fafb; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
    .sto-table td { vertical-align: middle; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
    .sto-shift-name { font-weight: 700; color: #111827; }
    .sto-count { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; height: 28px; border-radius: 999px; background: #eff6ff; color: #1e40af; font-weight: 800; }
    .sto-actions { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .sto-empty { padding: 2.5rem 1rem; text-align: center; color: #6b7280; }
    .sto-alert { border-radius: 10px; border: 1px solid transparent; padding: .85rem 1rem; margin-bottom: 1rem; font-size: 13px; font-weight: 600; }
    .sto-alert.success { background: #dcfce7; border-color: #bbf7d0; color: #166534; }
    .sto-alert.error { background: #fee2e2; border-color: #fecaca; color: #991b1b; }
    .sto-pagination { padding: 1rem; border-top: 1px solid #f3f4f6; }
    @media (max-width: 767.98px) {
        .sto-admin-page { padding: 1rem .75rem; }
        .sto-page-head, .sto-card-toolbar { align-items: stretch; flex-direction: column; }
        .sto-search { width: 100%; }
        .sto-page-head .btn { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="side-app">
    <div class="sto-admin-page">
        <div class="sto-page-head">
            <div class="sto-title-wrap">
                <div class="sto-title-icon"><i class="fe fe-clock"></i></div>
                <div>
                    <h1 class="sto-page-title">Shift Management</h1>
                    <p class="sto-page-subtitle">Atur jadwal kerja staf restoran.</p>
                </div>
            </div>
            <a href="{{ route('shifts.create') }}" class="btn btn-primary btn-icon text-white">
                <span><i class="fe fe-plus"></i></span> Tambah Shift
            </a>
        </div>

        <div class="sto-card">
            <div class="sto-card-toolbar">
                <form method="GET" action="{{ route('shifts.index') }}" class="sto-search">
                    <i class="fe fe-search"></i>
                    <input type="search" name="search" value="{{ $search }}" class="sto-input" placeholder="Cari nama shift">
                </form>
                @if($search)
                    <a href="{{ route('shifts.index') }}" class="btn btn-light btn-sm">Reset</a>
                @endif
            </div>

            <div class="sto-table-wrap">
                <table class="table sto-table text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Nama Shift</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Jumlah Staf</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td><span class="sto-shift-name">{{ $shift->name }}</span></td>
                                <td>{{ substr($shift->start_time, 0, 5) }}</td>
                                <td>{{ substr($shift->end_time, 0, 5) }}</td>
                                <td><span class="sto-count">{{ $shift->users_count }}</span></td>
                                <td>
                                    <div class="sto-actions">
                                        <a href="{{ route('shifts.edit', $shift) }}" class="btn btn-primary btn-sm">
                                            <i class="fe fe-edit-2"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('shifts.destroy', $shift) }}" class="mb-0 delete-form" data-type="shift {{ $shift->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fe fe-trash-2"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="sto-empty">Belum ada shift yang cocok.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shifts->hasPages())
                <div class="sto-pagination">
                    {{ $shifts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
