@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Checkout Fasilitas</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Form Booking Fasilitas -->
                <div class="col-md-4">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Form Booking Lapangan</h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('user.checkout.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <!-- Pilihan Fasilitas -->
                                <div class="form-group">
                                    <label for="fasilitas_id">Pilih Lapangan</label>
                                    <select name="fasilitas_id" id="fasilitas_id" class="form-control" required>
                                        <option value="">-- Pilih Lapangan --</option>
                                        @foreach($fasilitas as $f)
                                            <option value="{{ $f->id }}" data-harga="{{ $f->harga_sewa }}" data-lokasi="{{ $f->lokasi }}">
                                                {{ $f->nama_fasilitas }} - {{ $f->tipe }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fasilitas_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Informasi Lokasi -->
                                <div class="form-group">
                                    <label for="lokasi">Lokasi</label>
                                    <input type="text" id="lokasi" class="form-control" readonly>
                                </div>

                                <!-- Loading indicator untuk tanggal tersedia -->
                                <div id="tanggal-loading" class="d-none text-center mb-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Memuat tanggal tersedia...</p>
                                </div>

                                <!-- Kalender Tanggal Tersedia -->
                                <div class="form-group">
                                    <label>Tanggal Tersedia</label>
                                    <div id="tanggal-tersedia" class="border p-3 rounded mb-3" style="min-height: 80px;">
                                        <div class="text-center py-2 text-muted">
                                            Silahkan pilih lapangan terlebih dahulu
                                        </div>
                                    </div>
                                </div>

                                <!-- Tanggal Booking -->
                                <div class="form-group">
                                    <label for="tanggal_booking">Tanggal Booking</label>
                                    <input type="date" name="tanggal_booking" id="tanggal_booking" class="form-control"
                                        min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_booking')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Informasi Hari -->
                                <div class="form-group">
                                    <label for="hari">Hari</label>
                                    <input type="text" id="hari" class="form-control" readonly>
                                </div>

                                <!-- Alert jika jadwal belum dibuat -->
                                <div id="jadwal-alert" class="alert alert-warning d-none">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Jadwal belum dibuat oleh admin untuk tanggal ini.
                                </div>

                                <!-- Loading indicator untuk jadwal -->
                                <div id="jadwal-loading" class="d-none text-center mb-3">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <p class="text-muted mt-2">Memuat jadwal...</p>
                                </div>

                                <!-- Jadwal yang tersedia (Multiple Selection) -->
                                <div class="form-group">
                                    <label>Pilih Jadwal (bisa pilih lebih dari satu slot)</label>
                                    <div id="jadwal-container" class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
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

                                <!-- Informasi Harga per Jam -->
                                <div class="form-group">
                                    <label for="harga_per_jam">Harga per Jam</label>
                                    <input type="text" id="harga_per_jam" class="form-control" readonly>
                                </div>

                                <!-- Total Durasi -->
                                <div class="form-group">
                                    <label for="total_durasi">Total Durasi</label>
                                    <input type="text" id="total_durasi" class="form-control" readonly>
                                    <input type="hidden" name="total_durasi" id="total_durasi_value">
                                </div>

                                <!-- Total Bayar -->
                                <div class="form-group">
                                    <label for="total_bayar_display">Total Bayar</label>
                                    <input type="text" id="total_bayar_display" class="form-control" readonly>
                                    <input type="hidden" name="total_bayar" id="total_bayar_value">
                                    @error('total_bayar')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- DP 50% -->
                                <div class="form-group">
                                    <label for="dp_display">DP (50%)</label>
                                    <input type="text" id="dp_display" class="form-control" readonly>
                                    <input type="hidden" name="dp_value" id="dp_value">
                                    <small class="text-info">*DP 50% langsung tercatat sebagai pembayaran awal</small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" id="submit-btn" class="btn btn-primary btn-block" disabled>Booking Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/.col (left) -->

                <!-- Daftar Booking -->
                <div class="col-md-8">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon fas fa-shopping-cart"></i> List Booking Aktif</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" id="booking-search" class="form-control float-right"
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
                                        <th>Tanggal</th>
                                        <th>Fasilitas</th>
                                        <th>Durasi</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($checkouts as $index => $checkout)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $checkout->jadwal ? date('d-m-Y', strtotime($checkout->jadwal->tanggal)) : '-' }}</td>
                                            <td>{{ $checkout->jadwal && $checkout->jadwal->fasilitas ? $checkout->jadwal->fasilitas->nama_fasilitas : '-' }}</td>
                                            <td>{{ $checkout->totalDurasi ?? '-' }} jam</td>
                                            <td>Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                                            <td>
                                                @if($checkout->status == 'fee')
                                                    <span class="badge badge-warning">DP</span>
                                                @elseif($checkout->status == 'lunas')
                                                    <span class="badge badge-success">Lunas</span>
                                                @elseif($checkout->status == 'batal')
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('user.checkout.detail', $checkout->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($checkout->status == 'fee')
                                                    <a href="{{ route('user.checkout.pelunasan', $checkout->id) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-money-bill"></i> Lunasi
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmCancel({{ $checkout->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada booking aktif</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan booking ini? DP tidak akan dikembalikan.</p>
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

    <!-- JavaScript untuk menangani logika -->
    <script>
        // Global variables
        let selectedJadwals = [];
        let hargaPerJam = 0;
        let availableDates = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal hari ini sebagai default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_booking').value = today;
            updateHari(today);

            // Event listeners
            document.getElementById('fasilitas_id').addEventListener('change', handleFasilitasChange);
            document.getElementById('tanggal_booking').addEventListener('change', handleTanggalChange);
        });

        // Format mata uang Rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Format tanggal untuk tampilan
        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
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
            const selectedOption = select.options[select.selectedIndex];

            document.getElementById('lokasi').value = selectedOption.dataset.lokasi || '';
            hargaPerJam = parseInt(selectedOption.dataset.harga) || 0;
            document.getElementById('harga_per_jam').value = formatRupiah(hargaPerJam);

            // Reset jadwal
            resetJadwalSelections();

            // Load tanggal tersedia terlebih dahulu
            loadAvailableDates();
        }

        // Handler saat tanggal berubah
        function handleTanggalChange() {
            const tanggal = document.getElementById('tanggal_booking').value;
            updateHari(tanggal);
            resetJadwalSelections();

            // Cek apakah tanggal ada di daftar tanggal tersedia
            const isDateAvailable = availableDates.includes(tanggal);

            if (isDateAvailable) {
                // Jika tersedia, muat jadwal
                loadJadwal();
                document.getElementById('jadwal-alert').classList.add('d-none');
            } else {
                // Jika tidak tersedia, tampilkan alert
                document.getElementById('jadwal-container').innerHTML =
                    '<div class="text-center py-3 text-muted">Tidak ada jadwal untuk tanggal ini</div>';
                document.getElementById('jadwal-alert').classList.remove('d-none');
            }
        }

        // Reset semua pilihan jadwal
        function resetJadwalSelections() {
            selectedJadwals = [];
            document.getElementById('jadwal-container').innerHTML = '<div class="text-center py-3 text-muted">Silahkan pilih lapangan dan tanggal terlebih dahulu</div>';
            document.getElementById('selected-jadwal').innerHTML = '<div id="no-selected" class="text-center py-2 text-muted">Belum ada jadwal yang dipilih</div>';
            document.getElementById('jadwal-alert').classList.add('d-none');
            document.getElementById('total_durasi').value = '';
            document.getElementById('total_durasi_value').value = '';
            document.getElementById('total_bayar_display').value = '';
            document.getElementById('total_bayar_value').value = '';
            document.getElementById('dp_display').value = '';
            document.getElementById('dp_value').value = '';
            document.getElementById('submit-btn').disabled = true;
        }

        // Load tanggal tersedia
        function loadAvailableDates() {
            const fasilitasId = document.getElementById('fasilitas_id').value;
            const tanggalContainer = document.getElementById('tanggal-tersedia');

            if (!fasilitasId) {
                tanggalContainer.innerHTML = '<div class="text-center py-2 text-muted">Silahkan pilih lapangan terlebih dahulu</div>';
                return;
            }

            // Show loading
            document.getElementById('tanggal-loading').classList.remove('d-none');
            tanggalContainer.innerHTML = '';

            // AJAX request
            fetch(`/api/available-dates?fasilitas_id=${fasilitasId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading
                    document.getElementById('tanggal-loading').classList.add('d-none');

                    if (data.length === 0) {
                        tanggalContainer.innerHTML =
                            '<div class="alert alert-warning">Belum ada jadwal yang dibuat untuk fasilitas ini.</div>';
                        availableDates = [];
                    } else {
                        // Store available dates
                        availableDates = data;

                        // Build calendar-like display for available dates
                        tanggalContainer.innerHTML = '';

                        // Group dates by month
                        const datesByMonth = {};
                        availableDates.forEach(date => {
                            const month = date.substring(0, 7); // YYYY-MM
                            if (!datesByMonth[month]) {
                                datesByMonth[month] = [];
                            }
                            datesByMonth[month].push(date);
                        });

                        // Create a display for each month
                        for (const month in datesByMonth) {
                            const monthName = new Date(month + '-01').toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

                            const monthElement = document.createElement('div');
                            monthElement.className = 'mb-3';
                            monthElement.innerHTML = `
                                <h6 class="font-weight-bold">${monthName}</h6>
                                <div class="d-flex flex-wrap">
                                    ${datesByMonth[month].map(date => {
                                        const day = new Date(date).getDate();
                                        return `<button type="button" class="btn btn-sm btn-outline-success m-1"
                                                onclick="selectDate('${date}')">
                                                ${day}
                                            </button>`;
                                    }).join('')}
                                </div>
                            `;

                            tanggalContainer.appendChild(monthElement);
                        }

                        // If we have at least one date, select the first one
                        if (availableDates.length > 0) {
                            selectDate(availableDates[0]);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading available dates:', error);
                    document.getElementById('tanggal-loading').classList.add('d-none');
                    tanggalContainer.innerHTML =
                        '<div class="alert alert-danger">Terjadi kesalahan saat memuat tanggal tersedia.</div>';

                    // Use sample dates for development
                    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                        console.log("Menggunakan tanggal dummy untuk development");
                        const today = new Date();

                        // Generate sample dates (today and next 7 days)
                        availableDates = [];
                        for (let i = 0; i < 7; i++) {
                            const date = new Date(today);
                            date.setDate(today.getDate() + i);
                            availableDates.push(date.toISOString().split('T')[0]);
                        }

                        // Display sample dates
                        tanggalContainer.innerHTML = '<div class="d-flex flex-wrap">';
                        availableDates.forEach(date => {
                            const formattedDate = formatDate(date);
                            tanggalContainer.innerHTML += `
                                <button type="button" class="btn btn-sm btn-outline-success m-1"
                                    onclick="selectDate('${date}')">
                                    ${formattedDate}
                                </button>
                            `;
                        });
                        tanggalContainer.innerHTML += '</div>';

                        // Select the first date
                        selectDate(availableDates[0]);
                    }
                });
        }

        // Select a date and load its schedules
        function selectDate(date) {
            // Update input value
            document.getElementById('tanggal_booking').value = date;

            // Update hari
            updateHari(date);

            // Load jadwal for this date
            loadJadwal();

            // Highlight selected date button
            const dateButtons = document.querySelectorAll('#tanggal-tersedia button');
            dateButtons.forEach(button => {
                if (button.onclick.toString().includes(date)) {
                    button.classList.remove('btn-outline-success');
                    button.classList.add('btn-success');
                } else {
                    button.classList.remove('btn-success');
                    button.classList.add('btn-outline-success');
                }
            });
        }

        // Load jadwal dari database
        function loadJadwal() {
            const fasilitasId = document.getElementById('fasilitas_id').value;
            const tanggal = document.getElementById('tanggal_booking').value;
            const jadwalContainer = document.getElementById('jadwal-container');
            const jadwalAlert = document.getElementById('jadwal-alert');

            if (!fasilitasId || !tanggal) {
                jadwalContainer.innerHTML = '<div class="text-center py-3 text-muted">Silahkan pilih lapangan dan tanggal terlebih dahulu</div>';
                jadwalAlert.classList.add('d-none');
                return;
            }

            // Show loading
            document.getElementById('jadwal-loading').classList.remove('d-none');
            jadwalContainer.innerHTML = '';
            jadwalAlert.classList.add('d-none');

            // AJAX request untuk mendapatkan jadwal
            fetch(`/api/jadwal?fasilitas_id=${fasilitasId}&tanggal=${tanggal}`)
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
                        jadwalContainer.innerHTML = '<div class="text-center py-3 text-muted">Tidak ada jadwal untuk tanggal ini</div>';
                        jadwalAlert.classList.remove('d-none'); // Tampilkan alert jadwal belum dibuat
                    } else {
                        jadwalContainer.innerHTML = '';
                        jadwalAlert.classList.add('d-none');

                        // Create jadwal checkboxes
                        data.forEach(jadwal => {
                            const jadwalItem = document.createElement('div');
                            jadwalItem.className = 'form-check mb-2';

                            const isBooked = jadwal.status !== 'tersedia';
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

                            jadwalItem.innerHTML = `
                                <input class="form-check-input" type="checkbox" id="${checkboxId}"
                                    value="${jadwal.id}"
                                    data-jam-mulai="${jamMulaiDisplay}"
                                    data-jam-selesai="${jamSelesaiDisplay}"
                                    data-durasi="${durasi}"
                                    ${isBooked ? 'disabled' : ''}
                                    onchange="toggleJadwal(this)">
                                <label class="form-check-label" for="${checkboxId}">
                                    <span class="mr-2">${jamMulaiDisplay} - ${jamSelesaiDisplay} (${durasi} jam)</span>
                                    ${isBooked ?
                                        '<span class="badge badge-danger">Sudah Dibooking</span>' :
                                        '<span class="badge badge-success">Tersedia</span>'}
                                </label>
                            `;

                            jadwalContainer.appendChild(jadwalItem);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('jadwal-loading').classList.add('d-none');
                    jadwalContainer.innerHTML = '<div class="text-center py-3 text-danger">Terjadi kesalahan saat memuat jadwal</div>';
                    jadwalAlert.classList.add('d-none');

                    // Menggunakan data dummy saat error atau dalam development
                    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                        console.log("Menggunakan data dummy untuk development");
                        useDummyData();
                    }
                });
        }

        // Fungsi fallback - data dummy untuk testing
        function useDummyData() {
            const jadwalContainer = document.getElementById('jadwal-container');
            const jadwalAlert = document.getElementById('jadwal-alert');

            // Array dummy jadwal
            const jadwalDemo = [
                { id: 1, jam_mulai: '07:00', jam_selesai: '08:00', status: 'tersedia', durasi: 1 },
                { id: 2, jam_mulai: '08:00', jam_selesai: '09:00', status: 'tersedia', durasi: 1 },
                { id: 3, jam_mulai: '09:00', jam_selesai: '10:00', status: 'terbooking', durasi: 1 },
                { id: 4, jam_mulai: '10:00', jam_selesai: '11:00', status: 'tersedia', durasi: 1 },
                { id: 5, jam_mulai: '11:00', jam_selesai: '12:00', status: 'tersedia', durasi: 1 },
            ];

            jadwalContainer.innerHTML = '';
            jadwalAlert.classList.add('d-none');

            // Create jadwal checkboxes
            jadwalDemo.forEach(jadwal => {
                const jadwalItem = document.createElement('div');
                jadwalItem.className = 'form-check mb-2';

                const isBooked = jadwal.status !== 'tersedia';
                const checkboxId = `jadwal-${jadwal.id}`;

                jadwalItem.innerHTML = `
                    <input class="form-check-input" type="checkbox" id="${checkboxId}"
                        value="${jadwal.id}"
                        data-jam-mulai="${jadwal.jam_mulai}"
                        data-jam-selesai="${jadwal.jam_selesai}"
                        data-durasi="${jadwal.durasi}"
                        ${isBooked ? 'disabled' : ''}
                        onchange="toggleJadwal(this)">
                    <label class="form-check-label" for="${checkboxId}">
                        <span class="mr-2">${jadwal.jam_mulai} - ${jadwal.jam_selesai} (${jadwal.durasi} jam)</span>
                        ${isBooked ?
                            '<span class="badge badge-danger">Sudah Dibooking</span>' :
                            '<span class="badge badge-success">Tersedia</span>'}
                    </label>
                `;

                jadwalContainer.appendChild(jadwalItem);
            });
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

        // Update tampilan jadwal yang dipilih
        function updateSelectedJadwalDisplay() {
            const selectedJadwalContainer = document.getElementById('selected-jadwal');

            if (selectedJadwals.length === 0) {
                selectedJadwalContainer.innerHTML = '<div id="no-selected" class="text-center py-2 text-muted">Belum ada jadwal yang dipilih</div>';
                return;
            }

            selectedJadwalContainer.innerHTML = '';

            // Sort jadwal berdasarkan jam mulai
            selectedJadwals.sort((a, b) => {
                return a.jamMulai.localeCompare(b.jamMulai);
            });

            // Buat elemen untuk setiap jadwal yang dipilih
            selectedJadwals.forEach(jadwal => {
                const badgeItem = document.createElement('div');
                badgeItem.className = 'badge badge-info p-2 m-1 d-flex align-items-center';
                badgeItem.innerHTML = `
                    <span>${jadwal.jamMulai} - ${jadwal.jamSelesai} (${jadwal.durasi} jam)</span>
                    <button type="button" class="btn btn-xs btn-danger ml-1"
                        onclick="removeJadwal(${jadwal.id})">
                        <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="jadwal_ids[]" value="${jadwal.id}">
                `;
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

        // Fungsi pencarian booking
        function searchBookings() {
            const searchText = document.getElementById('booking-search').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endsection
