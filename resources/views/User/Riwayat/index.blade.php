@extends('User.user')

@section('content')



    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0 font-weight-bold">Riwayat Pemesanan</h1></div>
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
            @if (session('success'))
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
            @endif

            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h3 class="card-title text-primary font-weight-bold"><i class="fas fa-filter mr-2"></i>Filter Data</h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" action="{{ route('user.riwayat') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label class="small text-muted">Status Booking</label>
                                    <select id="status-filter" name="status" class="form-control custom-select">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="fee" {{ request('status') == 'kompensasi' ? 'selected' : '' }}>Belum Lunas (DP)</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group mb-md-0">
                                    <label class="small text-muted">Pilih Bulan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" id="calendar-filter" class="form-control bg-white"
                                               placeholder="Lihat Kalender..." readonly>
                                        <input type="hidden" name="month" id="month-value" value="{{ request('month') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-md-0">
                                    <label class="small text-muted">Pencarian</label>
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                               placeholder="Cari ID, Fasilitas, Lokasi..."
                                               value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
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
                                    <td class="align-middle">
                                        <span class="font-weight-bold text-primary">#{{ $checkout->id }}</span><br>
                                        <small class="text-muted">{{ $checkout->created_at->format('d M Y H:i') }}</small>
                                    </td>
                                    <td class="align-middle">
                                        @if($checkout->jadwal && $checkout->jadwal->fasilitas)
                                            <span class="d-block font-weight-bold">{{ $checkout->jadwal->fasilitas->nama_fasilitas }}</span>
                                            <small class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> {{ $checkout->jadwal->fasilitas->lokasi }}</small>
                                        @else
                                            <span class="text-muted">- Data Terhapus -</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($checkout->jadwal)
                                            <span class="d-block">{{ \Carbon\Carbon::parse($checkout->jadwal->tanggal)->format('d M Y') }}</span>
                                            <small class="text-info">{{ substr($checkout->jadwal->jam_mulai, 0, 5) }} - {{ substr($checkout->jadwal->jam_selesai, 0, 5) }}</small>
                                        @else - @endif
                                    </td>
                                    <td class="align-middle font-weight-bold">Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                                    <td class="align-middle">
                                        @if ($checkout->status == 'kompensasi')
                                            <span class="badge badge-warning p-2">Belum Lunas (DP)</span>
                                        @elseif($checkout->status == 'pending')
                                            <span class="badge badge-info p-2">Menunggu Verifikasi</span>
                                        @elseif($checkout->status == 'lunas')
                                            <span class="badge badge-success p-2">Lunas</span>
                                        @elseif($checkout->status == 'batal')
                                            <span class="badge badge-danger p-2">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('user.checkout.detail', $checkout->id) }}"
                                               class="btn btn-sm {{ $checkout->status == 'kompensasi' ? 'btn-success font-weight-bold' : 'btn-default' }}"
                                               title="Lihat Detail">
                                                @if($checkout->status == 'kompensasi') Bayar @else <i class="fas fa-eye"></i> @endif
                                            </a>
                                            @if (in_array($checkout->status, ['kompensasi']))
                                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmCancel({{ $checkout->id }})"><i class="fas fa-times"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img src="{{ asset('img/no-data.svg') }}" alt="Kosong" style="height: 100px; opacity: 0.5;">
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
                    <small class="text-danger font-weight-bold">Perhatian: Uang DP yang sudah ditransfer tidak dapat dikembalikan.</small>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. LOGIKA KALENDER PINTAR (FLATPICKR)
            // Terima data dari Controller
            const calendarData = @json($calendarData);
            // Contoh data: {"2023-11-21": "fee", "2023-11-25": "lunas"}

            const calendarInput = document.getElementById("calendar-filter");
            const hiddenMonthInput = document.getElementById("month-value");
            const filterForm = document.getElementById("filter-form");

            flatpickr(calendarInput, {
                locale: "id",
                disableMobile: "true",
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, // Tampilkan bulan singkat (Jan, Feb)
                        dateFormat: "Y-m", // Format value: 2023-11
                        altFormat: "F Y", // Format tampilan: November 2023
                        theme: "material_blue"
                    })
                ],
                // Saat bulan dipilih, submit form
                onChange: function(selectedDates, dateStr, instance) {
                    hiddenMonthInput.value = dateStr;
                    filterForm.submit();
                },
                // Logika Mewarnai Tanggal (Marker)
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Konversi tanggal elemen ke Y-m-d
                    const dateObj = dayElem.dateObj;
                    // Trik timezone offset agar akurat
                    const offset = dateObj.getTimezoneOffset();
                    const localDate = new Date(dateObj.getTime() - (offset * 60 * 1000));
                    const dateString = localDate.toISOString().split('T')[0];

                    // Cek apakah tanggal ini ada di data booking user
                    if (calendarData[dateString]) {
                        const status = calendarData[dateString];

                        // Tambahkan class sesuai status prioritas
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

            // 2. Auto Submit Status Filter
            document.getElementById('status-filter').addEventListener('change', function() {
                filterForm.submit();
            });
        });

        // Fungsi Bantuan Lainnya
        function openPrintWindow(url) { window.open(url, '_blank', 'width=800,height=600'); }
        function confirmCancel(id) {
            document.getElementById('cancel-form').action = "{{ url('/checkout/cancel') }}/" + id;
            $('#cancelModal').modal('show');
        }
    </script>

@endsection
