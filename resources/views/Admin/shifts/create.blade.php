@extends('layouts.admin')

@push('styles')
<style>
    .sto-form-page { padding: 2rem 1.5rem; max-width: 760px; }
    .sto-page-head { display: flex; align-items: center; gap: 12px; margin-bottom: 1.25rem; }
    .sto-title-icon { width: 42px; height: 42px; border-radius: 10px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; }
    .sto-page-title { font-size: 22px; font-weight: 700; color: #111827; margin: 0; }
    .sto-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(15,23,42,.04); padding: 1.25rem; }
    .sto-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
    .sto-field.full { grid-column: 1 / -1; }
    .sto-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: #6b7280; margin-bottom: 7px; }
    .sto-control { width: 100%; height: 42px; background: #f9fafb; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 12px; color: #111827; font-size: 14px; outline: none; }
    .sto-control:focus { background: #fff; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
    .sto-control.is-invalid { border-color: #dc2626; }
    .sto-error { color: #dc2626; font-size: 12px; margin-top: 6px; }
    .sto-actions { display: flex; justify-content: flex-end; gap: 10px; padding-top: 1.25rem; border-top: 1px solid #f3f4f6; margin-top: 1.25rem; }
    @media (max-width: 767.98px) {
        .sto-form-page { padding: 1rem .75rem; }
        .sto-grid { grid-template-columns: 1fr; }
        .sto-field.full { grid-column: auto; }
        .sto-actions { flex-direction: column-reverse; }
        .sto-actions .btn { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="side-app">
    <div class="sto-form-page">
        <div class="sto-child-nav">
            <ol class="sto-breadcrumb">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('shifts.index') }}">Shift Management</a></li>
                <li><span class="active">Tambah Shift</span></li>
            </ol>
            <a href="{{ route('shifts.index') }}" class="sto-page-back">
                <i class="fe fe-arrow-left"></i> Kembali ke Shifts
            </a>
        </div>

        <div class="sto-page-head">
            <div class="sto-title-icon"><i class="fe fe-plus"></i></div>
            <h1 class="sto-page-title">Tambah Shift</h1>
        </div>

        <form method="POST" action="{{ route('shifts.store') }}" class="sto-card">
            @csrf

            <div class="sto-grid">
                <div class="sto-field full">
                    <label for="name" class="sto-label">Nama Shift</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="sto-control @error('name') is-invalid @enderror" placeholder="Contoh: Pagi">
                    @error('name') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="start_time" class="sto-label">Jam Mulai</label>
                    <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" class="sto-control @error('start_time') is-invalid @enderror">
                    @error('start_time') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="end_time" class="sto-label">Jam Selesai</label>
                    <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" class="sto-control @error('end_time') is-invalid @enderror">
                    @error('end_time') <div class="sto-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="sto-actions">
                <a href="{{ route('shifts.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
