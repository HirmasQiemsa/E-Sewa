@extends('User.component')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Riwayat Pemesanan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('user.fasilitas') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Riwayat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            {{-- Notifikasi --}}
            {{-- @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif --}}

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title text-primary font-weight-bold"><i class="fas fa-filter mr-2"></i>Filter Data</h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" action="{{ route('user.riwayat') }}" method="GET">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group mb-md-0">
                                    <label class="small text-muted">Pencarian</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari ID, Fasilitas, Lokasi..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label class="small text-muted">Pilih Bulan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i
                                                    class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="calendar-filter" class="form-control bg-white"
                                            placeholder="Lihat Kalender..." readonly>
                                        <input type="hidden" name="date" id="date-value" value="{{ request('date') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label class="small text-muted">Status Booking</label>
                                    <select id="status-filter" name="status" class="form-control custom-select">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua
                                            Status</option>

                                        {{-- PERBAIKAN: Value harus sama dengan ENUM di database --}}
                                        <option value="kompensasi"
                                            {{ request('status') == 'kompensasi' ? 'selected' : '' }}>
                                            Belum Lunas (DP)
                                        </option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Menunggu Verifikasi
                                        </option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>
                                            Lunas
                                        </option>
                                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>
                                            Dibatalkan
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <a href="{{ route('user.riwayat') }}" class="btn btn-outline-secondary btn-block">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title font-weight-bold">Daftar Transaksi</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4" style="width: 5%">No</th>
                                <th>ID Booking</th>
                                <th>Fasilitas & Lokasi</th>
                                <th>Jadwal Main</th>
                                <th>Total Biaya</th>
                                <th>Status</th>
                                <th class="text-center">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkouts as $index => $checkout)
                                <tr>
                                    <td class="pl-4 align-middle">{{ $checkouts->firstItem() + $index }}</td>

                                    {{-- ID BOOKING & TANGGAL --}}
                                    <td class="align-middle">
                                        <span class="font-weight-bold text-primary">#{{ $checkout->id }}</span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $checkout->created_at->format('d M Y H:i') }}
                                        </small>
                                    </td>

                                    {{-- FASILITAS & LOKASI (Dibuat Vertikal) --}}
                                    <td class="align-middle">
                                        {{-- Ambil fasilitas dari jadwal pertama (asumsi 1 checkout = 1 fasilitas) --}}
                                        @php
                                            $fasilitas = $checkout->jadwals->first()->fasilitas ?? null;
                                        @endphp

                                        @if ($fasilitas)
                                            {{-- Nama Fasilitas (Tebal) --}}
                                            <span class="d-block font-weight-bold text-dark" style="font-size: 1rem;">
                                                {{ $fasilitas->nama_fasilitas }}
                                            </span>

                                            {{-- Lokasi (Di bawahnya, text kecil & abu-abu) --}}
                                            <small class="d-block text-muted mt-1">
                                                <i class="fas fa-map-marker-alt mr-1 text-danger"></i>
                                                {{ $fasilitas->lokasi }}
                                            </small>
                                        @else
                                            <span class="text-muted font-italic">- Data Fasilitas Terhapus -</span>
                                        @endif
                                    </td>

                                    {{-- JADWAL MAIN (Handling Multiple Jam) --}}
                                    <td class="align-middle">
                                        @if ($checkout->jadwals->count() > 0)
                                            {{-- Tanggal Main (Ambil dari jadwal pertama) --}}
                                            <div class="mb-1">
                                                <i class="far fa-calendar-alt text-secondary mr-1"></i>
                                                <span class="font-weight-bold">
                                                    {{ \Carbon\Carbon::parse($checkout->jadwals->first()->tanggal)->isoFormat('dddd, D MMMM Y') }}
                                                </span>
                                            </div>

                                            {{-- List Jam Main (Looping) --}}
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($checkout->jadwals->sortBy('jam_mulai') as $jadwal)
                                                    <span
                                                        class="badge badge-light border border-secondary text-secondary mr-1 mb-1"
                                                        style="font-size: 0.85rem;">
                                                        {{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                        {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- TOTAL BIAYA --}}
                                    <td class="align-middle font-weight-bold text-success">
                                        Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="align-middle text-center">
                                        @if ($checkout->status == 'kompensasi')
                                            <span class="badge badge-warning p-2 d-block w-100">Belum Lunas (DP)</span>
                                        @elseif($checkout->status == 'pending')
                                            <span class="badge badge-info p-2 d-block w-100">Verifikasi Admin</span>
                                        @elseif($checkout->status == 'lunas')
                                            <span class="badge badge-success p-2 d-block w-100">Lunas</span>
                                        @elseif($checkout->status == 'batal')
                                            <span class="badge badge-danger p-2 d-block w-100">Dibatalkan</span>
                                        @endif
                                    </td>

                                    {{-- OPSI --}}
                                    <td class="align-middle text-center">
                                        <div class="btn-group">
                                            @php
                                                $isKompensasi = $checkout->status === 'kompensasi';
                                            @endphp

                                            <a href="{{ route('user.checkout.detail', $checkout->id) }}"
                                                class="btn btn-sm {{ $isKompensasi ? 'btn-success font-weight-bold' : 'btn-default border' }}"
                                                title="Lihat Detail">
                                                @if ($isKompensasi)
                                                    Bayar <i class="fas fa-arrow-right ml-1"></i>
                                                @else
                                                    <i class="fas fa-eye"></i> Detail
                                                @endif
                                            </a>

                                            @if ($isKompensasi)
                                                <button type="button" class="btn btn-sm btn-danger ml-1"
                                                    onclick="confirmCancel({{ $checkout->id }})"
                                                    title="Batalkan Pesanan">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img src="{{ asset('img/no-data.svg') }}" alt="Kosong"
                                            style="height: 100px; opacity: 0.5;">
                                        <p class="text-muted mt-3">Belum ada riwayat pemesanan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white clearfix">
                    <div class="float-right">{{ $checkouts->appends(request()->query())->links() }}</div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Pembatalan</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center py-4">
                    <p>Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                    <small class="text-danger font-weight-bold">Perhatian: Uang DP yang sudah ditransfer tidak dapat
                        dikembalikan.</small>
                </div>
                <div class="modal-footer justify-content-center bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <form id="cancel-form" action="" method="POST">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Ambil Data Calendar (Marker) dari Controller
            const calendarData = @json($calendarData);
            // Data contoh: {"2026-01-10": "kompensasi", "2026-01-12": "lunas"}

            const calendarInput = document.getElementById("calendar-filter");
            const hiddenDateInput = document.getElementById(
            "date-value"); // Pastikan ID ini ada di HTML input hidden
            const filterForm = document.getElementById("filter-form");

            // 2. Konfigurasi Flatpickr
            flatpickr(calendarInput, {
                locale: "id",
                dateFormat: "Y-m-d", // Format nilai yang dikirim ke server (2026-01-10)
                altInput: true, // Aktifkan input alternatif (tampilan user)
                altFormat: "j F Y", // Format tampilan user (10 Januari 2026)
                disableMobile: "true",
                defaultDate: "{{ request('date') }}", // Muat tanggal terpilih jika ada

                // Event saat tanggal dipilih
                onChange: function(selectedDates, dateStr, instance) {
                    if (hiddenDateInput) {
                        hiddenDateInput.value = dateStr; // Isi input hidden
                        filterForm.submit(); // Submit form otomatis
                    }
                },

                // Logika Mewarnai Tanggal (Marker Status)
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Konversi tanggal elemen ke format Y-m-d lokal
                    const dateObj = dayElem.dateObj;
                    const offset = dateObj.getTimezoneOffset();
                    const localDate = new Date(dateObj.getTime() - (offset * 60 * 1000));
                    const dateString = localDate.toISOString().split('T')[0];

                    // Cek apakah tanggal ini ada di data riwayat user
                    if (calendarData[dateString]) {
                        const status = calendarData[dateString];

                        // Tambahkan class CSS dot warna sesuai status
                        if (status === 'kompensasi') {
                            dayElem.classList.add('status-fee');
                            dayElem.title = "Menunggu Pelunasan (DP)";
                        } else if (status === 'pending') {
                            dayElem.classList.add('status-pending');
                            dayElem.title = "Menunggu Verifikasi Admin";
                        } else if (status === 'lunas') {
                            dayElem.classList.add('status-lunas');
                            dayElem.title = "Booking Lunas";
                        }
                    }
                }
            });

            // 3. Auto Submit Status Filter
            const statusFilter = document.getElementById('status-filter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
        });

        // Fungsi Bantuan Lainnya
        function openPrintWindow(url) {
            window.open(url, '_blank', 'width=800,height=600');
        }

        function confirmCancel(id) {
            const cancelForm = document.getElementById('cancel-form');
            if (cancelForm) {
                cancelForm.action = "{{ url('/checkout/cancel') }}/" + id;
                $('#cancelModal').modal('show');
            }
        }
    </script>
@endpush
