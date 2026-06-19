@extends('layouts.landing')

@section('content')
    {{-- =========================================================
         1. HERO SECTION
         ========================================================= --}}
    <div class="hero-section" id="home">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">E - Sewa Fasilitas</h1>
            <p class="hero-subtitle">
                Platform resmi penyewaan fasilitas sarana olahraga <br>
                dibawah kepemilikan Dispora Kota Semarang.
            </p>
            @guest
                <a href="{{ route('login') }}" class="btn btn-danger btn-hero">
                    <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                </a>
            @else
                <a href="#fasilitas" class="btn btn-danger btn-hero">
                    <i class="fas fa-search mr-2"></i> Cari Lapangan
                </a>
            @endguest
        </div>
    </div>

    {{-- =========================================================
         2. FEATURES SECTION
         ========================================================= --}}
    <div class="container mb-5" id="fasilitas">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-laptop-house feature-icon"></i>
                    <h5>Booking Online</h5>
                    <p class="text-muted mb-0">Cek jadwal dan booking dari mana saja tanpa harus datang ke lokasi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-tags feature-icon"></i>
                    <h5>Harga Resmi</h5>
                    <p class="text-muted mb-0">Tarif penyewaan sesuai peraturan daerah yang berlaku, tanpa pungli.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="fas fa-medal feature-icon"></i>
                    <h5>Fasilitas Standar</h5>
                    <p class="text-muted mb-0">Lapangan terawat dengan standar yang layak untuk berolahraga.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         3. FACILITIES LIST SECTION (SEARCH & GRID)
         ========================================================= --}}
    <div class="content" id="fasilitas">
        <div class="container">

            {{-- A. Header Title & Search Bar --}}
            <div class="row mb-5 align-items-center">
                <div class="col-md-5 mb-3 mb-md-0 text-center text-md-left">
                    <h2 class="font-weight-bold text-dark mb-1">Fasilitas Kami</h2>
                    <p class="text-muted m-0">Ayo, pilih tanggal dan temukan lapangan favoritmu.</p>
                </div>

                <div class="col-md-7">
                    <div class="search-date-wrapper">
                        {{-- Icon Search --}}
                        <i class="fas fa-search text-muted ml-2"></i>

                        {{-- Input Pencarian Nama Lapangan --}}
                        <input type="text" id="liveSearch" class="custom-search-input"
                            placeholder="Cari nama lapangan..." autocomplete="off">

                        <div class="vertical-divider d-none d-sm-block"></div>

                        {{-- Tombol Trigger Tanggal --}}
                        <button type="button" id="dateTrigger" class="date-btn-custom">
                            {{-- Menampilkan Tanggal yang Dipilih (Default Hari Ini jika null) --}}
                            <span id="dateLabel">
                                {{ \Carbon\Carbon::parse(request('date', now()))->isoFormat('D MMMM Y') }}
                            </span>
                            <i class="fas fa-calendar-alt text-danger" style="font-size: 1.2rem;"></i>
                        </button>

                        {{-- Input Hidden untuk Flatpickr (Wajib ada value default) --}}
                        <input type="text" id="datePickerInput" value="{{ request('date', now()->format('Y-m-d')) }}"
                            style="position: absolute; opacity: 0; height: 0; width: 0; pointer-events: none;">
                    </div>
                </div>
            </div>

            {{-- B. Grid Fasilitas --}}
            <div class="row" id="facilityContainer">
                @forelse($fasilitas as $item)
                    <div class="col-lg-4 col-md-6 mb-4 facility-item" data-name="{{ strtolower($item->nama_fasilitas) }}">

                        {{-- Logika Link Detail (Non-aktif jika status 'closed' atau 'locked') --}}
                        @php
                            $isDisabled = in_array($item->status_type ?? 'available', ['closed', 'locked']);
                            // Ganti route('user.fasilitas.detail') dengan route detail kamu yang sebenarnya
                            $detailLink = $isDisabled
                                ? '#'
                                : route('user.booking.detail', [
                                    'id' => $item->id,
                                    'date' => request('date', now()->format('Y-m-d')),
                                ]);
                        @endphp

                        <a href="{{ $detailLink }}"
                            class="facility-card-link {{ $isDisabled ? 'cursor-not-allowed' : '' }}"
                            @if ($isDisabled) onclick="return false;" @endif>

                            <div class="card border-0 shadow-sm h-100 overflow-hidden text-white facility-card"
                                style="background-image: url('{{ !empty($item->foto) ? asset('storage/' . $item->foto) : asset('img/default-court.jpg') }}');">

                                {{-- Badge Status di Pojok Kanan Atas --}}
                                @if (($item->status_type ?? 'available') == 'available' && $item->sisa_slot > 0)
                                    <span class="status-badge bg-success shadow">Buka</span>
                                @elseif(($item->status_type ?? '') == 'full' || $item->sisa_slot <= 0)
                                    {{-- Tambahin kondisi OR sisa_slot <= 0 biar otomatis merah kalau habis --}}
                                    <span class="status-badge bg-danger shadow">Habis</span>
                                @else
                                    <span class="status-badge bg-secondary shadow">Tutup</span>
                                @endif

                                {{-- Overlay Gradasi Hitam di Bawah --}}
                                <div class="bg-overlay-dark"></div>

                                {{-- Informasi Card --}}
                                <div class="card-body d-flex flex-column justify-content-end position-relative h-100 p-4">

                                    {{-- Kategori & Nama --}}
                                    <span class="badge badge-light mb-2 w-auto align-self-start"
                                        style="font-size: 0.7rem; opacity: 0.9;">
                                        {{ $item->kategori ? ucwords($item->kategori) : 'Umum' }}
                                    </span>
                                    <h3 class="font-weight-bold mb-1 card-title-text">{{ $item->nama_fasilitas }}</h3>

                                    {{-- Lokasi (Max 2 Baris) --}}
                                    <p class="mb-2 text-white-50 small location-2-line">
                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $item->lokasi }}
                                    </p>


                                    {{-- Harga & Slot Info --}}
                                    <div class="d-flex justify-content-between align-items-end mt-2 border-top border-secondary pt-3"
                                        style="border-color: rgba(255,255,255,0.2) !important;">
                                        <div>
                                            <span class="d-block text-white font-weight-bold" style="font-size: 1.1rem;">
                                                Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                                            </span>
                                            {{-- Info Slot --}}
                                            @if (($item->status_type ?? 'available') === 'available')
                                                <small class="text-warning font-weight-bold">
                                                    {{ $item->sisa_slot > 0 ? $item->sisa_slot : 0 }} Sesi Tersedia
                                                </small>
                                            @else
                                                <small class="text-white-50">Cek Tanggal Lain</small>
                                            @endif

                                        </div>

                                        {{-- Tombol Panah --}}
                                        <div class="btn btn-light rounded-circle btn-sm d-flex align-items-center justify-content-center"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-arrow-right text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-light">
                            <i class="fas fa-info-circle mr-1"></i> Belum ada fasilitas yang aktif saat ini.
                        </div>
                    </div>
                @endforelse

                {{-- Pesan Jika Pencarian Tidak Ditemukan --}}
                <div id="noResult" class="col-12 text-center py-5 d-none">
                    <i class="fas fa-search fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">Fasilitas tidak ditemukan...</h5>
                </div>
            </div>

            <hr>

        </div>
    </div>

    {{-- =========================================================
    3.5. PENGAJUAN EVENT SECTION
    ========================================================= --}}
    <div class="content bg-white" id="event">
        <div class="container py-5">
            <div class="card border-0 shadow-lg rounded-lg overflow-hidden"
                style="background: linear-gradient(135deg, #c0392b 0%, #dd4b39 100%);">
                <div class="card-body p-5 text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-4 mb-md-0">
                            <h2 class="font-weight-bold mb-2">Ingin Mengadakan Turnamen Besar?</h2>
                            <p class="opacity-90 mb-0" style="font-size: 1.1rem;">
                                Kami mendukung kegiatan positif Anda. Ajukan pemakaian fasilitas untuk event, kompetisi,
                                atau kegiatan sekolah secara resmi di sini.
                            </p>
                        </div>
                        <div class="col-md-4 text-center text-md-right">
                            @guest
                                <a href="{{ route('login') }}"
                                    class="btn btn-light btn-lg rounded-pill font-weight-bold text-danger px-5 shadow-sm">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Ajukan
                                </a>
                            @else
                                @php
                                    $today = \Carbon\Carbon::today();
                                @endphp

                                {{-- LOGIKA TOMBOL DINAMIS --}}
                                @if (
                                    empty($lastPengajuan) ||
                                    $lastPengajuan->status === 'ditolak' ||
                                    ($lastPengajuan->status === 'disetujui' && $lastPengajuan->tgl_selesai->isPast()))
                                    {{-- Kondisi: Belum pernah mengajukan, atau ditolak, atau sudah selesai --}}
                                    <a href="{{ route('user.pengajuan.index') }}"
                                        class="btn btn-light btn-lg rounded-pill font-weight-bold text-danger px-5 shadow-sm">
                                        <i class="fas fa-paper-plane mr-2"></i> Ajukan Event
                                    </a>
                                @elseif($lastPengajuan->status === 'pending')
                                    {{-- Kondisi: Sedang menunggu konfirmasi --}}
                                    <button type="button"
                                        class="btn btn-warning btn-lg rounded-pill font-weight-bold text-white px-5 shadow-sm"
                                        disabled>
                                        <i class="fas fa-hourglass-half mr-2"></i> Tunggu Konfirmasi
                                    </button>

                                @elseif($lastPengajuan->status === 'disetujui' && !$lastPengajuan->tgl_selesai->isPast())
                                    {{-- Kondisi: Disetujui dan event belum berakhir --}}
                                    <a href="{{ route('user.history') }}" onclick="loadRiwayat('event')"
                                        class="btn btn-success btn-lg rounded-pill font-weight-bold text-white px-5 shadow-sm">
                                        <i class="fas fa-calendar-check mr-2"></i> Event Saya
                                    </a>

                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- =========================================================
         4. BANTUAN SECTION & LIVE CHAT UI
         ========================================================= --}}
    <div class="content bg-white border-top" id="bantuan">
        <div class="container py-5">
            {{-- Header Bantuan --}}
            <div class="row justify-content-center mb-5">
                <div class="col-md-8 text-center">
                    <h2 class="font-weight-bold text-dark">Pusat Bantuan</h2>
                    <p class="text-muted">Masih bingung? Tenang, kami kumpulkan pertanyaan yang sering muncul di sini.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="accordion shadow-sm rounded overflow-hidden" id="faqAccordion">

                        {{-- FAQ 1: Cara Kerja --}}
                        <div class="card border-0 mb-1">
                            <div class="card-header bg-light p-0" id="headingOne">
                                <h5 class="mb-0">
                                    <button
                                        class="btn btn-link btn-block text-left text-dark font-weight-bold py-3 px-4 d-flex justify-content-between align-items-center"
                                        type="button" data-toggle="collapse" data-target="#collapseOne">
                                        Bagaimana cara booking lapangan?
                                        <i class="fas fa-chevron-down text-danger small"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" data-parent="#faqAccordion">
                                <div class="card-body bg-white text-muted px-4 pb-4">
                                    Sangat mudah! Pastikan Anda sudah login, lalu pergi ke menu <b>Fasilitas</b>. Pilih
                                    lapangan yang diinginkan, klik detail, lalu pilih tanggal dan jam yang tersedia (warna
                                    putih). Setelah itu lakukan pembayaran dan upload bukti transfer.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 2: Pengajuan Event --}}
                        <div class="card border-0 mb-1">
                            <div class="card-header bg-light p-0" id="headingTwo">
                                <h5 class="mb-0">
                                    <button
                                        class="btn btn-link btn-block text-left text-dark font-weight-bold py-3 px-4 d-flex justify-content-between align-items-center"
                                        type="button" data-toggle="collapse" data-target="#collapseTwo">
                                        Apa bedanya Booking biasa dengan Pengajuan Event?
                                        <i class="fas fa-chevron-down text-danger small"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body bg-white text-muted px-4 pb-4">
                                    <b>Booking Biasa</b> digunakan untuk latihan rutin atau main santai dengan durasi
                                    hitungan jam. Sedangkan <b>Pengajuan Event</b> digunakan jika Anda ingin mengadakan
                                    turnamen atau acara besar yang memblokir jadwal seharian atau berhari-hari. Pengajuan
                                    event memerlukan persetujuan admin dan upload proposal.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 3: After Payment --}}
                        <div class="card border-0 mb-1">
                            <div class="card-header bg-light p-0" id="headingThree">
                                <h5 class="mb-0">
                                    <button
                                        class="btn btn-link btn-block text-left text-dark font-weight-bold py-3 px-4 d-flex justify-content-between align-items-center"
                                        type="button" data-toggle="collapse" data-target="#collapseThree">
                                        Saya sudah bayar dan upload bukti, selanjutnya apa?
                                        <i class="fas fa-chevron-down text-danger small"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body bg-white text-muted px-4 pb-4">
                                    Mohon tunggu sebentar. Admin Keuangan kami sedang memverifikasi mutasi rekening. Status
                                    Anda akan berubah dari <i>Pending</i> menjadi <i>Lunas</i> maksimal dalam 1x24 jam. Anda
                                    akan menerima notifikasi di dashboard jika sudah divalidasi.
                                </div>
                            </div>
                        </div>

                        {{-- FAQ 4: Komplain / Salah Bayar --}}
                        <div class="card border-0 mb-1">
                            <div class="card-header bg-light p-0" id="headingFour">
                                <h5 class="mb-0">
                                    <button
                                        class="btn btn-link btn-block text-left text-dark font-weight-bold py-3 px-4 d-flex justify-content-between align-items-center"
                                        type="button" data-toggle="collapse" data-target="#collapseFour">
                                        Bagaimana jika booking saya ditolak atau salah transfer?
                                        <i class="fas fa-chevron-down text-danger small"></i>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseFour" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body bg-white text-muted px-4 pb-4">
                                    Jangan panik. Dana Anda aman. Silakan hubungi <b>Call Center</b> di bawah ini untuk
                                    mengajukan protes atau klarifikasi pembayaran. Sertakan foto bukti transfer ulang jika
                                    diperlukan. Admin Fasilitas dan Kepala Dinas memantau percakapan ini untuk transparansi.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- =========================================================
            5. CALL CENTER SECTION
         ========================================================= --}}


    {{-- Tombol Call Center --}}
    <div class="w-100 mb-2 text-center">
        <a href="tel:0243546001" class="btn btn-outline-danger rounded-pill px-4 py-2">
            <i class="fas fa-phone-alt mr-2"></i>
            Hubungi Call Center (024-3546001)
        </a>
        <small class="d-block text-muted mt-1">
            Anda akan diarahkan ke panggilan telepon
        </small>
    </div>
