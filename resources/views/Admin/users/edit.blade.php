@extends('layouts.admin')

@push('styles')
<style>
    .sto-form-page { padding: 2rem 1.5rem; max-width: 920px; }
    .sto-page-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
    .sto-title-wrap { display: flex; align-items: center; gap: 12px; }
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
    .sto-help { color: #6b7280; font-size: 12px; margin-top: 6px; }
    .sto-actions { display: flex; justify-content: flex-end; gap: 10px; padding-top: 1.25rem; border-top: 1px solid #f3f4f6; margin-top: 1.25rem; }
    @media (max-width: 767.98px) {
        .sto-form-page { padding: 1rem .75rem; }
        .sto-page-head { align-items: stretch; flex-direction: column; }
        .sto-grid { grid-template-columns: 1fr; }
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
                <li><a href="{{ route('users.index') }}">User Management</a></li>
                <li><span class="active">Edit User</span></li>
            </ol>
            <a href="{{ route('users.index') }}" class="sto-page-back">
                <i class="fe fe-arrow-left"></i> Kembali ke Users
            </a>
        </div>

        <div class="sto-page-head">
            <div class="sto-title-wrap">
                <div class="sto-title-icon"><i class="fe fe-edit-2"></i></div>
                <h1 class="sto-page-title">Edit User</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" class="sto-card">
            @csrf
            @method('PUT')

            <div class="sto-grid">
                <div class="sto-field full">
                    <label for="name" class="sto-label">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="sto-control @error('name') is-invalid @enderror">
                    @error('name') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="email" class="sto-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="sto-control @error('email') is-invalid @enderror">
                    @error('email') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="role" class="sto-label">Role</label>
                    <select id="role" name="role" class="sto-control @error('role') is-invalid @enderror">
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
                        <option value="dapur"  @selected(old('role', $user->role) === 'dapur')>Dapur</option>
                        <option value="owner"  @selected(old('role', $user->role) === 'owner')>Owner</option>
                    </select>
                    @error('role') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="password" class="sto-label">Password Baru</label>
                    <input id="password" type="password" name="password" class="sto-control @error('password') is-invalid @enderror">
                    <div class="sto-help">Kosongkan jika tidak ingin mengubah password.</div>
                    @error('password') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="password_confirmation" class="sto-label">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="sto-control">
                </div>

                <div class="sto-field">
                    <label for="shift_id" class="sto-label">Shift</label>
                    <select id="shift_id" name="shift_id" class="sto-control @error('shift_id') is-invalid @enderror">
                        <option value="">Tidak ada shift</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" @selected((string) old('shift_id', $user->shift_id) === (string) $shift->id)>
                                {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                    @error('shift_id') <div class="sto-error">{{ $message }}</div> @enderror
                </div>

                <div class="sto-field">
                    <label for="is_active" class="sto-label">Status</label>
                    <select id="is_active" name="is_active" class="sto-control @error('is_active') is-invalid @enderror">
                        <option value="1" @selected((string) old('is_active', $user->is_active ? '1' : '0') === '1')>Akun aktif</option>
                        <option value="0" @selected((string) old('is_active', $user->is_active ? '1' : '0') === '0')>Akun nonaktif</option>
                    </select>
                    @error('is_active') <div class="sto-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="sto-actions">
                <a href="{{ route('users.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
