@extends('layouts.landing')

@section('content')
    {{-- Header Section --}}
    <div class="content-header bg-black shadow-sm mb-4" style="padding-top: 110px; padding-bottom: 20px;">
        <div class="container bg-white p-4 rounded-lg shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="font-weight-bold text-dark" style="font-size: 1.8rem;">Checkout Booking</h1>
                    <p class="text-muted mb-0">Selesaikan pembayaran untuk mengamankan jadwalmu.</p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('user.beranda') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="content pb-5">
        <div class="container">
            {{-- Alert Error --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-lg border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-lg mr-3"></i>
                        <div><strong>Oops!</strong> {{ session('error') }}</div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <form action="{{ route('user.booking.store') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                @csrf
                <input type="hidden" name="fasilitas_id" value="{{ $fasilitas->id }}">
                {{-- Input Tanggal untuk Backend (Disimpan saat Submit) --}}
                <input type="hidden" name="tanggal_booking" value="{{ $date }}">

                <div class="row">
                    {{-- LEFT COLUMN --}}
                    <div class="col-lg-8">
                        {{-- 1. Info Fasilitas --}}
                        <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                            <div class="d-flex flex-column flex-md-row">
                                <div class="bg-light d-flex align-items-center justify-content-center p-3"
                                    style="min-width: 200px;">
                                    <img src="{{ $fasilitas->foto ? asset('storage/' . $fasilitas->foto) : asset('img/default-court.jpg') }}"
                                        class="rounded shadow-sm"
                                        style="width: 100%; max-width: 180px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="p-4 flex-grow-1">
                                    <h4 class="font-weight-bold mb-2">{{ $fasilitas->nama_fasilitas }}</h4>
                                    <p class="text-muted mb-2"><i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                        {{ $fasilitas->lokasi }}</p>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <span class="badge badge-success px-3 py-2 rounded-pill mr-2 mb-2">
                                            <i class="fas fa-tag mr-1"></i>
                                            @if ($minPrice != $maxPrice)
                                                Rp {{ number_format($minPrice, 0, ',', '.') }} -
                                                {{ number_format($maxPrice, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                                            @endif
                                        </span>
                                        <span class="badge badge-info px-3 py-2 rounded-pill mb-2" id="infoKalenderBadge">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Pilih Jadwal --}}
                        <div class="card border-0 shadow-sm rounded-lg mb-4">
                            <div
                                class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="font-weight-bold mb-0"><i class="far fa-clock text-primary mr-2"></i> Pilih
                                        Jadwal</h5>
                                    <small class="text-muted">Pilih slot waktu bermain Anda.</small>
                                </div>
                                <div class="position-relative">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-light text-dark border shadow-sm font-weight-bold"
                                        id="miniCalendarBtn">
                                        <i class="fas fa-calendar-day text-danger mr-1"></i> Ubah Tanggal
                                    </button>
                                    <input type="text" id="miniCalendarInput" value="{{ $date }}"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                                </div>
                            </div>
                            <div class="card-body px-4 pb-4">
                                <div class="row" id="jadwalContainer">
                                    <div class="col-12 text-center py-5">
                                        <div class="spinner-border text-primary" role="status"><span
                                                class="sr-only">Loading...</span></div>
                                        <p class="mt-2 text-muted">Memuat jadwal...</p>
                                    </div>
                                </div>
                                @error('jadwal_ids')
                                    <div class="text-danger mt-2 small"><i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- 3. Instruksi Pembayaran (Hidden Awal - Muncul saat Pilih Jadwal) --}}
                        <div class="card border-0 shadow-sm rounded-lg mb-4 d-none" id="paymentInstructionCard">
                            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                                <h5 class="font-weight-bold mb-0"><i class="fas fa-wallet text-success mr-2"></i> Instruksi
                                    Pembayaran</h5>
                            </div>
                            <div class="card-body px-4">
                                <div class="alert alert-light border rounded-lg">
                                    <div class="d-flex">
                                        <div class="mr-3"><i class="fas fa-info-circle fa-2x text-info"></i></div>
                                        <div>
                                            <p class="mb-1 small text-muted">Silakan transfer ke rekening berikut:</p>
                                            <p class="mb-0 font-weight-bold text-dark copy-text"
                                                style="font-family: monospace; font-size: 1.1rem;">
                                                BCA 123-456-7890 <i class="far fa-copy ml-2 text-muted"
                                                    style="cursor: pointer;" title="Salin"></i>
                                            </p>
                                            <small class="text-muted">a.n Dispora Semarang</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 4. Upload Bukti (Hidden Awal - Muncul saat Pilih Jadwal) --}}
                        <div class="card border-0 shadow-sm rounded-lg mb-4 d-none" id="uploadBuktiCard">
                            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                                <h5 class="font-weight-bold mb-0"><i
                                        class="fas fa-cloud-upload-alt text-secondary mr-2"></i> Upload Bukti</h5>
                            </div>
                            <div class="card-body px-4">
                                <div class="form-group">
                                    <label class="font-weight-bold">Foto Struk / Screenshot</label>
                                    <div class="custom-file-upload-zone text-center p-4 border rounded bg-light"
                                        onclick="document.getElementById('bukti_bayar').click()"
                                        style="border-style: dashed !important; cursor: pointer; transition: all 0.3s;">
                                        <input type="file" name="bukti_bayar" id="bukti_bayar" class="d-none"
                                            accept="image/*" onchange="previewImage(this)">
                                        <div id="uploadPlaceholder">
                                            <i class="fas fa-camera fa-2x text-muted mb-3"></i>
                                            <p class="mb-0 text-muted font-weight-bold">Klik untuk upload bukti bayar</p>
                                            <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                                        </div>
                                        <img id="imagePreview" src="#" alt="Preview"
                                            class="img-fluid rounded shadow-sm d-none" style="max-height: 200px;">
                                    </div>
                                    @error('bukti_bayar')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: SUMMARY --}}
                    <div class="col-lg-4">
                        <div class="sticky-top" style="top: 100px; z-index: 1020;">
                            <div class="card border-0 shadow-sm rounded-lg bg-white">
                                <div class="card-body p-4">
                                    {{-- Icon Receipt Dipindah Sini --}}
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="icon-box bg-warning-light rounded-circle p-2 mr-3 text-warning">
                                            <i class="fas fa-receipt fa-lg"></i>
                                        </div>
                                        <h5 class="font-weight-bold mb-0">Ringkasan Pesanan</h5>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Fasilitas</span>
                                        <span class="font-weight-bold text-right text-truncate"
                                            style="max-width: 150px;">{{ $fasilitas->nama_fasilitas }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Tanggal</span>
                                        <span class="font-weight-bold"
                                            id="summaryDate">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                                    </div>

                                    {{-- Lama Sesi (New Feature) --}}
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Lama Sesi</span>
                                        <span class="font-weight-bold" id="durationDisplay">-</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                                        <span class="text-muted">Total Slot</span>
                                        <span class="font-weight-bold" id="slotCountDisplay">0</span>
                                    </div>

                                    {{-- List Jam Dipilih --}}
                                    <div id="selectedSlotsContainer" class="mb-3 d-none">
                                        <small class="text-muted d-block mb-2">Detail Jam:</small>
                                        <div id="slotsList" class="d-flex flex-wrap gap-1"></div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h5 class="mb-0 font-weight-bold">Total Bayar</h5>
                                        <h4 class="mb-0 font-weight-bold text-danger" id="totalBayarDisplay">Rp 0</h4>
                                        <input type="hidden" name="total_bayar" id="totalBayarInput" value="0">
                                    </div>

                                    {{-- Tombol Trigger Modal --}}
                                    <button type="button"
                                        class="btn btn-danger btn-block btn-lg rounded-pill font-weight-bold shadow-sm"
                                        id="btnPreSubmit" disabled>
                                        <i class="fas fa-lock mr-2"></i> Bayar Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL VERIFIKASI AKHIR --}}
                <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 rounded-lg">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title font-weight-bold">Konfirmasi Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info small">
                                    Pastikan tanggal dan jam sudah benar. Bukti pembayaran akan diverifikasi oleh Admin.
                                </div>
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="agreeCheck">
                                        <label class="custom-control-label small text-muted" for="agreeCheck">
                                            Saya setuju atas pembayaran dari booking pemesanan ini dan telah membaca aturan
                                            yang berlaku.
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-light rounded-pill"
                                    data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger rounded-pill px-4" id="btnFinalSubmit"
                                    disabled>
                                    Konfirmasi & Bayar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </section>
