@extends('User.component')

@push('css')
    {{-- CUSTOM CSS UNTUK FIX UI --}}
    <style>
        .flatpickr-calendar {
            z-index: 1050 !important;
        }

        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 20px;
            z-index: 10;
        }

        /* Fix tampilan upload */
        .custom-file-label::after {
            content: "Browse";
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        {{-- Error Handling --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success shadow-sm">{!! session('success') !!}</div>
        @endif

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('user.fasilitas') }}">Fasilitas & Jadwal</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $fasilitas->nama_fasilitas }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- SIDEBAR INFO FASILITAS --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-lg sticky-sidebar">
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center overflow-hidden"
                        style="height: 250px; position: relative;">
                        @if ($fasilitas->foto)
                            <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="{{ $fasilitas->nama_fasilitas }}"
                                style="width: 100%; height: 100%; object-fit: cover;"
                                onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                        @endif
                        <div class="{{ $fasilitas->foto ? 'd-none' : '' }} text-center text-white-50">
                            <i class="fas fa-image-slash fa-4x mb-2"></i>
                            <p class="m-0 small">Foto tidak tersedia</p>
                        </div>
                    </div>

                    <div class="card-body">
                        <h3 class="font-weight-bold">{{ $fasilitas->nama_fasilitas }}</h3>
                        <hr>
                        <p class="text-muted"><i class="fas fa-map-marker-alt mr-2 text-danger"></i>
                            {{ $fasilitas->lokasi }}</p>
                        <hr>
                        <h5 class="font-weight-bold text-success">Rp
                            {{ number_format($fasilitas->harga_sewa, 0, ',', '.') }} <small class="text-muted">/
                                Sesi</small>
                        </h5>
                        <div class="mt-3">
                            <h6><i class="fas fa-info-circle mr-1"></i> Deskripsi:</h6>
                            <p class="text-muted small text-justify">{{ $fasilitas->deskripsi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM BOOKING UTAMA --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt mr-2"></i> Form Booking Fasilitas</h5>
                    </div>
                    <div class="card-body">

                        {{-- PERHATIKAN: enctype="multipart/form-data" WAJIB ADA --}}
                        <form action="{{ route('user.checkout.store') }}" method="POST" id="bookingForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="fasilitas_id" id="fasilitas_id" value="{{ $fasilitas->id }}">
                            <input type="hidden" id="harga_per_jam_raw" value="{{ $fasilitas->harga_sewa }}">

                            {{-- 1. PILIH TANGGAL --}}
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label font-weight-bold">Pilih Tanggal</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white"><i
                                                    class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" name="tanggal_booking" id="tanggal_booking"
                                            class="form-control bg-white" placeholder="Klik untuk pilih tanggal..." readonly
                                            required>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle text-info"></i> Pilih tanggal dulu untuk melihat jam
                                        tersedia.
                                    </small>
                                </div>
                            </div>

                            <hr>

                            {{-- 2. PILIH JAM (SLOT) --}}
                            <div class="form-group">
                                <label class="font-weight-bold mb-3">Pilih Jam Main</label>

                                <div id="jadwal-loading" class="text-center py-4 d-none">
                                    <div class="spinner-border text-primary" role="status"><span
                                            class="sr-only">Loading...</span></div>
                                    <p class="mt-2 text-muted">Memuat jadwal...</p>
                                </div>

                                <div id="jadwal-placeholder" class="text-center py-5 bg-light rounded border border-dashed">
                                    <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Silakan pilih tanggal di atas.</p>
                                </div>

                                <div id="jadwal-container" class="row d-none"></div>
                                @error('jadwal_ids')
                                    <small class="text-danger font-weight-bold">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- 3. RINGKASAN HARGA --}}
                            <div class="card bg-light border-0 mt-4">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                        <i class="fas fa-receipt mr-2"></i> Ringkasan Biaya
                                    </h6>

                                    {{-- NEW: Total Sesi --}}
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Sesi:</span>
                                        <span class="font-weight-bold text-dark" id="total_sesi_display">0 Sesi</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Durasi:</span>
                                        <span class="font-weight-bold text-dark" id="total_durasi_display">0 Jam</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Total Tagihan:</span>
                                        <span class="h4 font-weight-bold text-success" id="total_harga_display">Rp 0</span>
                                    </div>

                                    {{-- Input Hidden buat dikirim ke Controller --}}
                                    <input type="hidden" name="total_durasi" id="total_durasi">
                                    <input type="hidden" name="total_bayar" id="total_bayar">
                                </div>
                            </div>

                            {{-- 4. SECTION PEMBAYARAN & UPLOAD --}}
                            <div id="payment-section"
                                class="card border-primary mt-4 d-none animate__animated animate__fadeIn">
                                <div class="card-body">
                                    <h6 class="font-weight-bold text-dark mb-3">
                                        <i class="fas fa-credit-card mr-2"></i> Pembayaran & Konfirmasi
                                    </h6>

                                    <div class="alert alert-warning border-warning">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <small class="text-uppercase text-muted">Bank Tujuan</small>
                                                <strong class="d-block text-dark h5 mb-0">Bank Jateng</strong>
                                            </div>
                                            <div class="col-md-6 text-md-right">
                                                <span class="h4 font-weight-bold text-primary">123-456-7890</span>
                                                <div class="small">a.n. Dispora Semarang</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <label class="font-weight-bold">Upload Bukti Transfer <span
                                                class="text-danger">*</span></label>

                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="bukti_bayar"
                                                    name="bukti_bayar" accept="image/*" required>
                                                <label class="custom-file-label" for="bukti_bayar">Pilih
                                                    foto/screenshot...</label>
                                            </div>
                                            <div class="input-group-append">
                                                {{-- Tombol Mata --}}
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="btn-preview-foto" disabled data-toggle="tooltip"
                                                    title="Lihat Preview Foto">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <small class="text-muted">Format: JPG, PNG. Max 2MB.</small>
                                        @error('bukti_bayar')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="btn-submit" class="btn btn-success btn-lg btn-block mt-4 shadow"
                                disabled>
                                <i class="fas fa-lock mr-2"></i> Booking & Bayar
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Pastikan SweetAlert2 sudah diload --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script Nama File Input --}}
    <script>
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            var fileName = document.getElementById("bukti_bayar").files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fasilitasId = document.getElementById('fasilitas_id').value;
            const hargaPerJam = parseInt(document.getElementById('harga_per_jam_raw').value);
            const jadwalContainer = document.getElementById('jadwal-container');
            const placeholder = document.getElementById('jadwal-placeholder');
            const loading = document.getElementById('jadwal-loading');
            const bookingForm = document.getElementById('bookingForm');

            let selectedSlots = [];

            // 1. INTERCEPT SUBMIT FORM
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const totalBayar = document.getElementById('total_bayar').value;
                    const totalDurasi = document.getElementById('total_durasi').value;
                    const formattedHarga = new Intl.NumberFormat('id-ID').format(totalBayar);

                    Swal.fire({
                        title: 'Apakah jadwal sudah sesuai?',
                        html: `
                            <div class="text-left">
                                <p class="mb-1">Anda akan membooking untuk <b>${totalDurasi} Jam</b>.</p>
                                <p class="mb-3">Total yang harus ditransfer: <b class="text-success">Rp ${formattedHarga}</b></p>
                                <div class="alert alert-warning py-2 small" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Anda dinyatakan setuju membayar & tidak dapat membatalkan sepihak jika sudah konfirmasi ini.
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Saya Yakin!',
                        cancelButtonText: 'Cek Lagi',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            bookingForm.submit();
                        }
                    });
                });
            }

            // 2. LOGIC FLAT PICKR & JADWAL
            const now = new Date();
            const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            const fp = flatpickr("#tanggal_booking", {
                locale: "id",
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "l, d F Y",
                minDate: "today",
                maxDate: endOfMonth,
                disableMobile: "true",
                defaultDate: "{{ request('date') }}",
                onReady: function(selectedDates, dateStr) {
                    if (dateStr) loadJadwal(dateStr);
                },
                onChange: function(selectedDates, dateStr) {
                    selectedSlots = [];
                    updateSummary();
                    loadJadwal(dateStr);
                }
            });

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
                            jadwalContainer.innerHTML =
                                `<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada jadwal tersedia hari ini.</p></div>`;
                            return;
                        }

                        const now = new Date();
                        const currentHour = now.getHours();
                        const todayStr = now.toISOString().split('T')[0];

                        data.forEach(slot => {
                            let isBooked = slot.status !== 'tersedia';
                            let isExpired = false;

                            if (tanggal === todayStr) {
                                const slotHour = parseInt(slot.jam_mulai.split(':')[0]);
                                if (slotHour <= currentHour) isExpired = true;
                            }

                            const jamDisplay = slot.jam_mulai.substring(0, 5) + ' - ' + slot.jam_selesai.substring(0, 5);
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3 mb-3';

                            let btnClass = 'btn-outline-primary';
                            let cursor = 'pointer';
                            let isDisabled = false;
                            let icon = '';

                            if (isExpired) {
                                btnClass = 'btn-secondary disabled border-0 text-white-50';
                                isDisabled = true;
                                icon = '<i class="fas fa-history ml-1"></i>';
                            } else if (isBooked) {
                                btnClass = 'btn-warning disabled text-dark border-warning';
                                isDisabled = true;
                                icon = '<i class="fas fa-lock ml-1"></i>';
                            }

                            // === PERUBAHAN DISINI: MENAMBAHKAN DATA START & END ===
                            col.innerHTML = `
                                <div class="btn ${btnClass} btn-block py-2 position-relative slot-btn"
                                     id="btn-slot-${slot.id}"
                                     data-start="${slot.jam_mulai}"
                                     data-end="${slot.jam_selesai}"
                                     onclick="${isDisabled ? '' : `toggleSlot(${slot.id})`}"
                                     style="cursor: ${cursor}; transition: all 0.2s;">
                                    ${jamDisplay} ${icon}
                                    ${!isDisabled ? `<input type="checkbox" name="jadwal_ids[]" id="input-slot-${slot.id}" value="${slot.id}" style="display:none;">` : ''}
                                </div>
                            `;
                            jadwalContainer.appendChild(col);
                        });
                    })
                    .catch(err => console.error(err));
            }

            window.toggleSlot = function(id) {
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
                updateSummary(); // Panggil fungsi hitung baru
            }

            function updateSummary() {
                // === LOGIKA BARU PERHITUNGAN DURASI ===
                let totalDurasi = 0;

                // 1. Total Sesi (Jumlah kotak yg dipilih)
                const totalSesi = selectedSlots.length;

                // 2. Loop untuk hitung durasi real berdasarkan jam
                selectedSlots.forEach(id => {
                    const btn = document.getElementById(`btn-slot-${id}`);
                    const startStr = btn.getAttribute('data-start'); // "08:00:00"
                    const endStr = btn.getAttribute('data-end');     // "10:00:00"

                    // Buat tanggal dummy agar bisa dihitung selisihnya
                    const dateStart = new Date("2000-01-01 " + startStr);
                    const dateEnd = new Date("2000-01-01 " + endStr);

                    // Hitung selisih milisecond
                    const diffMs = dateEnd - dateStart;

                    // Konversi ke Jam (ms -> detik -> menit -> jam)
                    const durationInHours = diffMs / (1000 * 60 * 60);

                    totalDurasi += durationInHours;
                });

                // 3. Hitung Harga Total
                const totalHarga = totalSesi * hargaPerJam;

                // 4. Update UI
                document.getElementById('total_sesi_display').innerText = totalSesi + ' Sesi'; // Tampilan Sesi
                document.getElementById('total_durasi_display').innerText = totalDurasi + ' Jam'; // Tampilan Durasi
                document.getElementById('total_harga_display').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalHarga);

                document.getElementById('total_durasi').value = totalDurasi;
                document.getElementById('total_bayar').value = totalHarga;

                // 5. Update Tombol Submit
                const submitBtn = document.getElementById('btn-submit');
                const paymentSection = document.getElementById('payment-section');

                if (selectedSlots.length > 0) {
                    paymentSection.classList.remove('d-none');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML =
                        `Bayar Sekarang Rp ${new Intl.NumberFormat('id-ID').format(totalHarga)}  <i class="fas fa-check-circle ml-2"></i>`;
                } else {
                    paymentSection.classList.add('d-none');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `Pilih jadwal dulu...`;
                }
            }

            // --- LOGIKA PREVIEW GAMBAR ---
            const fileInput = document.getElementById('bukti_bayar');
            const previewBtn = document.getElementById('btn-preview-foto');
            let currentImageBase64 = '';

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = this.files[0];
                    const label = this.nextElementSibling;

                    if (file) {
                        label.innerText = file.name;
                        if (!file.type.match('image.*')) {
                            Swal.fire('Error', 'File harus berupa gambar!', 'error');
                            this.value = '';
                            label.innerText = 'Pilih foto/screenshot...';
                            previewBtn.disabled = true;
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            currentImageBase64 = e.target.result;
                            previewBtn.disabled = false;
                            previewBtn.classList.remove('btn-outline-secondary');
                            previewBtn.classList.add('btn-outline-primary');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        label.innerText = 'Pilih foto/screenshot...';
                        previewBtn.disabled = true;
                        previewBtn.classList.remove('btn-outline-primary');
                        previewBtn.classList.add('btn-outline-secondary');
                    }
                });

                previewBtn.addEventListener('click', function() {
                    if (currentImageBase64) {
                        Swal.fire({
                            title: 'Preview Bukti Transfer',
                            imageUrl: currentImageBase64,
                            imageAlt: 'Bukti Transfer',
                            imageHeight: 400,
                            showCloseButton: true,
                            showConfirmButton: false,
                            background: '#fff',
                            backdrop: `rgba(0,0,0,0.8)`
                        });
                    }
                });
            }
        });
    </script>
@endpush
