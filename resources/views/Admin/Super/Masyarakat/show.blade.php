@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Masyarakat</h1>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('admin.super.masyarakat.index') }}" class="btn btn-secondary float-right">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- Profil --}}
                <div class="col-md-4">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('img/default-user.png') }}"
                                    alt="User profile picture"
                                    style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #adb5bd;">
                            </div>
                            <h3 class="profile-username text-center">{{ $user->name }}</h3>
                            <p class="text-muted text-center">{{ $user->email }}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Status Akun</b>
                                    <span
                                        class="float-right badge {{ $user->is_locked ?? true ? 'badge-danger' : 'badge-success' }}">
                                        {{ $user->is_locked ?? true ? 'DIBEKUKAN' : 'AKTIF' }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>No. HP</b> <a class="float-right">{{ $user->no_hp ?? '-' }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>NIK / KTP</b> <a class="float-right">{{ $user->no_ktp ?? '-' }}</a>
                                </li>
                            </ul>

                            {{-- AKSI LOCK / UNLOCK --}}
                            <form action="{{ route('admin.super.masyarakat.lock', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                @if ($user->is_locked ?? true)
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-unlock mr-1"></i> Aktifkan Kembali (Unlock)
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-danger btn-block">
                                        <i class="fas fa-lock mr-1"></i> Bekukan Akun (Lock)
                                    </button>
                                    <small class="text-muted d-block mt-2 text-center">*User tidak akan bisa booking
                                        fasilitas.</small>
                                @endif
                            </form>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Alamat</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                {{ $user->alamat ?? 'Belum mengisi alamat lengkap.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- History Transaksi (Opsional, tapi bagus buat monitoring) --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header p-2">
                            <h3 class="card-title p-1">Riwayat Transaksi Terbaru</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Fasilitas</th>
                                        <th>Tanggal Booking</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Pastikan relasi 'checkouts' ada di User Model --}}
                                    @forelse($user->checkouts()->latest()->take(5)->get() as $trx)
                                        <tr>
                                            <td>{{ $trx->kode_transaksi ?? 'TRX-' . $trx->id }}</td>
                                            <td>
                                                {{-- Ambil jadwal pertama dari list, lalu ambil fasilitasnya --}}
                                                {{ $trx->jadwals->first()->fasilitas->nama_fasilitas ?? '-' }}

                                                <br>

                                                <small class="text-muted">
                                                    {{-- Tanggal juga harus diambil dari jadwal pertama --}}
                                                    @if ($trx->jadwals->isNotEmpty())
                                                        {{ \Carbon\Carbon::parse($trx->jadwals->first()->tanggal)->format('d M Y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </small>
                                            </td>
                                            <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if ($trx->status == 'lunas')
                                                    <span class="badge badge-success px-2 py-1">
                                                        <i class="fas fa-check-circle mr-1"></i> Lunas
                                                    </span>
                                                @elseif($trx->status == 'kompensasi')
                                                    <span class="badge badge-warning px-2 py-1">
                                                        <i class="fas fa-money-bill-wave mr-1"></i> DP / Belum Lunas
                                                    </span>
                                                @elseif($trx->status == 'pending')
                                                    <span class="badge badge-info px-2 py-1">
                                                        <i class="fas fa-clock mr-1"></i> Menunggu Verifikasi
                                                    </span>
                                                @elseif($trx->status == 'batal' || $trx->status == 'cancel')
                                                    <span class="badge badge-danger px-2 py-1">
                                                        <i class="fas fa-times-circle mr-1"></i> Batal
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary px-2 py-1">
                                                        {{ ucfirst($trx->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada riwayat transaksi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
