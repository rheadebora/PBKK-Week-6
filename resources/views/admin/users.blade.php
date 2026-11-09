@extends('adminlte::page')

@section('title', 'Kelola Pengguna')

@section('content_header')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h1 class="mb-1">Kelola Pengguna</h1>
            <p class="text-muted mb-0">Pencarian dan filter memakai kueri kondisional <code>when()</code>.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Dashboard
        </a>
    </div>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-2 align-items-end">
                <div class="col-lg-6">
                    <label for="q" class="form-label">Cari pengguna</label>
                    <input
                        type="search"
                        id="q"
                        name="q"
                        value="{{ $search }}"
                        class="form-control"
                        placeholder="Nama atau email"
                    >
                </div>
                <div class="col-lg-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">Semua role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected($roleFilter === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search" aria-hidden="true"></i> Cari
                    </button>
                    @if ($search !== '' || $roleFilter !== '')
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h3 class="card-title mb-0">Daftar Pengguna</h3>
            <span class="badge text-bg-primary">{{ $users->total() }} data</span>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 4rem;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Verifikasi</th>
                        <th>Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-center">{{ ($users->firstItem() ?? 1) + $loop->index }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if ($user->is(auth()->user()))
                                    <span class="badge text-bg-info ms-1">Anda</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @forelse ($user->getRoleNames() as $roleName)
                                    <span class="badge text-bg-success">{{ $roleName }}</span>
                                @empty
                                    <span class="badge text-bg-secondary">Tanpa role</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                @if ($user->email_verified_at)
                                    <span class="badge text-bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge text-bg-warning">Belum</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->translatedFormat('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Tidak ada pengguna yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}
                dari {{ $users->total() }} pengguna
                @if ($search !== '')
                    untuk kata kunci <strong>{{ $search }}</strong>
                @endif
                @if ($roleFilter !== '')
                    dengan role <strong>{{ $roleFilter }}</strong>
                @endif
            </small>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
@stop

@section('footer')
    <strong>&copy; {{ date('Y') }} Rhea Debora Sianturi.</strong> Backoffice Tugas 10 PBKK.
@stop
