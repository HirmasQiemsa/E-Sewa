@extends('Admin.component')

@push('style')
    <style>
        /* Agar baris terlihat bisa diklik */
        .jadwal-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        /* Warna saat baris dipilih/dicentang (Kuning muda biar kontras) */
        .jadwal-row.row-selected {
            background-color: #fff3cd !important;
        }

        /* Warna saat hover (sorot mouse) */
        .jadwal-row:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    {{-- Header Content (Sama seperti sebelumnya) --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kelola Jadwal</h1>
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

            {{-- BAGIAN 1: GENERATOR (Code Form Sama, cuma aku kasih ID buat JS) --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><b>Generator Jadwal</b></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm mr-2" data-toggle="modal"
                                    data-target="#modalTambahManual">
                                    <i class="fas fa-plus"></i> Tambah Satuan
                                </button>
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
                                                $isDisabled = $f->ketersediaan !== 'aktif';
                                                $statusText = $isDisabled
                                                    ? ' (Status: ' . ucfirst($f->ketersediaan) . ')'
                                                    : '';
                                            @endphp
                                            <option value="{{ $f->id }}" {{ $isDisabled ? 'disabled' : '' }}
                                                class="{{ $isDisabled ? 'text-danger' : 'text-dark' }}">
                                                {{ $f->nama_fasilitas }} - {{ $f->lokasi }} {{ $statusText }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Mulai Tanggal</label>
                                            {{-- ID tgl_mulai dipake di JS --}}
                                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control"
                                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Sampai Tanggal</label>
                                            {{-- ID tgl_selesai dipake di JS --}}
                                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control"
                                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jam Buka</label>
                                            <select name="jam_buka" class="form-control" required>
                                                @for ($i = 0; $i <= 23; $i++)
                                                    @php $jam = sprintf("%02d:00", $i); @endphp
                                                    <option value="{{ $jam }}" {{ $i == 8 ? 'selected' : '' }}>
                                                        {{ $jam }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Jam Tutup</label>
                                            <select name="jam_tutup" class="form-control" required>
                                                @for ($i = 0; $i <= 23; $i++)
                                                    @php $jam = sprintf("%02d:00", $i); @endphp
                                                    <option value="{{ $jam }}" {{ $i == 22 ? 'selected' : '' }}>
                                                        {{ $jam }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Durasi per Sesi (Jam)</label>
                                    <input type="number" name="durasi" class="form-control" value="1" min="1"
                                        max="5" required>
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

                {{-- Info Box --}}
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

            {{-- BAGIAN 2: DAFTAR SLOT JADWAL (TABEL DENGAN BULK ACTION) --}}
            <div class="row" id="tabel-jadwal">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i> Daftar Slot Jadwal
                            </h3>
                            <div class="card-tools">
                                {{-- Form Pencarian & Filter --}}
                                <form action="{{ route('admin.fasilitas.jadwal.index') }}" method="GET"
                                    class="form-inline input-group-sm">

                                    {{-- 1. TOMBOL TOGGLE UNTUK MELIHAT DATA MASA LAMPAU --}}
                                    <div class="mr-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="showExpired"
                                                name="show_expired" value="1"
                                                {{ request('show_expired') ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="custom-control-label font-weight-normal" for="showExpired">
                                                Tampilkan Jadwal Lewat
                                            </label>
                                        </div>
                                    </div>

                                    <input type="date" name="tanggal" class="form-control mr-2"
                                        value="{{ request('tanggal') }}">

                                    <select name="fasilitas_id" class="form-control mr-2">
                                        <option value="">Semua Fasilitas</option>
                                        @foreach ($myFasilitas as $f)
                                            <option value="{{ $f->id }}"
                                                {{ request('fasilitas_id') == $f->id ? 'selected' : '' }}>
                                                {{ $f->nama_fasilitas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </form>
                            </div>
                        </div>

                        {{-- Form Action --}}
                        <form action="{{ route('admin.fasilitas.jadwal.bulkAction') }}" method="POST" id="formBulk">
                            @csrf
                            @method('PUT')

                            <div class="card-body p-0">
                                <div class="p-3 bg-light border-bottom">
                                    <span class="text-muted mr-2">Aksi Terpilih:</span>
                                    <button type="submit" name="action" value="nonaktifkan"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menonaktifkan jadwal yang dipilih?')">
                                        <i class="fas fa-ban"></i> Nonaktifkan
                                    </button>
                                    <button type="submit" name="action" value="aktifkan"
                                        class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i> Aktifkan (Tersedia)
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll">
                                                </th>
                                                <th>Tanggal</th>
                                                <th>Jam</th>
                                                <th>Fasilitas</th>
                                                <th>Status</th>
                                                <th>Penyewa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($jadwals as $jadwal)
                                                @php
                                                    // Logika Expired (Sama seperti sebelumnya)
                                                    $isPast = $jadwal->tanggal < date('Y-m-d');
                                                    $isBooked = $jadwal->checkouts->isNotEmpty();
                                                    $isAvailable = $jadwal->status == 'tersedia';
                                                    $isExpired = $isPast && $isAvailable && !$isBooked;

                                                    if ($isExpired && !request('show_expired')) {
                                                        continue;
                                                    }
                                                @endphp

                                                {{-- TAMBAHKAN CLASS 'jadwal-row' DI SINI --}}
                                                <tr class="jadwal-row {{ $isExpired ? 'bg-secondary text-white disabled-row' : '' }}"
                                                    style="{{ $isExpired ? 'opacity: 0.6; background-color: #e9ecef !important; color: #6c757d !important; cursor: not-allowed;' : '' }}">

                                                    <td class="text-center">
                                                        {{-- Checkbox Asli --}}
                                                        <input type="checkbox" name="ids[]"
                                                            value="{{ $jadwal->id }}" class="check-item"
                                                            data-status="{{ $jadwal->status }}"
                                                            {{ $isExpired ? 'disabled' : '' }}>
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}
                                                        @if ($isExpired)
                                                            <br><small class="badge badge-secondary">Kadaluarsa</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                        {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                                    <td>{{ $jadwal->fasilitas->nama_fasilitas }}</td>
                                                    <td>
                                                        @if ($jadwal->status == 'tersedia')
                                                            <span class="badge badge-success">Tersedia</span>
                                                        @elseif($jadwal->status == 'nonaktif')
                                                            <span class="badge badge-danger">Nonaktif</span>
                                                        @else
                                                            <span
                                                                class="badge badge-warning">{{ ucfirst($jadwal->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $isBooked ? $jadwal->checkouts->first()->user->name ?? 'User' : '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data
                                                        jadwal.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                {{ $jadwals->appends(request()->query())->links() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MODAL TAMBAH JADWAL MANUAL --}}
    <div class="modal fade" id="modalTambahManual" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Jadwal Satuan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('admin.fasilitas.jadwal.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        {{-- Inputan lainnya sama --}}
                        <div class="form-group">
                            <label>Fasilitas</label>
                            <select name="fasilitas_id" class="form-control" required>
                                @foreach ($myFasilitas as $f)
                                    @if ($f->ketersediaan == 'aktif')
                                        <option value="{{ $f->id }}">{{ $f->nama_fasilitas }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            {{-- Validasi HTML5 min hari ini --}}
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}"
                                min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mulai</label>
                                    <select name="jam_mulai" class="form-control" required>
                                        <option value="">-- Jam --</option>
                                        @for ($i = 6; $i <= 23; $i++)
                                            @php $jam = sprintf("%02d:00", $i); @endphp
                                            <option value="{{ $jam }}">{{ $jam }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Selesai</label>
                                    <select name="jam_selesai" class="form-control" required>
                                        <option value="">-- Jam --</option>
                                        @for ($i = 7; $i <= 24; $i++)
                                            @php $jam = sprintf("%02d:00", $i); @endphp
                                            <option value="{{ $jam }}">{{ $jam }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // -------------------------------------------------------------
            // 1. LOGIKA GENERATOR JADWAL (DENGAN CONFIRMATION & KALKULASI)
            // -------------------------------------------------------------

            // Saat tombol submit ditekan
            $('#formGenerate').on('submit', function(e) {
                e.preventDefault(); // Tahan dulu, jangan submit langsung

                // Ambil nilai dari inputan
                const fasilitasText = $('#fasilitas_id option:selected').text().trim();
                const tglMulai = $('#tgl_mulai').val();
                const tglSelesai = $('#tgl_selesai').val();
                const jamBuka = $('select[name="jam_buka"]').val();
                const jamTutup = $('select[name="jam_tutup"]').val();
                const durasi = parseInt($('input[name="durasi"]').val()) || 1;

                // Hitung Selisih Hari
                const date1 = new Date(tglMulai);
                const date2 = new Date(tglSelesai);
                const diffTime = Math.abs(date2 - date1);
                const totalHari = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                // Hitung Selisih Jam (Slot per hari)
                const jamBukaInt = parseInt(jamBuka.split(':')[0]);
                const jamTutupInt = parseInt(jamTutup.split(':')[0]);
                let totalJamOperasional = jamTutupInt - jamBukaInt;

                // Validasi jam terbalik
                if (totalJamOperasional <= 0) {
                    Swal.fire('Error', 'Jam Tutup harus lebih besar dari Jam Buka!', 'error');
                    return;
                }

                const slotPerHari = Math.floor(totalJamOperasional / durasi);
                const totalEstimasi = totalHari * slotPerHari;

                // Tampilkan SweetAlert Konfirmasi
                Swal.fire({
                    title: 'Konfirmasi Generate?',
                    html: `
                        <div class="text-left" style="font-size: 0.9em;">
                            <p>Anda akan membuat jadwal untuk:</p>
                            <ul>
                                <li><b>Fasilitas:</b> ${fasilitasText}</li>
                                <li><b>Periode:</b> ${totalHari} Hari (${tglMulai} s/d ${tglSelesai})</li>
                                <li><b>Jam:</b> ${jamBuka} - ${jamTutup}</li>
                                <li><b>Durasi:</b> ${durasi} Jam/Sesi</li>
                            </ul>
                            <div class="alert alert-info text-center">
                                <b>Estimasi Total: ±${totalEstimasi} Slot Jadwal</b><br>
                                <small>(Slot yang sudah ada akan dilewati)</small>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Generate Sekarang!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan Loading
                        Swal.fire({
                            title: 'Sedang Memproses...',
                            html: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        // Submit form secara manual via native DOM
                        e.target.submit();
                    }
                });
            });

            // -------------------------------------------------------------
            // 2. LOGIKA TANGGAL RESPONSIVE (GENERATOR) - SAMA SEPERTI SEBELUMNYA
            // -------------------------------------------------------------
            const $tglMulai = $('#tgl_mulai');
            const $tglSelesai = $('#tgl_selesai');

            $tglMulai.on('change', function() {
                const minDate = $(this).val();
                $tglSelesai.attr('min', minDate);
                if ($tglSelesai.val() < minDate) {
                    $tglSelesai.val(minDate);
                }
            });

            // -------------------------------------------------------------
            // 3. LOGIKA CLICKABLE ROW & CHECKBOX
            // -------------------------------------------------------------

            // A. Saat baris (TR) diklik
            $(document).on('click', '.jadwal-row', function(e) {
                // Cegah konflik jika user klik pas di checkbox-nya langsung
                if ($(e.target).is('input[type="checkbox"]')) return;

                // Cari checkbox di dalam baris ini
                const $checkbox = $(this).find('.check-item');

                // Jika checkbox tidak disabled (bukan expired), toggle statusnya
                if (!$checkbox.prop('disabled')) {
                    $checkbox.prop('checked', !$checkbox.prop('checked')).trigger('change');
                }
            });

            // B. Saat Checkbox berubah (baik diklik manual atau via baris)
            // Fungsi ini untuk Ganti Warna Background
            $('.check-item').on('change', function() {
                const $row = $(this).closest('tr');
                if ($(this).is(':checked')) {
                    $row.addClass('row-selected'); // Tambah warna kuning
                } else {
                    $row.removeClass('row-selected'); // Hapus warna
                }

                updateCheckAllState(); // Cek status tombol "Select All"
            });

            // C. Logika Select All (Diperbarui untuk Handle Warna)
            $('#checkAll').click(function() {
                const isChecked = $(this).is(':checked');

                // Hanya pilih yang tidak disabled
                $('.check-item:not(:disabled)').prop('checked', isChecked).trigger('change');
            });

            // Fungsi helper untuk update status checkbox "Select All"
            function updateCheckAllState() {
                const totalCheckbox = $('.check-item:not(:disabled)').length;
                const totalChecked = $('.check-item:not(:disabled):checked').length;

                // Jika semua tercentang, maka checkAll juga tercentang
                $('#checkAll').prop('checked', totalCheckbox > 0 && totalCheckbox === totalChecked);
            }
        });
    </script>
@endpush
