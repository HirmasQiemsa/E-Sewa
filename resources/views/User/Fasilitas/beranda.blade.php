@extends('User.component')

@push('css')
    <style>
        /* Style Widget */
        .booking-widget-container {
            width: 100%;
            margin-bottom: 30px;
            animation: slideDown 0.5s ease-out;
            position: relative;
            z-index: 99;
        }

        /* Flatpickr Styles */
        .flatpickr-input {
            display: none;
        }

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
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .date-trigger-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Small Box Flex Container */
        .small-box {
            display: flex !important;
            flex-direction: column !important;
            position: relative !important;
            overflow: hidden !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

            /* [FIX GANTUNG] Paksa tinggi 100% dari parent column */
            height: 100% !important;
            min-height: 170px;
            /* Tambah dikit biar makin lega */
        }

        /* Inner: Flex Item yang expand */
        .small-box>.inner {
            flex: 1 !important;
            /* Dorong footer ke bawah */
            position: relative !important;
            z-index: 20 !important;
            padding: 20px 20px 10px 20px !important;
        }

        /* TYPOGRAPHY */
        .small-box .inner h4 {
            font-weight: 700 !important;
            margin-bottom: 5px !important;
            line-height: 1.2 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6) !important;
            position: relative;
            z-index: 25;

            /* [OPSIONAL] Limit 2 baris agar rapi, atau biarkan lepas */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .small-box .inner p,
        .small-box .inner small {
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6) !important;
            position: relative;
            z-index: 25;
        }

        /* [FIX ICON] Lebih Besar & Posisi Pas */
        .small-box .icon {
            position: absolute !important;
            /* Center Vertikal: 50% top - 50% translate */
            top: 50% !important;
            right: 10px !important;
            z-index: 5 !important;

            /* Ukuran diperbesar sesuai request */
            font-size: 120px !important;

            color: rgba(255, 255, 255, 0.15) !important;
            transform: translateY(-50%);
            /* Kunci posisi di tengah vertikal */
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Hover Icon: Goyang & Terang */
        .small-box:hover .icon {
            font-size: 130px !important;
            /* Zoom dikit pas hover */
            transform: translateY(-50%) rotate(-10deg);
            color: rgba(255, 255, 255, 0.25) !important;
        }

        /* Footer nempel bawah */
        .small-box>.small-box-footer {
            position: relative !important;
            z-index: 20 !important;
            margin-top: auto !important;
            /* Wajib ada biar nempel bawah */
            padding: 10px 0 !important;
            background: rgba(0, 0, 0, 0.25) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-weight: 600;
            backdrop-filter: blur(2px);
        }

        /* Background Gradient + Image */
        .facility-bg {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            z-index: 0 !important;
            opacity: 0.4;
            filter: grayscale(20%);
            transition: transform 0.6s ease, opacity 0.6s ease;
        }

        .small-box:hover .facility-bg {
            transform: scale(1.1);
            opacity: 0.6;
            filter: grayscale(0%);
        }

        /* Mobile tweak */
        @media (max-width: 768px) {
            .main-header .navbar-nav .nav-link {
                padding-right: 1rem;
                /* Beri jarak kanan tombol hamburger */
            }

            /* Agar logo tidak terlalu mepet kiri di HP */
            .main-header .navbar-nav:first-child {
                padding-left: 0.5rem;
            }
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            to {
                opacity: 0;
                transform: translateY(-50px);
                display: none;
            }
        }

        /* =========================
                   HOW TO BOOK – UX IMPROVE
                   ========================= */

        .how-to-book {
            position: relative;
        }

        /* Wrapper step */
        .step-item {
            position: relative;
        }

        /* Card step */
        .step-content {
            background: #ffffff;
            border-radius: 14px;
            padding: 28px 18px;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .step-content:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }

        /* Icon bulat */
        .step-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 26px;
            position: relative;
        }

        /* Step number */
        .step-number {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ffc107;
            color: #000;
            width: 28px;
            height: 28px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Step selesai (step 5) */
        .success-icon {
            background: linear-gradient(135deg, #20c997, #198754);
        }

        /* Text */
        .step-content h5 {
            font-size: 1.05rem;
        }

        .step-content p {
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* Garis penghubung (Desktop only) */
        @media (min-width: 992px) {
            .step-item:not(:last-child)::after {
                content: '';
                position: absolute;
                top: 50px;
                right: -12px;
                width: 24px;
                height: 2px;
                background: #dee2e6;
            }
        }

        .how-to-book-header {
            margin-bottom: 30px;
            margin-top: 20px;
            /* bebas: 24–40px */
        }
    </style>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            @if ($pendingBooking && $pendingBooking->jadwal)
                <div class="booking-widget-container" id="autoCloseWidget">
                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center"
                        style="background-color: #fff3cd; border-left: 5px solid #ffc107;">
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
                        <a href="{{ route('user.checkout.detail', $pendingBooking->id) }}"
                            class="btn btn-warning btn-sm font-weight-bold ml-3">
                            Bayar <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endif

            <div class="content-header pl-0 pr-0">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold text-dark">Ketersediaan Fasilitas</h1>
                        <p class="text-muted mb-0">Informasi jadwal dan status operasional</p>
                    </div>
                    <div class="col-sm-6">
                        <div class="float-sm-right mt-3 mt-sm-0">
                            {{-- Tombol Trigger (Biru) --}}
                            <button id="dateTrigger" class="btn btn-primary shadow-sm"
                                style="border-radius: 20px; padding: 8px 20px;">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span id="dateLabel">
                                    {{ \Carbon\Carbon::parse($selectedDate)->isoFormat('dddd, D MMMM Y') }}
                                </span>
                                <i class="fas fa-chevron-down ml-2" style="font-size: 0.8em;"></i>
                            </button>

                            {{-- Input Asli (Disembunyikan / d-none) agar tidak muncul kotak putih jelek --}}
                            <input type="text" id="datePickerInput" class="d-none" value="{{ $selectedDate }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($fasilitas as $f)
                    {{-- [FIX GANTUNG] Tambahkan 'd-flex align-items-stretch' di sini --}}
                    <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">

                        <div class="small-box {{ $f->box_class }} w-100"> {{-- Background Image Logic --}}
                            @php
                                $bgUrl = !empty($f->foto)
                                    ? asset('storage/' . $f->foto)
                                    : 'https://source.unsplash.com/random/500x300/?stadium,sport';
                            @endphp

                            <div class="facility-bg"
                                style="background-image:
                                linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.9) 100%),
                                url('{{ $bgUrl }}');">
                            </div>

                            <div class="inner">
                                <h4>{{ $f->nama_fasilitas }}</h4>

                                <p style="font-size: 0.95rem; margin-bottom: 12px; font-weight: 500;">
                                    <i class="fas fa-map-marker-alt mr-1 text-white-50"></i> {{ $f->lokasi }}
                                </p>

                                <div class="mt-2">
                                    @if ($f->status_type !== 'available')
                                        <h5 class="font-weight-bold mb-1">
                                            <i class="{{ $f->status_icon }}"></i> {{ $f->status_text }}
                                        </h5>
                                        <small class="text-white-50 d-block" style="font-size: 0.85rem; line-height: 1.3;">
                                            {{ $f->status_desc }}
                                        </small>
                                    @else
                                        <h5 class="font-weight-bold mb-1">
                                            Tersedia {{ $f->total_slot }} Slot <br>
                                            <small class="text-white-100 ml-1">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $f->jam_mulai }} – {{ $f->jam_selesai }} WIB
                                            </small>
                                        </h5>
                                    @endif

                                </div>
                            </div>

                            {{-- Icon Besar --}}
                            <div class="icon">
                                <i class="{{ $f->icon }}"></i>
                            </div>

                            {{-- Footer --}}
                            @if (in_array($f->status_type, ['closed', 'locked']))
                                <a href="#" class="small-box-footer text-white-50" style="cursor: not-allowed;">
                                    {{ $f->status_text }} <i class="fas fa-ban ml-1"></i>
                                </a>
                            @elseif ($f->status_type === 'full')
                                <a href="{{ route('user.fasilitas.detail', ['id' => $f->id, 'date' => $selectedDate]) }}"
                                    class="small-box-footer">
                                    Cek Tanggal Lain <i class="fas fa-arrow-circle-right ml-1"></i>
                                </a>
                            @else
                                {{-- available --}}
                                <a href="{{ route('user.fasilitas.detail', ['id' => $f->id, 'date' => $selectedDate]) }}"
                                    class="small-box-footer">
                                    Lihat Jadwal <i class="fas fa-arrow-circle-right ml-1"></i>
                                </a>
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light text-center shadow-sm py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada fasilitas yang ditemukan untuk tanggal ini.</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="how-to-book text-center">
                {{-- Judul dengan jarak yang sudah disesuaikan --}}
                <div class="how-to-book-header mb-4">
                    <h3 class="section-title font-weight-bold">Cara Booking Fasilitas</h3>
                </div>

                <div class="row justify-content-center px-lg-5">
                    {{-- STEP 1 --}}
                    <div class="col-lg col-md-4 col-sm-6 mb-4 step-item">
                        <div class="step-content">
                            <div class="step-icon">
                                <span class="step-number">1</span>
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h5 class="font-weight-bold mt-3">Pilih Tanggal</h5>
                            <p class="text-muted small">Cek ketersediaan di kalender.</p>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="col-lg col-md-4 col-sm-6 mb-4 step-item">
                        <div class="step-content">
                            <div class="step-icon">
                                <span class="step-number">2</span>
                                <i class="fas fa-search-location"></i>
                            </div>
                            <h5 class="font-weight-bold mt-3">Pilih Fasilitas</h5>
                            <p class="text-muted small">Pilih lapangan yang diinginkan.</p>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="col-lg col-md-4 col-sm-6 mb-4 step-item">
                        <div class="step-content">
                            <div class="step-icon">
                                <span class="step-number">3</span>
                                <i class="fas fa-clock"></i> {{-- Ganti icon biar variatif --}}
                            </div>
                            <h5 class="font-weight-bold mt-3">Pilih Slot</h5>
                            <p class="text-muted small">Tentukan jam booking Anda.</p>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="col-lg col-md-4 col-sm-6 mb-4 step-item">
                        <div class="step-content">
                            <div class="step-icon">
                                <span class="step-number">4</span>
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h5 class="font-weight-bold mt-3">Bayar DP</h5>
                            <p class="text-muted small">Lakukan pembayaran awal.</p>
                        </div>
                    </div>

                    {{-- STEP 5 --}}
                    <div class="col-lg col-md-4 col-sm-6 mb-4 step-item">
                        <div class="step-content">
                            <div class="step-icon success-icon"> {{-- Class beda untuk finish --}}
                                <span class="step-number">5</span>
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5 class="font-weight-bold mt-3">Main & Lunas</h5>
                            <p class="text-muted small">Datang, main, & lunasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateTrigger = document.getElementById("dateTrigger");
            const dateLabel = document.getElementById("dateLabel"); // Ambil elemen label teks tanggal
            const dateInput = document.getElementById("datePickerInput");

            // 1. DATA HISTORY (Biarkan seperti semula)
            const historyData = @json($userHistoryData);
            const bookedDates = Object.keys(historyData);
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

                // LOGIKA DISABLE (Sama seperti sebelumnya)
                disable: [
                    function(date) {
                        const offset = date.getTimezoneOffset();
                        const localDate = new Date(date.getTime() - (offset * 60 * 1000));
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (date >= today) return false;
                        return true;
                    }
                ],

                // LOGIKA VISUAL & KLIK
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // ... (Logika History & Invalid Date Anda biarkan sama, tidak perlu diubah) ...
                    // Salin saja logika 'onDayCreate' yang lama ke sini jika ingin fitur klik history/invalid tetap jalan
                    // Untuk ringkasnya, saya fokus ke bagian onChange di bawah ini.
                },

                // --- [BAGIAN UTAMA PERBAIKAN] ---
                onChange: function(selectedDates, dateStr, instance) {
                    instance.close(); // Tutup kalender segera

                    // 1. UBAH TEKS TOMBOL BIRU SECARA INSTANT (Biar terasa cepat)
                    if (selectedDates.length > 0) {
                        const options = {
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        };
                        // Contoh hasil: "Sabtu, 10 Januari 2026"
                        dateLabel.innerText = selectedDates[0].toLocaleDateString('id-ID', options);
                    }

                    // 2. TAMPILKAN LOADING (Menutupi delay reload)
                    Swal.fire({
                        title: 'Memuat Jadwal...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false, // User gabisa klik luar
                        showConfirmButton: false, // Hapus tombol OK
                        didOpen: () => {
                            Swal.showLoading(); // Munculkan spinner
                        }
                    });

                    // 3. RELOAD HALAMAN (Beri jeda sedikit biar animasi smooth)
                    setTimeout(() => {
                        window.location.href = "{{ route('user.fasilitas') }}?date=" + dateStr;
                    }, 200);
                }
            });

            // Toggle Button Logic
            dateTrigger.addEventListener("click", function(e) {
                e.stopPropagation();
                if (fp.isOpen) fp.close();
                else fp.open();
            });

            // Widget Auto-Close (Biarkan sama)
            const widget = document.getElementById('autoCloseWidget');
            if (widget) {
                setTimeout(() => {
                    widget.style.animation = 'slideUp 0.8s ease-in forwards';
                    setTimeout(() => {
                        widget.style.display = 'none';
                    }, 800);
                }, 10000);
            }
        });
    </script>
@endpush
