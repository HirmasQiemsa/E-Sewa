@extends('Admin.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <a href="#tabel-jadwal" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-arrow-down"></i> Lihat Tabel Jadwal
                    </a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kelola Jadwal</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Generator Jadwal Otomatis</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.fasilitas.jadwal.generate') }}" method="POST" id="formGenerate">
                                @csrf
                                <div class="form-group">
                                    <label>Pilih Fasilitas</label>
                                    <select name="fasilitas_id" id="fasilitas_id" class="form-control" required>
                                        <option value="">-- Pilih Fasilitas --</option>
                                        @foreach ($myFasilitas as $f)
                                            @php
                                                // LOGIC BARU: Hanya disable jika Nonaktif atau Maintenance
                                                // Kita abaikan status_approval
                                                $isDisabled = $f->ketersediaan !== 'aktif';

                                                $statusText = '';
                                                if ($isDisabled) {
                                                    $statusText = ' (Status: ' . ucfirst($f->ketersediaan) . ')';
                                                }
                                            @endphp

                                            <option value="{{ $f->id }}" {{ $isDisabled ? 'disabled' : '' }}
                                                class="{{ $isDisabled ? 'text-danger' : 'text-dark' }}">
                                                {{ $f->nama_fasilitas }} - {{ $f->lokasi }} {{ $statusText }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Fasilitas harus berstatus <b>Aktif</b> agar bisa
                                        dibuatkan jadwal.
                                    </small>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Mulai Tanggal</label>
                                            {{-- Tambahkan atribut min="{{ date('Y-m-d') }}" --}}
                                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"
                                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Sampai Tanggal</label>
                                            {{-- Tambahkan atribut min="{{ date('Y-m-d') }}" --}}
                                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"
                                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jam Buka</label>
                                            <input type="time" name="jam_buka" class="form-control" value="08:00" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jam Tutup</label>
                                            <input type="time" name="jam_tutup" class="form-control" value="22:00" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Durasi per Sesi (Jam)</label>
                                    <input type="number" name="durasi" class="form-control" value="1" min="1" max="5"
                                        required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-magic mr-1"></i> Generate Jadwal Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info-circle"></i> Cara Kerja Generator</h5>
                        <p>Fitur ini akan membuat slot jadwal secara massal berdasarkan rentang tanggal dan jam
                            operasional yang Anda tentukan.</p>
                        <ul>
                            <li>Sistem akan otomatis melewati slot yang sudah ada (tidak duplikat).</li>
                            <li>Pastikan durasi sesi sesuai (misal: 1 jam untuk Badminton).</li>
                            <li>Pastikan fasilitas sudah berstatus <b>Aktif</b>.</li>
                            <li>Jadwal yang dibuat otomatis berstatus <b>Tersedia</b>.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mt-3" id="tabel-jadwal">
                <div class="col-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Daftar Slot Jadwal</h3>

                            <div class="card-tools">
                                <button type="button" id="btnBulkDelete" class="btn btn-danger btn-sm mr-2"
                                    style="display:none;">
                                    <i class="fas fa-trash"></i> Hapus Terpilih (<span id="countSelected">0</span>)
                                </button>

                                <form action="{{ route('admin.fasilitas.jadwal.index') }}" method="GET"
                                    class="d-inline-block">
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="date" name="tanggal" class="form-control"
                                            value="{{ request('tanggal') }}" placeholder="Filter Tanggal">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">
                                            <input type="checkbox" id="checkAll">
                                        </th>
                                        <th>Fasilitas</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Status</th>
                                        <th>Penyewa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwals as $j)
                                        <tr>
                                            <td>
                                                @if($j->status != 'terbooking')
                                                    <input type="checkbox" class="checkItem" value="{{ $j->id }}">
                                                @else
                                                    <i class="fas fa-lock text-muted" title="Sedang dibooking"></i>
                                                @endif
                                            </td>
                                            <td>{{ $j->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}</td>
                                            <td>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                                            <td>
                                                {{-- 1. PRIORITAS TERTINGGI: Jika sudah terbooking, status fasilitas tidak
                                                masalah (karena sudah laku) --}}
                                                @if($j->status == 'terbooking')
                                                    <span class="badge badge-warning text-white">
                                                        <i class="fas fa-user-check mr-1"></i> Booked
                                                    </span>

                                                    {{-- 2. PRIORITAS KEDUA: Cek Status Fasilitas Induk --}}
                                                    {{-- Jika Fasilitas Maintenance/Nonaktif, maka jadwal ini otomatis dianggap
                                                    tidak valid --}}
                                                @elseif($j->fasilitas->ketersediaan != 'aktif')
                                                    <span class="badge badge-secondary"
                                                        title="Fasilitas sedang {{ $j->fasilitas->ketersediaan }}">
                                                        <i class="fas fa-ban mr-1"></i> {{ ucfirst($j->fasilitas->ketersediaan) }}
                                                    </span>

                                                    {{-- 3. PRIORITAS KETIGA: Status Jadwal Normal --}}
                                                @elseif($j->status == 'tersedia')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle mr-1"></i> Tersedia
                                                    </span>

                                                @elseif($j->status == 'batal')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                                                    </span>

                                                @else
                                                    <span class="badge badge-info">{{ ucfirst($j->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $j->checkouts->first()->user->name ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada jadwal. Gunakan generator
                                                di atas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            {{ $jadwals->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkAll = document.getElementById('checkAll');
            const btnBulkDelete = document.getElementById('btnBulkDelete');
            const countSelected = document.getElementById('countSelected');
            const tglMulai = document.getElementById('tgl_mulai');
            const tglSelesai = document.getElementById('tgl_selesai');

            // Validasi Tanggal
            tglMulai.addEventListener('change', function () {
                tglSelesai.min = this.value;

                // Jika tanggal selesai sekarang lebih kecil dari mulai baru, reset
                if (tglSelesai.value < this.value) {
                    tglSelesai.value = this.value;
                }
            });

            // Toggle All
            checkAll.addEventListener('change', function () {
                document.querySelectorAll('.checkItem').forEach(cb => cb.checked = this.checked);
                updateBulkBtn();
            });

            // Toggle Item
            document.addEventListener('change', function (e) {
                if (e.target.classList.contains('checkItem')) updateBulkBtn();
            });

            function updateBulkBtn() {
                const count = document.querySelectorAll('.checkItem:checked').length;
                countSelected.innerText = count;
                btnBulkDelete.style.display = count > 0 ? 'inline-block' : 'none';
            }

            // Action Delete
            btnBulkDelete.addEventListener('click', function () {
                const ids = Array.from(document.querySelectorAll('.checkItem:checked')).map(cb => cb.value);

                Swal.fire({
                    title: 'Hapus ' + ids.length + ' Jadwal?',
                    text: "Jadwal yang sudah dibooking tidak akan terhapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('admin.fasilitas.jadwal.bulk_destroy') }}", {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ ids: ids })
                        })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Deleted!', data.success, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error', data.error, 'error');
                                }
                            });
                    }
                });
            });
        });
    </script>
@endsection