@endsection

{{-- =========================================================
     STACK CSS (STYLE KHUSUS HALAMAN INI)
     ========================================================= --}}
@push('css')
    <style>
        /* --- HERO SECTION --- */
        .hero-section {
            position: relative;
            background-image: url('{{ asset('img/sports-facilities-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 550px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: -80px;
            padding-top: 80px;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 20px;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
        }

        .btn-hero {
            padding: 12px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
        }

        /* --- FEATURES --- */
        .feature-box {
            padding: 30px 20px;
            text-align: center;
            background: white;
            border-radius: 10px;
            margin-top: -50px;
            position: relative;
            z-index: 3;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .step-icon {
            font-size: 2rem;
            color: #dc3545;
            margin-bottom: 10px;
        }

        /* --- SEARCH & DATE WRAPPER --- */
        .search-date-wrapper {
            background: #fff;
            border-radius: 50px;
            padding: 5px 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            height: 60px;
            width: 100%;
            border: 1px solid #f0f0f0;
            position: relative;
        }

        .custom-search-input {
            border: none;
            background: transparent;
            height: 100%;
            width: 100%;
            padding-left: 15px;
            font-size: 1rem;
            outline: none;
        }

        .vertical-divider {
            width: 1px;
            height: 30px;
            background-color: #e0e0e0;
            margin: 0 15px;
        }

        .date-btn-custom {
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            color: #333;
            font-weight: 600;
            padding-right: 15px;
            cursor: pointer;
            height: 100%;
            outline: none;
        }

        .date-btn-custom:hover {
            color: #dc3545;
        }

        /* Fix Calendar Z-Index */
        .flatpickr-calendar {
            z-index: 99999 !important;
        }

        /* --- FACILITY CARD STYLE (NEW) --- */
        .facility-card-link {
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            transition: transform 0.2s;
            height: 100%;
        }

        .facility-card-link:hover {
            transform: translateY(-5px);
        }

        .facility-card-link.cursor-not-allowed {
            cursor: not-allowed;
            opacity: 0.8;
        }

        .facility-card {
            border-radius: 15px;
            min-height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
            transition: all 0.3s;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            z-index: 10;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-overlay-dark {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0) 100%);
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 80%;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            pointer-events: none;
        }

        .card-title-text {
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        /* --- CHAT MODAL STYLE --- */
        /* --- FLOATING BUTTON --- */
        .floating-chat-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 4px solid #fff;
        }

        .floating-chat-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 1rem 3rem rgba(220, 53, 69, 0.3) !important;
        }

        /* --- CHAT MODAL --- */
        .online-indicator {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            background-color: #2ecc71;
            border: 2px solid #dc3545;
            border-radius: 50%;
        }

        .chat-bubble-admin {
            background: #ffffff;
            color: #333;
            padding: 10px 15px;
            border-radius: 0px 15px 15px 15px;
            max-width: 80%;
            position: relative;
            font-size: 0.95rem;
        }

        .chat-bubble-user {
            background: #dc3545;
            /* Merah Dispora */
            color: #fff;
            padding: 10px 15px;
            border-radius: 15px 0px 15px 15px;
            max-width: 80%;
            position: relative;
            font-size: 0.95rem;
        }

        /* Scrollbar Chat yang tipis */
        #chatBody::-webkit-scrollbar {
            width: 6px;
        }

        #chatBody::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #chatBody::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        #chatBody::-webkit-scrollbar-thumb:hover {
            background: #dc3545;
        }
    </style>
