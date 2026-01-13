@extends('User.component')

@push('css')
    {{-- CUSTOM CSS UNTUK FIX UI --}}
    <style>
        /* Fix Flatpickr ketimpa elemen lain */
        .flatpickr-calendar {
            z-index: 1050 !important;
            /* Di atas sticky top & modal bootstrap */
        }

        /* Fix Sticky Sidebar agar tidak menutupi alert/modal */
        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 20px;
            z-index: 10;
            /* Z-index rendah agar tidak menutupi alert */
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        {{-- DEBUG: TAMPILKAN SEMUA ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('user.fasilitas') }}">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $fasilitas->nama_fasilitas }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4 mb-4">
                {{-- FIX: Ganti inline style dengan class sticky-sidebar --}}
                <div class="card shadow-sm border-0 rounded-lg sticky-sidebar">
                    {{-- FIX IMAGE: Tambahkan onerror untuk fallback image jika gagal load --}}
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center overflow-hidden"
                        style="height: 250px; position: relative;">

                        {{-- 1. Cek apakah ada data foto di database --}}
                        @if($fasilitas->foto)
                            <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="{{ $fasilitas->nama_fasilitas }}"
                                style="width: 100%; height: 100%; object-fit: cover;"
                                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                        @endif

                        {{-- 2. Fallback Icon (Akan muncul jika foto null ATAU error loading) --}}
                        {{-- Class 'd-none' ditambahkan jika foto ada, tapi akan dihapus oleh JS onerror jika gagal load
                        --}}
                        <div class="{{ $fasilitas->foto ? 'd-none' : '' }} text-center text-white-50">
                            <i class="fas fa-image-slash fa-4x mb-2"></i>
                            <p class="m-0 small">Foto tidak tersedia</p>
                        </div>

                    </div>

                    <div class="card-body">
                        <h3 class="font-weight-bold">{{ $fasilitas->nama_fasilitas }}</h3>
                        <div class="mb-3">
                            <span class="badge {{ $fasilitas->icon_color ?? 'bg-primary' }} p-2">
                                {{ $fasilitas->kategori ?? 'Olahraga' }}
                            </span>
                        </div>

                        <p class="text-muted"><i class="fas fa-map-marker-alt mr-2 text-danger"></i>
                            {{ $fasilitas->lokasi }}</p>

                        <hr>

                        <h5 class="font-weight-bold text-success">Rp
                            {{ number_format($fasilitas->harga_sewa, 0, ',', '.') }} <small class="text-muted">/ jam</small>
                        </h5>

                        <div class="mt-3">
                            <h6><i class="fas fa-info-circle mr-1"></i> Deskripsi:</h6>
                            <p class="text-muted small text-justify">{{ $fasilitas->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i> Cek Ketersediaan & Booking</h5>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('user.checkout.store') }}" method="POST" id="bookingForm">
                            @csrf
                            <input type="hidden" name="fasilitas_id" id="fasilitas_id" value="{{ $fasilitas->id }}">
                            <input type="hidden" id="harga_per_jam_raw" value="{{ $fasilitas->harga_sewa }}">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Pilih Tanggal</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i
                                                    class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" name="tanggal_booking" id="tanggal_booking"
                                            class="form-control bg-white" placeholder="Pilih tanggal main..." readonly
                                            required>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle text-info"></i> Jadwal booking diperbarui setiap bulan
                                        sekali.
                                    </small>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group">
                                <label class="font-weight-bold mb-3">Pilih Slot Waktu</label>

                                <div id="jadwal-loading" class="text-center py-4 d-none">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Memuat jadwal...</p>
                                </div>

                                <div id="jadwal-placeholder" class="text-center py-5 bg-light rounded border border-dashed">
                                    <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Silakan pilih tanggal terlebih dahulu untuk melihat slot waktu.
                                    </p>
                                </div>

                                <div id="jadwal-container" class="row d-none">
                                </div>
                                @error('jadwal_ids') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            {{-- RINGKASAN PESANAN (Tetap Sama) --}}
                            <div class="card bg-light border-0 mt-4">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-receipt mr-2"></i> Ringkasan Pesanan
                                    </h6>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Durasi:</span>
                                        <span class="font-weight-bold text-dark" id="total_durasi_display">0 Jam</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Total Harga Sewa:</span>
                                        <span class="font-weight-bold text-dark" id="total_harga_display">Rp 0</span>
                                    </div>

                                    <div
                                        class="bg-white rounded p-3 border border-warning d-flex justify-content-between align-items-center shadow-sm">
                                        <div>
                                            <span class="d-block font-weight-bold text-dark" style="font-size: 0.9rem;">DP
                                                Yang Harus Dibayar (50%)</span>
                                            <small class="text-muted">Dibayar sekarang via transfer</small>
                                        </div>
                                        <span class="font-weight-bold text-warning" id="dp_display"
                                            style="font-size: 1.4rem;">Rp 0</span>
                                    </div>

                                    <input type="hidden" name="total_durasi" id="total_durasi">
                                    <input type="hidden" name="total_bayar" id="total_bayar">
                                    <input type="hidden" name="dp_value" id="dp_value">
                                </div>
                            </div>

                            <button type="submit" id="btn-submit" class="btn btn-success btn-lg btn-block mt-3 shadow-sm"
                                disabled>
                                Lanjut ke Pembayaran <i class="fas fa-arrow-right ml-2"></i>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fasilitasId = document.getElementById('fasilitas_id').value;
            const hargaPerJam = parseInt(document.getElementById('harga_per_jam_raw').value);
            const jadwalContainer = document.getElementById('jadwal-container');
            const placeholder = document.getElementById('jadwal-placeholder');
            const loading = document.getElementById('jadwal-loading');

            let selectedSlots = [];

            const now = new Date();
            const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            // 1. Inisialisasi Flatpickr (Kalender)
            const fp = flatpickr("#tanggal_booking", {
                locale: "id",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "l, d F Y",
                minDate: "today",
                maxDate: endOfMonth,
                disableMobile: "true",
                defaultDate: "{{ request('date') }}", // Hapus date('Y-m-d') agar tidak otomatis load hari ini jika kosong

                onReady: function (selectedDates, dateStr, instance) {
                    if (dateStr) {
                        loadJadwal(dateStr);
                    }
                },

                onChange: function (selectedDates, dateStr, instance) {
                    selectedSlots = [];
                    updateSummary();
                    loadJadwal(dateStr);
                },

                onDayCreate: function (dObj, dStr, fp, dayElem) {
                    if (dayElem.classList.contains("flatpickr-disabled")) {
                        dayElem.addEventListener("click", function () {
                            // FIX: Tutup kalender dulu sebelum alert muncul
                            fp.close();

                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Jadwal Telah Ditutup',
                                    text: 'Saat ini hanya tersedia untuk hari yang akan datang.',
                                    confirmButtonColor: '#007bff'
                                });
                            }
                        });
                    }
                }
            });

