@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Booking Fasilitas</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Notification area -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <!-- Form Booking Fasilitas -->
                <div class="col-md-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Form Booking Lapangan</h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('user.checkout.store') }}" method="POST" id="checkoutForm">
                            @csrf
                            <div class="card-body">
                                <!-- Pilihan Fasilitas -->
                                <div class="form-group">
                                    <label for="fasilitas_id">Pilih Tipe Lapangan</label>
                                    <select name="fasilitas_id" id="fasilitas_id" class="form-control" required>
                                        <option value="">-- Pilih Lapangan --</option>
                                        @foreach ($fasilitas as $f)
                                            <option value="{{ $f->id }}" data-harga="{{ $f->harga_sewa }}"
                                                data-lokasi="{{ $f->lokasi }}" data-deskripsi="{{ $f->deskripsi }}">
                                                {{ $f->nama_fasilitas }} - {{ $f->tipe }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fasilitas_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Tanggal dan Hari Booking (side by side) -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_booking">Tanggal Booking</label>
                                            <input type="date" name="tanggal_booking" id="tanggal_booking"
                                                class="form-control" min="{{ date('Y-m-d') }}" required>
                                            @error('tanggal_booking')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hari">Hari</label>
                                            <input type="text" id="hari" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Lokasi -->
                                <div class="form-group">
                                    <label for="lokasi">Lokasi</label>
                                    <input type="text" id="lokasi" class="form-control" readonly>
                                </div>

                                <!-- Jadwal yang tersedia -->
                                <div class="form-group">
                                    <label>Pilih Jadwal</label>
                                    <div id="jadwal-loading" class="text-center d-none mb-2">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <div id="jadwal-container" class="border p-3 rounded"
                                        style="max-height: 200px; overflow-y: auto;">
                                        <div class="text-center py-3 text-muted">
                                            Silahkan pilih lapangan dan tanggal terlebih dahulu
                                        </div>
                                    </div>
                                    @error('jadwal_ids')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Jadwal yang dipilih -->
                                <div class="form-group">
                                    <label>Jadwal Yang Dipilih</label>
                                    <div id="selected-jadwal" class="border p-2 rounded" style="min-height: 50px;">
                                        <div id="no-selected" class="text-center py-2 text-muted">
                                            Belum ada jadwal yang dipilih
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Include -->
                                <div class="form-group">
                                    <label><i class="fas fa-info-circle"></i> Include</label>
                                    <div id="deskripsi-container" class="p-2 border rounded bg-light">
                                        <p id="deskripsi" class="mb-0 text-muted">Pilih lapangan untuk melihat fasilitas
                                            yang tersedia</p>
                                    </div>
                                </div>

                                <!-- Informasi Harga dan Durasi side by side -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga_per_jam">Harga per Jam</label>
                                            <input type="text" id="harga_per_jam" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_durasi">Total Durasi</label>
                                            <input type="text" id="total_durasi" class="form-control" readonly>
                                            <input type="hidden" name="total_durasi" id="total_durasi_value">
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Bayar dan DP side by side -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_bayar_display">Total Bayar</label>
                                            <input type="text" id="total_bayar_display" class="form-control" readonly>
                                            <input type="hidden" name="total_bayar" id="total_bayar_value">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dp_display">DP (50%)</label>
                                            <input type="text" id="dp_display" class="form-control" readonly>
                                            <input type="hidden" name="dp_value" id="dp_value">
                                        </div>
                                    </div>
                                </div>
                                <small class="text-info mb-3">*DP 50% langsung tercatat sebagai pembayaran awal</small>
                            </div>

                            <div class="card-footer">
                                <button type="submit" id="submit-btn" class="btn btn-primary btn-block" disabled>
                                    <i class="fas fa-calendar-check mr-2"></i>Booking Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Booking -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shopping-cart mr-2"></i> List Booking Saya</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" id="booking-search" class="form-control float-right"
                                        placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-default" onclick="searchBookings()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hari</th>
                                        <th>Tgl. Booking</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($checkouts as $index => $checkout)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $checkout->jadwal ? date('l', strtotime($checkout->jadwal->tanggal)) : '-' }}
                                            </td>
                                            <td>{{ $checkout->jadwal ? date('d/m/Y', strtotime($checkout->jadwal->tanggal)) : '-' }}
                                            </td>
                                            <td>Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($checkout->status == 'fee')
                                                    <span class="badge badge-warning">DP</span>
                                                @elseif($checkout->status == 'lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif($checkout->status == 'batal')
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-toolbar justify-content-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('user.checkout.detail', ['id' => $checkout->id, 'source' => 'checkout']) }}"
                                                            class="btn btn-xs btn-info" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if ($checkout->status == 'fee')
                                                            <a href="{{ route('user.checkout.pelunasan', $checkout->id) }}"
                                                                class="btn btn-xs btn-success" title="Lunasi Pembayaran">
                                                                <i class="fas fa-money-bill"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-xs btn-danger"
                                                                title="Batalkan Pesanan"
                                                                onclick="confirmCancel({{ $checkout->id }})">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada booking aktif</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Apakah Anda yakin ingin membatalkan
                        booking ini? DP tidak akan dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="cancel-form" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let selectedJadwals = [];
        let hargaPerJam = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal hari ini sebagai default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_booking').value = today;
            updateHari(today);

            // Event listeners
            document.getElementById('fasilitas_id').addEventListener('change', handleFasilitasChange);
            document.getElementById('tanggal_booking').addEventListener('change', handleTanggalChange);

            // Setup search functionality
            setupSearch();
        });

        // Format mata uang Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Update hari berdasarkan tanggal
        function updateHari(tanggal) {
            const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const date = new Date(tanggal);
            document.getElementById('hari').value = hari[date.getDay()];
        }

        // Handler saat fasilitas berubah
        function handleFasilitasChange() {
            const select = document.getElementById('fasilitas_id');
            if (!select.value) {
                resetFormFields();
                return;
            }

            const selectedOption = select.options[select.selectedIndex];

            // Update lokasi
            document.getElementById('lokasi').value = selectedOption.dataset.lokasi || '';

            // Update harga
            hargaPerJam = parseInt(selectedOption.dataset.harga) || 0;
            document.getElementById('harga_per_jam').value = formatRupiah(hargaPerJam);

            // Update deskripsi/include
            const deskripsiElement = document.getElementById('deskripsi');
            const deskripsiText = selectedOption.dataset.deskripsi || 'Tidak ada informasi tambahan';

            // Format deskripsi dengan bullet points jika ada pemisah koma
            if (deskripsiText.includes(',')) {
                const items = deskripsiText.split(',').map(item => item.trim()).filter(item => item);
                if (items.length > 0) {
                    let htmlContent = '<ul class="mb-0 pl-3">';
                    items.forEach(item => {
                        htmlContent += `<li>${item}</li>`;
                    });
                    htmlContent += '</ul>';
                    deskripsiElement.innerHTML = htmlContent;
                } else {
                    deskripsiElement.textContent = deskripsiText;
                }
            } else {
                deskripsiElement.textContent = deskripsiText;
            }

            // Hapus kelas text-muted jika ada deskripsi
            if (deskripsiText && deskripsiText !== 'Tidak ada informasi tambahan') {
                deskripsiElement.classList.remove('text-muted');
            } else {
                deskripsiElement.classList.add('text-muted');
            }

            // Reset jadwal
            resetJadwalSelections();

            // Load jadwal jika tanggal sudah dipilih
            const tanggal = document.getElementById('tanggal_booking').value;
            if (tanggal) {
                loadJadwal();
            }
        }

        // Reset all form fields
        function resetFormFields() {
            document.getElementById('lokasi').value = '';
            document.getElementById('harga_per_jam').value = '';
            document.getElementById('deskripsi').innerHTML = 'Pilih lapangan untuk melihat fasilitas yang tersedia';
            document.getElementById('deskripsi').classList.add('text-muted');
            resetJadwalSelections();
        }

        // Handler saat tanggal berubah
        function handleTanggalChange() {
            const tanggal = document.getElementById('tanggal_booking').value;
            updateHari(tanggal);
            resetJadwalSelections();

            // Load jadwal
            if (document.getElementById('fasilitas_id').value) {
                loadJadwal();
            }
        }

        // Reset semua pilihan jadwal
        function resetJadwalSelections() {
            selectedJadwals = [];
            document.getElementById('jadwal-container').innerHTML =
                '<div class="text-center py-3 text-muted">Silahkan pilih lapangan dan tanggal terlebih dahulu</div>';
            document.getElementById('selected-jadwal').innerHTML =
                '<div id="no-selected" class="text-center py-2 text-muted">Belum ada jadwal yang dipilih</div>';
            document.getElementById('total_durasi').value = '';
            document.getElementById('total_durasi_value').value = '';
            document.getElementById('total_bayar_display').value = '';
            document.getElementById('total_bayar_value').value = '';
            document.getElementById('dp_display').value = '';
            document.getElementById('dp_value').value = '';
            document.getElementById('submit-btn').disabled = true;
        }

        // Load jadwal dari database
        function loadJadwal() {
            const fasilitasId = document.getElementById('fasilitas_id').value;
            const tanggal = document.getElementById('tanggal_booking').value;
            const jadwalContainer = document.getElementById('jadwal-container');

            if (!fasilitasId || !tanggal) {
                jadwalContainer.innerHTML =
                    '<div class="text-center py-3 text-muted">Silahkan pilih lapangan dan tanggal terlebih dahulu</div>';
                return;
            }

            // Show loading
            document.getElementById('jadwal-loading').classList.remove('d-none');
            jadwalContainer.innerHTML = '';

            // Fetch jadwal
            fetch(`/api/check-jadwal/${fasilitasId}/${tanggal}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading
                    document.getElementById('jadwal-loading').classList.add('d-none');

                    if (data.length === 0) {
                        jadwalContainer.innerHTML =
                            '<div class="alert alert-info">Tidak ada jadwal untuk tanggal ini</div>';
                    } else {
                        jadwalContainer.innerHTML = '';

                        // Group jadwal by status
                        const tersedia = data.filter(j => j.status === 'tersedia');
                        const terbooking = data.filter(j => j.status !== 'tersedia');

                        // Available slots
                        if (tersedia.length > 0) {
                            const availableTitle = document.createElement('h6');
                            availableTitle.className = 'font-weight-bold mb-2';
                            availableTitle.textContent = 'Jadwal Tersedia:';
                            jadwalContainer.appendChild(availableTitle);

                            tersedia.forEach(jadwal => {
                                const jadwalItem = createJadwalItem(jadwal, false);
                                jadwalContainer.appendChild(jadwalItem);
                            });
                        }

                        // Booked slots
                        if (terbooking.length > 0) {
                            const bookedTitle = document.createElement('h6');
                            bookedTitle.className = 'font-weight-bold mb-2 mt-3';
                            bookedTitle.textContent = 'Jadwal Sudah Dipesan:';
                            jadwalContainer.appendChild(bookedTitle);

                            terbooking.forEach(jadwal => {
                                const jadwalItem = createJadwalItem(jadwal, true);
                                jadwalContainer.appendChild(jadwalItem);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading jadwal:', error);
                    document.getElementById('jadwal-loading').classList.add('d-none');
                    jadwalContainer.innerHTML =
                        '<div class="alert alert-danger">Terjadi kesalahan saat memuat jadwal</div>';
                });
        }

        // Create jadwal item element with improved button position
        function createJadwalItem(jadwal, isBooked) {
            const jadwalItem = document.createElement('div');
            jadwalItem.className = 'form-check mb-2 d-flex align-items-center';

            const checkboxId = `jadwal-${jadwal.id}`;

            // Format jam untuk tampilan (tanpa detik)
            const jamMulaiDisplay = jadwal.jam_mulai.substring(0, 5);
            const jamSelesaiDisplay = jadwal.jam_selesai.substring(0, 5);

            // Hitung durasi (dalam jam penuh)
            const mulaiParts = jadwal.jam_mulai.split(':');
            const selesaiParts = jadwal.jam_selesai.split(':');

            const mulaiJam = parseInt(mulaiParts[0]);
            const selesaiJam = parseInt(selesaiParts[0]);

            // Durasi selalu dalam jam penuh
            const durasi = selesaiJam - mulaiJam;

            // Create checkbox and label first
            const checkbox = document.createElement('input');
            checkbox.className = 'form-check-input';
            checkbox.type = 'checkbox';
            checkbox.id = checkboxId;
            checkbox.value = jadwal.id;
            checkbox.dataset.jamMulai = jamMulaiDisplay;
            checkbox.dataset.jamSelesai = jamSelesaiDisplay;
            checkbox.dataset.durasi = durasi;
            checkbox.disabled = isBooked;
            checkbox.onchange = function() {
                toggleJadwal(this);
            };

            const label = document.createElement('label');
            label.className = 'form-check-label w-100 d-flex justify-content-between align-items-center';
            label.htmlFor = checkboxId;

            const timeSpan = document.createElement('span');
            timeSpan.className = 'ml-2';
            timeSpan.textContent = `${jamMulaiDisplay} - ${jamSelesaiDisplay} (${durasi} jam)`;

            const statusBadge = document.createElement('span');
            statusBadge.className = isBooked ? 'badge badge-danger ml-2' : 'badge badge-success ml-2';
            statusBadge.textContent = isBooked ? 'Sudah Dibooking' : 'Tersedia';

            label.appendChild(timeSpan);
            label.appendChild(statusBadge);

            jadwalItem.appendChild(checkbox);
            jadwalItem.appendChild(label);

            return jadwalItem;
        }

        // Toggle jadwal ketika checkbox di-klik
        function toggleJadwal(checkbox) {
            const jadwalId = parseInt(checkbox.value);
            const jamMulai = checkbox.dataset.jamMulai;
            const jamSelesai = checkbox.dataset.jamSelesai;
            const durasi = parseInt(checkbox.dataset.durasi);

            if (checkbox.checked) {
                // Tambahkan ke array jadwal yang dipilih
                selectedJadwals.push({
                    id: jadwalId,
                    jamMulai: jamMulai,
                    jamSelesai: jamSelesai,
                    durasi: durasi
                });
            } else {
                // Hapus dari array jadwal yang dipilih
                selectedJadwals = selectedJadwals.filter(j => j.id !== jadwalId);
            }

            updateSelectedJadwalDisplay();
            updateTotalHarga();
        }

        // Update tampilan jadwal yang dipilih with improved layout
        function updateSelectedJadwalDisplay() {
            const selectedJadwalContainer = document.getElementById('selected-jadwal');

            if (selectedJadwals.length === 0) {
                selectedJadwalContainer.innerHTML =
                    '<div id="no-selected" class="text-center py-2 text-muted">Belum ada jadwal yang dipilih</div>';
                return;
            }

            selectedJadwalContainer.innerHTML = '';

            // Sort jadwal berdasarkan jam mulai
            selectedJadwals.sort((a, b) => a.jamMulai.localeCompare(b.jamMulai));

            // Buat elemen untuk setiap jadwal yang dipilih
            selectedJadwals.forEach(jadwal => {
                const badgeItem = document.createElement('div');
                badgeItem.className = 'badge badge-info p-2 m-1 d-flex align-items-center';

                const timeSpan = document.createElement('span');
                timeSpan.textContent = `${jadwal.jamMulai} - ${jadwal.jamSelesai} (${jadwal.durasi} jam)`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-xs btn-danger ml-2';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = function() {
                    removeJadwal(jadwal.id);
                };

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'jadwal_ids[]';
                hiddenInput.value = jadwal.id;

                badgeItem.appendChild(timeSpan);
                badgeItem.appendChild(removeBtn);
                badgeItem.appendChild(hiddenInput);

                selectedJadwalContainer.appendChild(badgeItem);
            });
        }

        // Hapus jadwal dari pilihan
        function removeJadwal(jadwalId) {
            // Hapus dari array
            selectedJadwals = selectedJadwals.filter(j => j.id !== jadwalId);

            // Uncheck checkbox
            const checkbox = document.getElementById(`jadwal-${jadwalId}`);
            if (checkbox) {
                checkbox.checked = false;
            }

            // Update tampilan dan total
            updateSelectedJadwalDisplay();
            updateTotalHarga();
        }

        // Update total harga berdasarkan jadwal yang dipilih
        function updateTotalHarga() {
            // Hitung total durasi
            const totalDurasi = selectedJadwals.reduce((total, jadwal) => total + jadwal.durasi, 0);

            // Hitung total harga dan DP
            const totalBayar = hargaPerJam * totalDurasi;
            const dp = Math.round(totalBayar * 0.5); // DP 50%, dibulatkan

            // Update fields
            document.getElementById('total_durasi').value = `${totalDurasi} jam`;
            document.getElementById('total_durasi_value').value = totalDurasi;
            document.getElementById('total_bayar_display').value = formatRupiah(totalBayar);
            document.getElementById('total_bayar_value').value = totalBayar;
            document.getElementById('dp_display').value = formatRupiah(dp);
            document.getElementById('dp_value').value = dp;

            // Enable/disable submit button
            document.getElementById('submit-btn').disabled = totalDurasi === 0;
        }

        // Fungsi untuk konfirmasi pembatalan
        function confirmCancel(id) {
            document.getElementById('cancel-form').action = `/checkout/cancel/${id}`;
            $('#cancelModal').modal('show');
        }

        // Setup search functionality
        function setupSearch() {
            const searchInput = document.getElementById('booking-search');
            if (!searchInput) return;

            // Search as you type
            searchInput.addEventListener('input', function() {
                searchBookings();
            });

            // Search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchBookings();
                }
            });
        }

        // Improved search bookings function
        function searchBookings() {
            const searchText = document.getElementById('booking-search').value.toLowerCase();
            const tableBody = document.querySelector('table tbody');
            const rows = tableBody.querySelectorAll('tr');
            let hasResults = false;

            rows.forEach(row => {
                if (row.id === 'no-results-row') return;

                const text = row.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    row.style.display = '';
                    hasResults = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle no results
            const noResultsRow = document.getElementById('no-results-row');
            if (!hasResults && searchText) {
                if (!noResultsRow) {
                    const newRow = document.createElement('tr');
                    newRow.id = 'no-results-row';
                    newRow.innerHTML = `<td colspan="6" class="text-center">
                        Tidak ditemukan hasil untuk "<b>${searchText}</b>"
                        <button class="btn btn-sm btn-outline-secondary ml-2" onclick="clearSearch()">Reset</button>
                    </td>`;
                    tableBody.appendChild(newRow);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        }

        // Function to clear search
        function clearSearch() {
            document.getElementById('booking-search').value = '';
            searchBookings();
        }
    </script>
@endsection