@endsection

@push('css')
    <style>
        /* 1. Slot Jadwal Full Height & Overlapping Checkbox */
        .jadwal-card-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
            z-index: 5;
        }

        .jadwal-col {
            position: relative;
        }

        .jadwal-card-label {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: #fff;
            border: 1px solid #e9ecef;
            /* Border Default Halus */
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            transition: all 0.2s ease;
            height: 100%;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* --- UPDATE: HOVER STATE KALEM (TANPA UBAH WARNA TEKS) --- */
        /* Hanya ubah border jadi merah dan kasih shadow lebih kuat */
        .jadwal-card-input:not(:disabled):hover+.jadwal-card-label {
            border-color: #dc3545;
            background-color: #ffffff;
            /* Tetap putih atau sangat muda */
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* HAPUS SEMUA CSS YANG MEMAKSA WARNA TEKS SAAT HOVER */
        /* Text tetap mengikuti warna aslinya (hitam/muted) */

        /* Selected State (Saat Dipilih) - Ini baru boleh berubah drastis */
        .jadwal-card-input:checked+.jadwal-card-label {
            background-color: #ffffff;
            border-color: #dc3545;
            color: rgb(255, 39, 39) !important;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }

        /* Pastikan teks putih SAAT DIPILIH saja */
        .jadwal-card-input:checked+.jadwal-card-label h6,
        .jadwal-card-input:checked+.jadwal-card-label small,
        .jadwal-card-input:checked+.jadwal-card-label span {
            color: rgba(240, 42, 42, 0.9) !important;
        }

        /* Style Khusus Kondisi Lain (Tetap) */
        .jadwal-card-input:disabled.status-booked+.jadwal-card-label {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
            cursor: not-allowed;
        }

        .jadwal-card-input:disabled.status-expired+.jadwal-card-label {
            background-color: #e9ecef;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
        }

        .jadwal-card-input:not(:disabled).status-late+.jadwal-card-label {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        .status-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            padding: 2px 6px;
            border-radius: 4px;
            display: block;
        }

        .bg-warning-light {
            background-color: #fff3cd;
        }

        .custom-file-upload-zone:hover {
            background-color: #f8f9fa !important;
            border-color: #dc3545 !important;
        }

        /* --- PERMANENT DARK TEXT (Agar text muted terbaca jelas tanpa hover) --- */
        .jadwal-card-label .text-muted {
            color: #343a40 !important;
            font-weight: 500;
        }

        .jadwal-card-label h6 {
            color: #000 !important;
        }

        /* Kecualikan yang disabled */
        .jadwal-card-input:disabled+.jadwal-card-label .text-muted,
        .jadwal-card-input:disabled+.jadwal-card-label h6 {
            color: #6c757d !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fasilitasId = "{{ $fasilitas->id }}";
            const tanggalDipilih = "{{ $date }}";
            const defaultHarga = {{ $fasilitas->harga_sewa }};
            const jadwalContainer = document.getElementById('jadwalContainer');

            // Server time setup (Untuk sinkronisasi waktu client-server yang akurat)
            // Menggunakan waktu server PHP agar user tidak bisa cheat ganti jam laptop
            const serverTimeNow = new Date("{{ now()->format('Y-m-d H:i:s') }}");

            // 1. Setup Kalender Kecil (Flatpickr)
            flatpickr("#miniCalendarInput", {
                defaultDate: tanggalDipilih,
                minDate: "today",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    // Logika Mengubah Kalender: Reload halaman dengan query param baru
                    // Route Store tetap aman karena ID di form tidak berubah
                    window.location.href = `/fasilitas/${fasilitasId}?date=${dateStr}`;
                }
            });

            // 2. Load Jadwal AJAX
            fetch(`/api/check-jadwal/${fasilitasId}/${tanggalDipilih}`)
                .then(res => res.json())
                .then(data => {
                    jadwalContainer.innerHTML = '';

                    if (data.length === 0) {
                        jadwalContainer.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <div class="alert alert-warning border-0 shadow-sm">
                            <i class="fas fa-calendar-times mr-2"></i> Tidak ada jadwal tersedia.
                        </div>
                    </div>`;
                        return;
                    }

                    data.forEach(item => {
                        // Parsing Waktu
                        const jamMulaiParts = item.jam_mulai.split(':');
                        const slotDate = new Date(tanggalDipilih);
                        slotDate.setHours(jamMulaiParts[0], jamMulaiParts[1], 0);

                        // Hitung Selisih Waktu (Miliseconds)
                        const diffMs = serverTimeNow - slotDate;
                        const diffHours = diffMs / (1000 * 60 * 60);

                        let isDisabled = false;
                        let statusClass = '';
                        let statusText = '';
                        let onClickAttr = '';

                        // LOGIKA KONDISI SLOT
                        if (item.status !== 'tersedia') {
                            // KASUS: SUDAH DIBOOKING
                            isDisabled = true;
                            statusClass = 'status-booked'; // CSS Kuning Text Muted
                            statusText = 'BOOKED';
                        } else if (diffMs > 0) {
                            // KASUS: WAKTU SUDAH LEWAT
                            if (diffHours >= 1) {
                                // Lewat > 1 Jam: Expired (Abu-abu, Gabisa diklik)
                                isDisabled = true;
                                statusClass = 'status-expired';
                                statusText = 'CLOSED';
                            } else {
                                // Lewat < 1 Jam: Late (Kuning, Bisa diklik, Warning)
                                statusClass = 'status-late';
                                statusText = 'LATE';
                                // Tambah Alert JS saat diklik
                                onClickAttr = `onclick="return confirmLateBooking(this)"`;
                            }
                        }

                        // Tampilan Harga Per Slot (Jika ada data harga khusus dari API, kalau null pakai default)
                        const hargaSlot = item.harga_per_slot ? item.harga_per_slot : defaultHarga;
                        const displayHarga = new Intl.NumberFormat('id-ID').format(hargaSlot);

                        const html = `
                    <div class="col-6 col-md-4 col-lg-3 mb-3 d-flex align-items-stretch jadwal-col">
                        <input type="checkbox" name="jadwal_ids[]" value="${item.id}"
                               id="jadwal_${item.id}" class="jadwal-card-input ${statusClass}"
                               data-jam="${item.jam_mulai.substring(0,5)} - ${item.jam_selesai.substring(0,5)}"
                               data-harga="${hargaSlot}"
                               data-durasi="${Number(item.durasi_jam || 1)}"
                               ${isDisabled ? 'disabled' : ''}
                               onchange="calculateTotal()"
                               ${onClickAttr}>

                        <label for="jadwal_${item.id}" class="jadwal-card-label">
                            ${statusText ? `<span class="status-badge ${statusClass === 'status-booked' ? 'bg-warning text-white' : 'bg-secondary text-white'}">${statusText}</span>` : ''}

                            <h6 class="font-weight-bold mb-0" style="font-size: 1.1rem;">${item.jam_mulai.substring(0,5)}</h6>
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Sampai ${item.jam_selesai.substring(0,5)}</small>

                            <div class="mt-2 pt-2 border-top w-100">
                                <span class="badge badge-light border text-dark font-weight-normal">Rp ${displayHarga}</span>
                            </div>
                        </label>
                    </div>
                `;
                        jadwalContainer.insertAdjacentHTML('beforeend', html);
                    });

                    // Tambahkan listener untuk slot expired (untuk alert custom jika user maksa klik via inspect element)
                    document.querySelectorAll('.status-expired').forEach(el => {
                        el.addEventListener('click', function(e) {
                            e.preventDefault();
                            alert(
                                'Maaf, jadwal ini sudah melewati batas waktu pemesanan (> 1 jam).'
                            );
                        });
                    });

                    // Tambahkan listener untuk slot booked
                    document.querySelectorAll('.status-booked').forEach(el => {
                        el.addEventListener('click', function(e) {
                            e.preventDefault();
                            alert('Maaf, jadwal ini sudah dipesan oleh orang lain.');
                        });
                    });

                })
                .catch(err => {
                    console.error(err);
                    jadwalContainer.innerHTML =
                        `<div class="col-12 text-danger text-center">Gagal memuat jadwal.</div>`;
                });

            // 3. Setup Modal & Tombol Bayar
            const btnPreSubmit = document.getElementById('btnPreSubmit');
            const agreeCheck = document.getElementById('agreeCheck');
            const btnFinalSubmit = document.getElementById('btnFinalSubmit');

            btnPreSubmit.addEventListener('click', function() {
                $('#confirmModal').modal('show');
            });

            agreeCheck.addEventListener('change', function() {
                btnFinalSubmit.disabled = !this.checked;
            });



        });

        // Fungsi Peringatan untuk Slot Telat (< 1 jam)
        function confirmLateBooking(checkbox) {
            if (checkbox.checked) {
                // Jika user mencentang
                const confirmMsg =
                    "PERINGATAN: Waktu bermain sudah berjalan.\n\nTidak ada kompensasi waktu bermain (waktu selesai tetap mengikuti jadwal). Apakah Anda yakin ingin melanjutkan?";
                if (!confirm(confirmMsg)) {
                    checkbox.checked = false; // Batal centang
                    return false;
                }
            }
            return true;
        }

        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function calculateTotal() {
            const checkboxes = document.querySelectorAll('.jadwal-card-input:checked');
            const paymentCard = document.getElementById('paymentInstructionCard');
            const uploadCard = document.getElementById('uploadBuktiCard'); // Ambil element Upload

            // 1. Show/Hide Payment & Upload Card (Logic Baru)
            if (checkboxes.length > 0) {
                paymentCard.classList.remove('d-none');
                uploadCard.classList.remove('d-none'); // Munculkan Upload
            } else {
                paymentCard.classList.add('d-none');
                uploadCard.classList.add('d-none'); // Sembunyikan Upload
            }

            let totalBayar = 0;
            let totalJam = 0;
            const slotsHtmlArray = [];

            checkboxes.forEach(cb => {
                const harga = parseFloat(cb.getAttribute('data-harga'));
                const jamLabel = cb.getAttribute('data-jam');
                const durasi = parseFloat(cb.getAttribute('data-durasi'));

                totalBayar += harga;
                totalJam += durasi;

                slotsHtmlArray.push(`<span class="badge badge-light border px-2 py-1">${jamLabel}</span>`);
            });

            // 2. Update UI Lama Sesi
            const durationText = `${totalJam} Jam | ${checkboxes.length} Sesi`;
            document.getElementById('durationDisplay').textContent = checkboxes.length > 0 ? durationText : '-';

            // Update Lainnya
            document.getElementById('totalBayarDisplay').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(
                totalBayar);
            document.getElementById('totalBayarInput').value = totalBayar;
            document.getElementById('slotCountDisplay').textContent = checkboxes.length;

            const slotsContainer = document.getElementById('selectedSlotsContainer');
            const slotsList = document.getElementById('slotsList');

            if (checkboxes.length > 0) {
                slotsList.innerHTML = slotsHtmlArray.join('');
                slotsContainer.classList.remove('d-none');
                document.getElementById('btnPreSubmit').disabled = false;
            } else {
                slotsContainer.classList.add('d-none');
                document.getElementById('btnPreSubmit').disabled = true;
            }
        }
    </script>
@endpush
