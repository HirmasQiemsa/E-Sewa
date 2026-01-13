@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Log Aktivitas Sistem</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekam Jejak Aktivitas</h3>
                    <div class="card-tools">
                        <form action="{{ route('admin.super.laporan.log') }}" method="GET">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control float-right"
                                    placeholder="Cari deskripsi/user..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pelaku (Aktor)</th>
                                <th>Aksi</th>
                                <th>Deskripsi</th>
                                <th>Objek Terkait</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                    <td>
                                        @if ($log->admin)
                                            {{-- Jika Pelaku adalah ADMIN --}}
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tie text-secondary mr-2"></i>
                                                <div>
                                                    <b class="text-dark">{{ $log->admin->name }}</b>
                                                    <br><small class="text-muted">{{ $log->admin->role }}</small>
                                                </div>
                                            </div>
                                        @elseif($log->user)
                                            {{-- Jika Pelaku adalah USER (Masyarakat) --}}
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-info mr-2"></i>
                                                <div>
                                                    <b class="text-dark">{{ $log->user->name }}</b>
                                                    <br><small class="text-info">Masyarakat</small>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Jika Pelaku SYSTEM atau Data Terhapus --}}
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-robot text-secondary mr-2"></i>
                                                <span class="text-secondary font-italic">System / Deleted</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $color = 'secondary';
                                            if (in_array($log->action, ['CREATE', 'APPROVE', 'UNLOCK'])) {
                                                $color = 'success';
                                            }
                                            if (in_array($log->action, ['UPDATE', 'LOGIN'])) {
                                                $color = 'info';
                                            }
                                            if (in_array($log->action, ['DELETE', 'REJECT', 'LOCK'])) {
                                                $color = 'danger';
                                            }
                                        @endphp
                                        <span class="badge badge-{{ $color }}">{{ $log->action }}</span>
                                    </td>
                                    <td>{{ Str::limit($log->description, 60) }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada log aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $logs->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
@endsection
