@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Booking Saya</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Daftar Booking -->
                <div class="col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
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
                                            <td>{{ $checkout->jadwal ? date('d-m-Y', strtotime($checkout->jadwal->tanggal)) : '-' }}
                                            </td>
                                            <td>{{ $checkout->jadwal && $checkout->jadwal->fasilitas ? $checkout->jadwal->fasilitas->nama_fasilitas : '-' }}
                                            </td>
                                            <td>{{ $checkout->totalDurasi ?? '-' }} jam</td>
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
                                            <td>
                                                <a href="{{ route('user.checkout.detail', $checkout->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($checkout->status == 'fee')
                                                    <a href="{{ route('user.checkout.pelunasan', $checkout->id) }}"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-money-bill"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger ml-2"
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
            const selectedOption = select.options[select.selectedIndex];

            document.getElementById('lokasi').value = selectedOption.dataset.lokasi || '';
            hargaPerJam = parseInt(selectedOption.dataset.harga) || 0;
            document.getElementById('harga_per_jam').value = formatRupiah(hargaPerJam);

            // Reset jadwal
            resetJadwalSelections();

            // Load jadwal jika tanggal sudah dipilih
            const tanggal = document.getElementById('tanggal_booking').value;
            if (tanggal) {
                loadJadwal();
            }
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

        // Create jadwal item element
        function createJadwalItem(jadwal, isBooked) {
            const jadwalItem = document.createElement('div');
            jadwalItem.className = 'form-check mb-2';

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

        // Update tampilan jadwal yang dipilih
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

        // Enhanced search functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Set up search functionality
            const searchInput = document.getElementById('booking-search');

            // Search as you type
            searchInput.addEventListener('input', function() {
                searchBookings();
            });

            // Search when pressing Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchBookings();
                }
            });

            // Make sure the button works
            document.querySelector('.card-tools button').addEventListener('click', function() {
                searchBookings();
            });
        });

        // Improved search bookings function
        function searchBookings() {
            const searchText = document.getElementById('booking-search').value.toLowerCase();
            const tableBody = document.querySelector('table tbody');
            const rows = tableBody.querySelectorAll('tr');
            let hasResults = false;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchText)) {
                    row.style.display = '';
                    hasResults = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle no results
            if (!hasResults && searchText) {
                // If there's no existing "no results" row
                if (!document.getElementById('no-results-row')) {
                    // Remove any previous no-results message
                    const existing = tableBody.querySelector('.no-results-row');
                    if (existing) tableBody.removeChild(existing);

                    // Create new row
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'no-results-row';
                    noResultsRow.className = 'no-results-row';
                    noResultsRow.innerHTML =
                        `<td colspan="7" class="text-center">Tidak ditemukan hasil untuk "${searchText}" <button class="btn btn-sm btn-outline-secondary ml-2" onclick="clearSearch()">Reset</button></td>`;
                    tableBody.appendChild(noResultsRow);
                }
            } else {
                // Remove no results message if it exists
                const noResultsRow = document.getElementById('no-results-row');
                if (noResultsRow) {
                    tableBody.removeChild(noResultsRow);
                }
            }
        }

        // Function to clear search
        function clearSearch() {
            document.getElementById('booking-search').value = '';
            searchBookings();
        }
    </script>
@endsection