@endpush

{{-- =========================================================
     STACK SCRIPTS (JS KHUSUS HALAMAN INI)
     ========================================================= --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* =====================================================
             * 1. SCROLLSPY SEDERHANA
             * ===================================================== */
            (() => {
                const sections = document.querySelectorAll('div[id]');
                const navLinks = document.querySelectorAll('.nav-scroll-spy .nav-link');

                if (!sections.length || !navLinks.length) return;

                window.addEventListener('scroll', () => {
                    let current = '';
                    sections.forEach(section => {
                        if (window.pageYOffset >= section.offsetTop - 150) {
                            current = section.id;
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.toggle(
                            'active',
                            current && link.getAttribute('href').includes(current)
                        );
                    });
                });
            })();

            /* =====================================================
             * 2. LIVE SEARCH FASILITAS
             * ===================================================== */
            (() => {
                const searchInput = document.getElementById('liveSearch');
                const items = document.querySelectorAll('.facility-item');
                const noResult = document.getElementById('noResult');

                if (!searchInput || !items.length) return;

                searchInput.addEventListener('keyup', e => {
                    const term = e.target.value.toLowerCase();
                    let visible = false;

                    items.forEach(item => {
                        const match = item.dataset.name?.includes(term);
                        item.classList.toggle('d-none', !match);
                        item.classList.toggle('d-block', match);
                        if (match) visible = true;
                    });

                    noResult?.classList.toggle('d-none', visible);
                });
            })();

            /* =====================================================
             * 3. FLATPICKR – BOOKING DATE
             * ===================================================== */
            (() => {
                const dateInput = document.getElementById("datePickerInput");
                const dateTrigger = document.getElementById("dateTrigger");

                if (!dateInput || typeof flatpickr === 'undefined') return;

                const fp = flatpickr(dateInput, {
                    locale: "id",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    maxDate: new Date().fp_incr(30),
                    disableMobile: true,
                    positionElement: dateTrigger,
                    clickOpens: true,
                    onChange: (_, dateStr) => {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Memuat Jadwal...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading(),
                                backdrop: 'rgba(0,0,0,0.8)'
                            });
                        }

                        const url = new URL(window.location.href);
                        url.searchParams.set('date', dateStr);
                        url.hash = 'fasilitas';
                        window.location.href = url.toString();
                    }
                });

                dateTrigger?.addEventListener('click', e => {
                    e.stopPropagation();
                    fp.open();
                });
            })();

            /* =====================================================
             * 4. FLATPICKR – EVENT DATE
             * ===================================================== */
            if (typeof flatpickr !== 'undefined') {
                flatpickr('.event-date', {
                    locale: "id",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disableMobile: true,
                    allowInput: false
                });
            }




        });
    </script>
@endpush
