@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h1 class="mb-1">Dashboard Admin</h1>
            <p class="text-muted mb-0">Ringkasan data backoffice Rhea Debora Sianturi.</p>
        </div>
        <span class="badge text-bg-primary">AdminLTE + Breeze + Spatie</span>
    </div>
@stop

@section('content')
    <div class="row">
        @foreach ($stats as $stat)
            <div class="col-lg-4 col-md-6">
                <div class="small-box text-bg-{{ $stat['tone'] }}">
                    <div class="inner">
                        <h3>{{ number_format($stat['value']) }}</h3>
                        <p>{{ $stat['label'] }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $stat['icon'] }}" aria-hidden="true"></i>
                    </div>
                    <span class="small-box-footer">
                        Dihitung langsung dari model <code>count()</code>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Role dan Permission</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th class="text-center">Permission</th>
                                <th class="text-center">Pengguna</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td class="text-center">{{ $role->permissions_count }}</td>
                                    <td class="text-center">{{ $role->users_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">Belum ada role.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Sesi Login</h3>
                </div>
                <div class="card-body">
                    <p class="mb-2">Masuk sebagai:</p>
                    <p class="fw-semibold mb-1">{{ auth()->user()->name }}</p>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    <p class="mb-2">Role aktif:</p>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @forelse (auth()->user()->getRoleNames() as $roleName)
                            <span class="badge text-bg-success">{{ $roleName }}</span>
                        @empty
                            <span class="badge text-bg-secondary">Tanpa role</span>
                        @endforelse
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <strong>&copy; {{ date('Y') }} Rhea Debora Sianturi.</strong> Backoffice Tugas 10 PBKK.
@stop