// FUNGSI: Load Jadwal (Revisi Visual: Expired vs Booked)
            function loadJadwal(tanggal) {
                placeholder.classList.add('d-none');
                jadwalContainer.classList.add('d-none');
                jadwalContainer.innerHTML = '';
                loading.classList.remove('d-none');

                fetch(`/api/check-jadwal/${fasilitasId}/${tanggal}`)
                    .then(res => res.json())
                    .then(data => {
                        loading.classList.add('d-none');
                        jadwalContainer.classList.remove('d-none');

                        if (data.length === 0) {
                            jadwalContainer.innerHTML = `<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada jadwal tersedia.</p></div>`;
                            return;
                        }

                        // --- PREPARE DATA WAKTU SEKARANG ---
                        const now = new Date();
                        const currentHour = now.getHours();
                        const year = now.getFullYear();
                        const month = String(now.getMonth() + 1).padStart(2, '0');
                        const day = String(now.getDate()).padStart(2, '0');
                        const todayStr = `${year}-${month}-${day}`;

                        data.forEach(slot => {
                            // 1. Status Awal (Dari Database)
                            let isBooked = slot.status !== 'tersedia';
                            let isExpired = false;

                            // 2. TIME VALIDATION (Expired check)
                            if (tanggal === todayStr) {
                                const slotHour = parseInt(slot.jam_mulai.split(':')[0]);
                                if (slotHour <= currentHour) {
                                    isExpired = true;
                                }
                            }

                            // 3. RENDER VISUAL
                            const jamDisplay = slot.jam_mulai.substring(0, 5) + ' - ' + slot.jam_selesai.substring(0, 5);
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3 mb-3';

                            let btnClass = '';
                            let cursor = '';
                            let icon = '';
                            let isDisabled = false;

                            // --- LOGIKA WARNA TOMBOL ---
                            if (isExpired) {
                                // KASUS 1: EXPIRED (Lewat Jam) -> Abu-abu Gelap
                                btnClass = 'btn-secondary disabled border-0 text-white-50';
                                cursor = 'not-allowed';
                                icon = '<i class="fas fa-history ml-1"></i>';
                                isDisabled = true;

                            } else if (isBooked) {
                                // KASUS 2: BOOKED (Punya Orang) -> KUNING (Warning)
                                // Kita pakai btn-warning, text-dark biar kontras
                                btnClass = 'btn-warning disabled text-dark border-warning';
                                cursor = 'not-allowed';
                                icon = '<i class="fas fa-lock ml-1"></i>';
                                isDisabled = true;

                            } else {
                                // KASUS 3: TERSEDIA -> Biru (Primary)
                                btnClass = 'btn-outline-primary';
                                cursor = 'pointer';
                                icon = '';
                                isDisabled = false;
                            }

                            // Render HTML
                            col.innerHTML = `
                                <div class="btn ${btnClass} btn-block py-2 position-relative slot-btn"
                                     id="btn-slot-${slot.id}"
                                     onclick="${isDisabled ? '' : `toggleSlot(${slot.id}, false)`}"
                                     style="cursor: ${cursor}; transition: all 0.2s;">
                                    ${jamDisplay} ${icon}

                                    ${!isDisabled ? `<input type="checkbox" name="jadwal_ids[]" id="input-slot-${slot.id}" value="${slot.id}" style="display:none;">` : ''}
                                </div>
                            `;
                            jadwalContainer.appendChild(col);
                        });
                    })
                    .catch(err => {
                        loading.classList.add('d-none');
                        console.error(err);
                    });
            }

            // FUNGSI: Toggle Pilihan Slot
            window.toggleSlot = function (id, isBooked) {
                if (isBooked) return;

                const btn = document.getElementById(`btn-slot-${id}`);
                const input = document.getElementById(`input-slot-${id}`);
                const index = selectedSlots.indexOf(id);

                if (index > -1) {
                    selectedSlots.splice(index, 1);
                    btn.classList.remove('btn-primary', 'text-white', 'shadow');
                    btn.classList.add('btn-outline-primary');
                    input.checked = false;
                } else {
                    selectedSlots.push(id);
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-primary', 'text-white', 'shadow');
                    input.checked = true;
                }
                updateSummary();
            }

            function updateSummary() {
                const totalDurasi = selectedSlots.length;
                const totalHarga = totalDurasi * hargaPerJam;
                const dp = totalHarga * 0.5;

                document.getElementById('total_durasi_display').innerText = totalDurasi + ' Jam';
                document.getElementById('total_harga_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);
                document.getElementById('dp_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(dp);

                document.getElementById('total_durasi').value = totalDurasi;
                document.getElementById('total_bayar').value = totalHarga;
                document.getElementById('dp_value').value = dp;

                const submitBtn = document.getElementById('btn-submit');
                submitBtn.disabled = (totalDurasi === 0);

                if (totalDurasi > 0) {
                    submitBtn.innerHTML = `Lanjut Bayar DP (Rp ${new Intl.NumberFormat('id-ID').format(dp)}) <i class="fas fa-arrow-right ml-2"></i>`;
                } else {
                    submitBtn.innerHTML = `Lanjut ke Pembayaran <i class="fas fa-arrow-right ml-2"></i>`;
                }
            }
        });
    </script>
@endpush
