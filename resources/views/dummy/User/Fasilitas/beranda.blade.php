@extends('User.user')

@section('content')

    {{--
        LOGIKA PHP:
        1. Ambil Pending Booking (Untuk Widget).
        2. Ambil Riwayat Booking Lengkap (Untuk Kalender).
           Kita format menjadi array: ['2023-11-20' => 'Futsal (08:00-09:00)<br>Tenis (10:00-11:00)']
    --}}
    @php
        // 1. Data Pending (Widget)
        $pendingBooking = \App\Models\Checkout::with(['jadwals', 'jadwal.fasilitas'])
            ->where('user_id', auth()->id())
            ->where('status', 'kompensasi')
            ->orderBy('created_at', 'desc')
            ->first();

        // 2. Data Riwayat (Kalender)
        $rawHistory = \App\Models\Jadwal::whereHas('checkouts', function($q) {
                $q->where('user_id', auth()->id())
                  ->whereIn('status', ['lunas', 'selesai']);
            })
            ->with('fasilitas')
            ->get()
            ->groupBy('tanggal');

        $userHistoryData = [];
        foreach($rawHistory as $date => $items) {
            $details = $items->map(function($item) {
                return '• ' . $item->fasilitas->nama_fasilitas . ' (' . substr($item->jam_mulai, 0, 5) . ' - ' . substr($item->jam_selesai, 0, 5) . ')';
            })->toArray();
            // Gabungkan dengan line break HTML untuk SweetAlert
            $userHistoryData[$date] = implode('<br>', $details);
        }
    @endphp

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <style>
        /* Style Widget */
        .booking-widget-container { width: 100%; margin-bottom: 30px; animation: slideDown 0.5s ease-out; position: relative; z-index: 99; }

        /* Flatpickr Styles */
        .flatpickr-input { display: none; }

        /* Marker Warna Tosca untuk Riwayat */
        .flatpickr-day.user-booking-date {
            background-color: #20c997 !important;
            border-color: #20c997 !important;
            color: white !important;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(32, 201, 151, 0.3);
        }
        .flatpickr-day.user-booking-date:hover {
            background-color: #17a589 !important;
        }

        /* Tombol Tanggal */
        .date-trigger-btn {
            background-color: #007bff; color: white; border: none; padding: 8px 20px;
            border-radius: 50px; font-weight: 600; box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        }
        .date-trigger-btn:hover { background-color: #0056b3; transform: translateY(-2px); }

        /* Card Style */
        .small-box { transition: transform 0.3s ease; }
        .small-box:hover { transform: translateY(-5px); }
        .small-box .icon>i { font-size: 70px; top: 20px; }

        /* Animations */
        @keyframes slideDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { to { opacity: 0; transform: translateY(-50px); display: none; } }
    </style>

    <section class="content">
        <div class="container-fluid">

            @if($pendingBooking && $pendingBooking->jadwal)
                <div class="booking-widget-container" id="autoCloseWidget">
                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" style="background-color: #fff3cd; border-left: 5px solid #ffc107;">
                        <div style="font-size: 1.5rem; margin-right: 15px; color: #ffc107;">
                            <i class="fas fa-exclamation-circle animation__shake"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="font-weight-bold mb-1 text-dark">Menunggu Pembayaran</h6>
                            <small class="text-muted d-block">
                                Tagihan: <strong>{{ $pendingBooking->jadwal->fasilitas->nama_fasilitas }}</strong>
                                ({{ date('d M', strtotime($pendingBooking->jadwal->tanggal)) }}).
                            </small>
                        </div>
                        <a href="{{ route('user.checkout.detail', $pendingBooking->id) }}" class="btn btn-warning btn-sm font-weight-bold ml-3">
                            Bayar <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endif

            <div class="content-header pl-0 pr-0">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-dark">Fasilitas Tersedia</h1>
                        <p class="text-muted mb-0">Cek ketersediaan lapangan di sini</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3 mt-sm-0">
                            <button id="dateTrigger" class="date-trigger-btn">
                                <i class="fas fa-calendar-alt"></i>
                                <span id="dateLabel">{{ \Carbon\Carbon::parse($selectedDate)->isoFormat('dddd, D MMMM Y') }}</span>
                                <i class="fas fa-chevron-down ml-1" style="font-size: 0.8em;"></i>
                            </button>
                            <input type="text" id="datePickerInput" value="{{ $selectedDate }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($fasilitas as $f)
                    <div class="col-lg-4 col-md-6">
                        <div class="small-box {{ $f->icon_color ?? 'bg-info' }}">
                            <div class="inner">
                                <h3>{{ $f->nama_fasilitas }}</h3>
                                <div class="mb-2">
                                    <span class="d-inline-block mr-2" style="font-size: 1rem">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ Str::limit($f->lokasi, 25) }}
                                    </span>
                                </div>
                                <div class="mt-3" style="min-height: 50px;">
                                    @if($f->total_slot > 0)
                                        <h5 class="font-weight-bold mb-1"><i class="far fa-clock"></i> {{ $f->jam_mulai_tersedia }} - {{ $f->jam_tutup_tersedia }} WIB</h5>
                                        <div class="mt-1"><span class="badge badge-light text-dark">{{ $f->total_slot }} Slot Tersedia</span></div>
                                    @else
                                        <h5 class="font-weight-bold mb-1 text-white-50"><i class="fas fa-times-circle"></i> Penuh / Tutup</h5>
                                        <small class="text-white-50">Tidak ada jadwal tersedia pada tanggal ini</small>
                                    @endif
                                </div>
                            </div>
                            <div class="icon"><i class="{{ $f->icon ?? 'fas fa-dumbbell' }}"></i></div>

                            @if($f->is_restricted)
                                <a href="#" class="small-box-footer bg-secondary disabled" style="cursor: not-allowed;">Tidak Tersedia <i class="fas fa-ban"></i></a>
                            @else
                                {{-- Kita kirim ID fasilitas DAN Tanggal yang sedang dipilih --}}
                                <a href="{{ route('user.fasilitas.detail', ['id' => $f->id, 'date' => $selectedDate]) }}" class="small-box-footer">
                                    {{ $f->total_slot > 0 ? 'Lihat Jadwal' : 'Cek Detail' }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center shadow-sm">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5>Tidak ada fasilitas yang ditemukan.</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="how-to-book">
                <h3 class="text-center mb-4">Cara Booking Fasilitas</h3>
                <div class="row text-center">
                    <div class="col-md-3"><div class="step-icon"><i class="fas fa-search-location"></i></div><h5>1. Pilih Fasilitas</h5></div>
                    <div class="col-md-3"><div class="step-icon"><i class="fas fa-calendar-alt"></i></div><h5>2. Pilih Jadwal</h5></div>
                    <div class="col-md-3"><div class="step-icon"><i class="fas fa-money-bill-wave"></i></div><h5>3. Bayar DP</h5></div>
                    <div class="col-md-3"><div class="step-icon"><i class="fas fa-check-circle"></i></div><h5>4. Bermain & Lunaskan</h5></div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateTrigger = document.getElementById("dateTrigger");
            const dateInput = document.getElementById("datePickerInput");

            // 1. DATA HISTORY DARI CONTROLLER
            // Object JS: { '2023-11-20': 'Futsal (08:00-09:00)<br>Tenis...', ... }
            const historyData = @json($userHistoryData);
            const bookedDates = Object.keys(historyData); // Ambil array tanggalnya saja

            const now = new Date();
            const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            // 2. KONFIGURASI FLATPICKR
            const fp = flatpickr(dateInput, {
                locale: "id",
                dateFormat: "Y-m-d",
                maxDate: endOfMonth,
                minDate: null,
                disableMobile: "true",
                positionElement: dateTrigger,
                clickOpens: false,
                closeOnSelect: true,

                // LOGIKA DISABLE (Ketersediaan Tanggal)
                disable: [
                    function (date) {
                        // Format ke Y-m-d lokal
                        const offset = date.getTimezoneOffset();
                        const localDate = new Date(date.getTime() - (offset * 60 * 1000));
                        const dateStr = localDate.toISOString().split('T')[0];

                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        // Masa Depan = BOLEH (False = Enabled)
                        if (date >= today) return false;

                        // Masa Lalu = MATI (True = Disabled)
                        // KITA DISABLE JUGA TANGGAL HISTORY.
                        // Kenapa? Supaya tidak memicu 'onChange' (Reload halaman).
                        // Kita akan tangkap klik-nya secara manual di 'onDayCreate'
                        return true;
                    }
                ],

                // LOGIKA VISUAL & INTERAKSI KLIK
                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    const dateObj = dayElem.dateObj;
                    const offset = dateObj.getTimezoneOffset();
                    const localDate = new Date(dateObj.getTime() - (offset * 60 * 1000));
                    const dateString = localDate.toISOString().split('T')[0];

                    // A. TANGGAL ADA DI RIWAYAT (Warna Hijau)
                    if (bookedDates.includes(dateString)) {
                        dayElem.classList.add('user-booking-date');
                        dayElem.title = "Riwayat Booking Anda";

                        // Event Click Khusus Riwayat
                        dayElem.addEventListener("click", function (e) {
                            e.stopPropagation(); // Jangan sampai event tembus
                            fp.close(); // Tutup kalender

                            // Tampilkan Alert Hijau (Success)
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Riwayat Booking Anda',
                                    html: `<div class="text-left mt-2" style="font-size: 0.9rem; color: #555;">${historyData[dateString]}</div>`,
                                    footer: '<small class="text-muted">' + new Date(dateString).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) + '</small>',
                                    showCloseButton: true, // Ada tombol silang (X)
                                    showConfirmButton: false, // Tidak ada tombol OK besar
                                    allowOutsideClick: true // Bisa tutup klik luar
                                });
                            }
                        });
                    }

                    // B. TANGGAL INVALID (Bukan Hari Ini, Bukan Masa Depan, Bukan Riwayat)
                    else if (dayElem.classList.contains("flatpickr-disabled")) {
                        dayElem.addEventListener("click", function (e) {
                            e.stopPropagation();
                            fp.close();

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Tanggal Tidak Tersedia',
                                    text: 'Mengembalikan ke hari ini...',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                }).then(() => {
                                    resetToToday();
                                });
                            } else {
                                resetToToday();
                            }
                        });
                    }
                },

                // SAAT TANGGAL VALID (MASA DEPAN) DIPILIH
                onChange: function (selectedDates, dateStr, instance) {
                    instance.close();
                    dateTrigger.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
                    dateTrigger.classList.add('active');

                    Swal.fire({
                        title: 'Memuat Jadwal...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });
                    window.location.href = "{{ route('user.fasilitas') }}?date=" + dateStr;
                }
            });

            function resetToToday() {
                const d = new Date();
                const offset = d.getTimezoneOffset();
                const local = new Date(d.getTime() - (offset * 60 * 1000));
                const todayStr = local.toISOString().split('T')[0];

                dateTrigger.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mereset...';
                dateTrigger.classList.add('active');
                window.location.href = "{{ route('user.fasilitas') }}?date=" + todayStr;
            }

            // Toggle Button Logic
            dateTrigger.addEventListener("click", function (e) {
                e.stopPropagation();
                if (fp.isOpen) fp.close();
                else fp.open();
            });

            document.addEventListener("click", function (e) {
                if (fp.isOpen && !dateTrigger.contains(e.target) && !fp.calendarContainer.contains(e.target)) {
                    fp.close();
                }
            });
        });

        // 3. WIDGET AUTO-CLOSE (10 Detik)
        const widget = document.getElementById('autoCloseWidget');
        if (widget) {
            setTimeout(() => {
                widget.style.animation = 'slideUp 0.8s ease-in forwards';
                setTimeout(() => { widget.style.display = 'none'; }, 800);
            }, 10000);
        }
    </script>
@endsection
