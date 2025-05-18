@extends('PetugasFasilitas.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Jadwal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tambah Jadwal Fasilitas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Form Generator Jadwal -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Generator Jadwal Otomatis</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="jadwalGeneratorForm">
                                <!-- Pilih Fasilitas -->
                                <div class="form-group">
                                    <label for="fasilitas_id">Pilih Fasilitas</label>
                                    <select id="fasilitas_id" name="fasilitas_id" class="form-control select2" required>
                                        <option value="">-- Pilih Fasilitas --</option>
                                        @foreach ($fasilitas as $f)
                                            <option value="{{ $f->id }}">{{ $f->nama_fasilitas }} -
                                                {{ $f->lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Rentang Tanggal -->
                                <div class="form-group">
                                    <label>Rentang Tanggal</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Dari</span>
                                                </div>
                                                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                                                    class="form-control" min="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Sampai</span>
                                                </div>
                                                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                                                    class="form-control" min="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hari dalam Seminggu -->
                                <div class="form-group">
                                    <label>Pilih Hari</label>
                                    <div class="d-flex flex-wrap">
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_1"
                                                name="hari[]" value="1" checked>
                                            <label for="hari_1" class="custom-control-label">Senin</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_2"
                                                name="hari[]" value="2" checked>
                                            <label for="hari_2" class="custom-control-label">Selasa</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_3"
                                                name="hari[]" value="3" checked>
                                            <label for="hari_3" class="custom-control-label">Rabu</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_4"
                                                name="hari[]" value="4" checked>
                                            <label for="hari_4" class="custom-control-label">Kamis</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_5"
                                                name="hari[]" value="5" checked>
                                            <label for="hari_5" class="custom-control-label">Jumat</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_6"
                                                name="hari[]" value="6" checked>
                                            <label for="hari_6" class="custom-control-label">Sabtu</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_0"
                                                name="hari[]" value="0" checked>
                                            <label for="hari_0" class="custom-control-label">Minggu</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Jam Operasional -->
                                <div class="form-group">
                                    <label>Jam Operasional</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Mulai</span>
                                                </div>
                                                <input type="time" id="jam_buka" name="jam_buka"
                                                    class="form-control" value="08:00" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Selesai</span>
                                                </div>
                                                <input type="time" id="jam_tutup" name="jam_tutup"
                                                    class="form-control" value="22:00" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Durasi per Slot -->
                                <div class="form-group">
                                    <label for="durasi_slot">Durasi per Slot</label>
                                    <div class="input-group">
                                        <input type="number" id="durasi_slot" name="durasi_slot" class="form-control"
                                            min="1" max="6" value="1" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">jam</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button Generate -->
                                <div class="form-group">
                                    <button type="button" id="previewButton" class="btn btn-info">
                                        <i class="fas fa-eye"></i> Preview Jadwal
                                    </button>
                                    <button type="button" id="generateButton" class="btn btn-success float-right">
                                        <i class="fas fa-calendar-plus"></i> Generate Jadwal
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <!-- Preview Jadwal -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Preview Jadwal</h3>
                            <div class="card-tools">
                                <span id="totalJadwalPreview" class="badge badge-warning">0 jadwal akan dibuat</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive" id="previewContainer">
                                <p class="text-muted text-center py-3">Klik 'Preview Jadwal' untuk melihat jadwal yang akan
                                    dibuat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Jadwal Tersedia -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Jadwal Tersedia</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 250px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Cari">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
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
                                        <th>ID</th>
                                        <th>Fasilitas</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jadwals as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->id }}</td>
                                            <td>{{ $jadwal->fasilitas->nama_fasilitas }} - {{ $jadwal->fasilitas->tipe }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                            <td>{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                                            <td>
                                                @if ($jadwal->status == 'tersedia')
                                                    <span class="badge badge-success">Tersedia</span>
                                                @elseif($jadwal->status == 'terbooking')
                                                    <span class="badge badge-warning">Terbooking</span>
                                                @elseif($jadwal->status == 'selesai')
                                                    <span class="badge badge-info">Selesai</span>
                                                @else
                                                    <span class="badge badge-danger">Batal</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-xs btn-primary">Edit</button>
                                                <button class="btn btn-xs btn-danger">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 justify-content-center">
                                {{ $jadwals->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <!-- Modal Konfirmasi Generate -->
    <div class="modal fade" id="confirmGenerateModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Konfirmasi Generate Jadwal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda akan membuat <span id="totalJadwalConfirm" class="font-weight-bold">0</span> jadwal baru.</p>
                    <p>Proses ini tidak dapat dibatalkan dan akan menambahkan jadwal ke database.</p>
                    <p>Lanjutkan?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="button" id="confirmGenerateButton" class="btn btn-success">
                        <i class="fas fa-calendar-plus"></i> Buat Jadwal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript untuk form generator -->
    <script>
        // Perbaiki koneksi antara button generate dan confirmation modal
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan event listener untuk generate button
            const generateButton = document.getElementById('generateButton');
            if (generateButton) {
                generateButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Validasi form terlebih dahulu
                    const isValid = validateGeneratorForm();
                    if (!isValid) return;

                    // Hitung total jadwal
                    const totalJadwal = calculateTotalJadwal();

                    // Update text pada modal
                    document.getElementById('totalJadwalConfirm').textContent = totalJadwal;

                    // Tampilkan modal konfirmasi dengan jQuery
                    $('#confirmGenerateModal').modal('show');
                });
            }

            // Event listener untuk tombol konfirmasi di modal
            const confirmButton = document.getElementById('confirmGenerateButton');
            if (confirmButton) {
                confirmButton.addEventListener('click', function() {
                    // Tambahkan class loading dan disable button
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                    // Submit form
                    submitGenerateJadwal();
                });
            }
        });

        // Validasi form sebelum generate
        function validateGeneratorForm() {
            const fasilitas = document.getElementById('fasilitas_id');
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalSelesai = document.getElementById('tanggal_selesai').value;
            const jamBuka = document.getElementById('jam_buka').value;
            const jamTutup = document.getElementById('jam_tutup').value;

            if (!fasilitas.value) {
                Swal.fire('Error', 'Pilih fasilitas terlebih dahulu', 'error');
                return false;
            }

            if (!tanggalMulai || !tanggalSelesai) {
                Swal.fire('Error', 'Pilih rentang tanggal terlebih dahulu', 'error');
                return false;
            }

            if (!jamBuka || !jamTutup) {
                Swal.fire('Error', 'Isi jam operasional terlebih dahulu', 'error');
                return false;
            }

            // Periksa apakah ada hari yang dipilih
            const selectedDays = document.querySelectorAll('input[name="hari[]"]:checked');
            if (selectedDays.length === 0) {
                Swal.fire('Error', 'Pilih minimal satu hari', 'error');
                return false;
            }

            return true;
        }

        // Perbaiki fungsi untuk submit data ke server
        function submitGenerateJadwal() {
            // Kumpulkan data form
            const formData = new FormData();

            // Tambahkan CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            // Tambahkan data form
            formData.append('fasilitas_id', document.getElementById('fasilitas_id').value);
            formData.append('tanggal_mulai', document.getElementById('tanggal_mulai').value);
            formData.append('tanggal_selesai', document.getElementById('tanggal_selesai').value);
            formData.append('jam_buka', document.getElementById('jam_buka').value);
            formData.append('jam_tutup', document.getElementById('jam_tutup').value);
            formData.append('durasi_slot', document.getElementById('durasi_slot').value);

            // Kumpulkan checkbox hari yang dipilih
            const selectedDays = [];
            document.querySelectorAll('input[name="hari[]"]:checked').forEach(function(checkbox) {
                selectedDays.push(checkbox.value);
            });

            // Tambahkan data hari ke FormData
            selectedDays.forEach(day => {
                formData.append('hari[]', day);
            });

            // Kirim data ke server dengan fetch API
            fetch('{{ route('admin.jadwal.generate') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Sembunyikan modal
                    $('#confirmGenerateModal').modal('hide');

                    if (data.success) {
                        // Tampilkan pesan sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: `${data.count} jadwal berhasil dibuat`,
                            showConfirmButton: true,
                            timer: 3000
                        }).then(() => {
                            // Refresh halaman
                            window.location.reload();
                        });
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat membuat jadwal',
                            showConfirmButton: true
                        });

                        // Reset button konfirmasi
                        document.getElementById('confirmGenerateButton').disabled = false;
                        document.getElementById('confirmGenerateButton').innerHTML =
                            '<i class="fas fa-calendar-plus"></i> Buat Jadwal';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);

                    // Tampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan pada server',
                        showConfirmButton: true
                    });

                    // Reset button konfirmasi
                    document.getElementById('confirmGenerateButton').disabled = false;
                    document.getElementById('confirmGenerateButton').innerHTML =
                        '<i class="fas fa-calendar-plus"></i> Buat Jadwal';

                    // Sembunyikan modal
                    $('#confirmGenerateModal').modal('hide');
                });
        }

        // Calculate total jadwal based on form inputs
        function calculateTotalJadwal() {
            const tanggalMulai = new Date(document.getElementById('tanggal_mulai').value);
            const tanggalSelesai = new Date(document.getElementById('tanggal_selesai').value);
            const jamBuka = document.getElementById('jam_buka').value;
            const jamTutup = document.getElementById('jam_tutup').value;
            const durasiSlot = parseInt(document.getElementById('durasi_slot').value);

            // Get selected days
            const selectedDays = [];
            document.querySelectorAll('input[name="hari[]"]:checked').forEach(function(checkbox) {
                selectedDays.push(parseInt(checkbox.value));
            });

            // Count days between dates
            let totalJadwal = 0;
            let currentDate = new Date(tanggalMulai);

            while (currentDate <= tanggalSelesai) {
                // Check if day is selected
                if (selectedDays.includes(currentDate.getDay())) {
                    // Calculate slots in a day
                    const [startHour, startMin] = jamBuka.split(':').map(Number);
                    const [endHour, endMin] = jamTutup.split(':').map(Number);

                    let totalMinutes = (endHour * 60 + endMin) - (startHour * 60 + startMin);
                    let totalSlots = Math.floor(totalMinutes / (durasiSlot * 60));

                    totalJadwal += totalSlots;
                }

                // Move to next day
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return totalJadwal;
        }

        // Perbaikan fungsi generatePreview()
        function generatePreview() {
            const fasilitas = document.getElementById('fasilitas_id');
            const tanggalMulai = document.getElementById('tanggal_mulai').value;
            const tanggalSelesai = document.getElementById('tanggal_selesai').value;
            const jamBuka = document.getElementById('jam_buka').value;
            const jamTutup = document.getElementById('jam_tutup').value;
            const durasiSlot = parseInt(document.getElementById('durasi_slot').value);

            // Validasi inputs
            if (!fasilitas.value || !tanggalMulai || !tanggalSelesai || !jamBuka || !jamTutup) {
                Swal.fire('Error', 'Mohon lengkapi semua field', 'error');
                return;
            }

            // Get selected days
            const selectedDays = [];
            document.querySelectorAll('input[name="hari[]"]:checked').forEach(function(checkbox) {
                selectedDays.push(parseInt(checkbox.value));
            });

            if (selectedDays.length === 0) {
                Swal.fire('Error', 'Pilih minimal satu hari', 'error');
                return;
            }

            // Tampilkan loading
            document.getElementById('previewContainer').innerHTML =
                '<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Menghasilkan preview...</p></div>';

            // Gunakan setTimeout untuk proses di background (mencegah browser freeze jika banyak data)
            setTimeout(() => {
                try {
                    // Generate preview data
                    const jadwalPreview = generateJadwalData(
                        tanggalMulai,
                        tanggalSelesai,
                        jamBuka,
                        jamTutup,
                        durasiSlot,
                        selectedDays,
                        fasilitas.options[fasilitas.selectedIndex].text
                    );

                    // Update preview container
                    const previewContainer = document.getElementById('previewContainer');
                    const totalJadwalPreview = document.getElementById('totalJadwalPreview');

                    if (jadwalPreview.length > 0) {
                        totalJadwalPreview.textContent = `${jadwalPreview.length} jadwal akan dibuat`;

                        // Batasi preview untuk performa
                        const maxPreviewItems = 100;
                        const shownItems = jadwalPreview.slice(0, maxPreviewItems);

                        let html = `
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fasilitas</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                        shownItems.forEach(function(jadwal) {
                            html += `
                    <tr>
                        <td>${jadwal.fasilitas}</td>
                        <td>${jadwal.tanggal}</td>
                        <td>${jadwal.hari}</td>
                        <td>${jadwal.jam_mulai} - ${jadwal.jam_selesai}</td>
                    </tr>
                    `;
                        });

                        // Jika jumlah jadwal melebihi yang ditampilkan, tambahkan catatan
                        if (jadwalPreview.length > maxPreviewItems) {
                            html += `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <em>...dan ${jadwalPreview.length - maxPreviewItems} jadwal lainnya</em>
                        </td>
                    </tr>
                    `;
                        }

                        html += `
                    </tbody>
                </table>
                `;

                        previewContainer.innerHTML = html;
                    } else {
                        previewContainer.innerHTML =
                            '<p class="text-center text-warning py-3">Tidak ada jadwal yang dapat dibuat dengan kriteria tersebut</p>';
                        totalJadwalPreview.textContent = '0 jadwal akan dibuat';
                    }
                } catch (error) {
                    console.error('Error generating preview:', error);
                    Swal.fire('Error', 'Terjadi kesalahan saat menghasilkan preview', 'error');
                }
            }, 100);
        }

        // Tambahkan fungsi untuk memvalidasi checkbox hari berdasarkan rentang tanggal
        function validateDayCheckboxes() {
            const tanggalMulai = new Date(document.getElementById('tanggal_mulai').value);
            const tanggalSelesai = new Date(document.getElementById('tanggal_selesai').value);

            if (!tanggalMulai || !tanggalSelesai || isNaN(tanggalMulai) || isNaN(tanggalSelesai)) {
                // Jika tanggal tidak valid, aktifkan semua checkbox
                document.querySelectorAll('input[name="hari[]"]').forEach(function(checkbox) {
                    checkbox.disabled = false;
                });
                return;
            }

            // Temukan hari apa saja yang ada dalam rentang tanggal
            const daysInRange = new Set();
            let currentDate = new Date(tanggalMulai);

            while (currentDate <= tanggalSelesai) {
                daysInRange.add(currentDate.getDay()); // 0 = Minggu, 1 = Senin, dsb.
                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Update status disabled checkbox berdasarkan hari yang tersedia
            document.querySelectorAll('input[name="hari[]"]').forEach(function(checkbox) {
                const day = parseInt(checkbox.value);
                if (daysInRange.has(day)) {
                    checkbox.disabled = false;
                    // Tambahkan class untuk styling (opsional)
                    checkbox.parentElement.classList.remove('text-muted');
                } else {
                    checkbox.disabled = true;
                    checkbox.checked = false; // Uncheck jika dinonaktifkan
                    // Tambahkan class untuk styling (opsional)
                    checkbox.parentElement.classList.add('text-muted');
                }
            });
        }

        // Hubungkan ke event change pada input tanggal
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('tanggal_mulai').addEventListener('change', validateDayCheckboxes);
            document.getElementById('tanggal_selesai').addEventListener('change', validateDayCheckboxes);
        });

        // Perbaikan fungsi generateJadwalData
        function generateJadwalData(tanggalMulai, tanggalSelesai, jamBuka, jamTutup, durasiSlot, selectedDays,
            fasilitasText) {
            // Pastikan semua parameter dikonversi ke format yang benar
            const startDate = new Date(tanggalMulai);
            const endDate = new Date(tanggalSelesai);
            const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            console.log("Generating preview with parameters:", {
                tanggalMulai,
                tanggalSelesai,
                jamBuka,
                jamTutup,
                durasiSlot,
                selectedDays
            }); // Debug log

            const jadwalData = [];

            // Validasi data sebelum memproses
            if (!startDate || !endDate || isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
                console.error("Invalid date format");
                return [];
            }

            let currentDate = new Date(startDate);

            // Untuk setiap hari dalam rentang
            while (currentDate <= endDate) {
                // Cek apakah hari ini dipilih
                if (selectedDays.includes(currentDate.getDay())) {
                    const dateFormatted = currentDate.toISOString().split('T')[0];
                    const dayName = daysOfWeek[currentDate.getDay()];

                    // Parse jam buka & tutup
                    const [startHour, startMin] = jamBuka.split(':').map(Number);
                    const [endHour, endMin] = jamTutup.split(':').map(Number);

                    let currentHour = startHour;
                    let currentMin = startMin;

                    // Untuk setiap slot dalam hari
                    while ((currentHour < endHour) || (currentHour === endHour && currentMin < endMin)) {
                        const slotStartTime =
                            `${currentHour.toString().padStart(2, '0')}:${currentMin.toString().padStart(2, '0')}`;

                        // Hitung waktu akhir slot
                        let endSlotHour = currentHour + durasiSlot;
                        let endSlotMin = currentMin;

                        // Cek apakah melebihi jam tutup
                        if (endSlotHour > endHour || (endSlotHour === endHour && endSlotMin > endMin)) {
                            break;
                        }

                        const slotEndTime =
                            `${endSlotHour.toString().padStart(2, '0')}:${endSlotMin.toString().padStart(2, '0')}`;

                        // Tambahkan ke data jadwal
                        jadwalData.push({
                            fasilitas: fasilitasText,
                            tanggal: dateFormatted,
                            hari: dayName,
                            jam_mulai: slotStartTime,
                            jam_selesai: slotEndTime
                        });

                        // Pindah ke slot berikutnya
                        currentHour = endSlotHour;
                        currentMin = endSlotMin;
                    }
                }

                // Pindah ke hari berikutnya
                currentDate.setDate(currentDate.getDate() + 1);
            }

            console.log(`Generated ${jadwalData.length} schedule items`); // Debug log
            return jadwalData;
        }

        // Perbaikan untuk preview jadwal
        function generatePreview() {
            try {
                const fasilitas = document.getElementById('fasilitas_id');
                const tanggalMulai = document.getElementById('tanggal_mulai').value;
                const tanggalSelesai = document.getElementById('tanggal_selesai').value;
                const jamBuka = document.getElementById('jam_buka').value;
                const jamTutup = document.getElementById('jam_tutup').value;
                const durasiSlot = parseInt(document.getElementById('durasi_slot').value);

                // Debug log
                console.log("Form values:", {
                    fasilitas: fasilitas.value,
                    tanggalMulai,
                    tanggalSelesai,
                    jamBuka,
                    jamTutup,
                    durasiSlot
                });

                // Validasi inputs
                if (!fasilitas.value || !tanggalMulai || !tanggalSelesai || !jamBuka || !jamTutup) {
                    alert('Mohon lengkapi semua field');
                    return;
                }

                // Get selected days
                const selectedDays = [];
                document.querySelectorAll('input[name="hari[]"]:checked').forEach(function(checkbox) {
                    selectedDays.push(parseInt(checkbox.value));
                });

                if (selectedDays.length === 0) {
                    alert('Pilih minimal satu hari');
                    return;
                }

                // Tampilkan loading
                const previewContainer = document.getElementById('previewContainer');
                previewContainer.innerHTML =
                    '<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-3">Memuat preview...</p></div>';

                // Beri waktu untuk loading indicator
                setTimeout(() => {
                    // Generate preview data
                    const jadwalPreview = generateJadwalData(
                        tanggalMulai,
                        tanggalSelesai,
                        jamBuka,
                        jamTutup,
                        durasiSlot,
                        selectedDays,
                        fasilitas.options[fasilitas.selectedIndex].text
                    );

                    // Update preview container
                    const totalJadwalPreview = document.getElementById('totalJadwalPreview');

                    if (jadwalPreview.length > 0) {
                        totalJadwalPreview.textContent = `${jadwalPreview.length} jadwal akan dibuat`;

                        // Batasi preview untuk kinerja yang lebih baik
                        const maxPreviewItems = Math.min(jadwalPreview.length, 50);
                        const shownItems = jadwalPreview.slice(0, maxPreviewItems);

                        let html = `
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Fasilitas</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                        shownItems.forEach(function(jadwal) {
                            html += `
                    <tr>
                        <td>${jadwal.fasilitas}</td>
                        <td>${jadwal.tanggal}</td>
                        <td>${jadwal.hari}</td>
                        <td>${jadwal.jam_mulai} - ${jadwal.jam_selesai}</td>
                    </tr>
                    `;
                        });

                        // Tampilkan jumlah yang tidak muat di preview
                        if (jadwalPreview.length > maxPreviewItems) {
                            html += `
                    <tr>
                        <td colspan="4" class="text-center font-italic">
                            ...dan ${jadwalPreview.length - maxPreviewItems} jadwal lainnya
                        </td>
                    </tr>
                    `;
                        }

                        html += `
                        </tbody>
                    </table>
                </div>
                `;

                        previewContainer.innerHTML = html;
                    } else {
                        previewContainer.innerHTML =
                            '<div class="alert alert-warning text-center">Tidak ada jadwal yang dapat dibuat dengan kriteria tersebut</div>';
                        totalJadwalPreview.textContent = '0 jadwal akan dibuat';
                    }
                }, 500);
            } catch (error) {
                console.error("Error in preview generation:", error);
                alert('Terjadi kesalahan saat membuat preview jadwal: ' + error.message);
            }
        }

        // Submit jadwal generation to server
        function submitGenerateJadwal() {
            const formData = new FormData(document.getElementById('jadwalGeneratorForm'));

            // Collect checkbox values manually
            const selectedDays = [];
            document.querySelectorAll('input[name="hari[]"]:checked').forEach(function(checkbox) {
                selectedDays.push(checkbox.value);
            });
            formData.delete('hari[]'); // Remove the original entries
            selectedDays.forEach(value => formData.append('hari[]', value));

            // Send to server
            fetch('{{ route('admin.jadwal.generate') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    $('#confirmGenerateModal').modal('hide');

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: `${data.count} jadwal berhasil dibuat`,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Terjadi kesalahan saat membuat jadwal',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan pada server',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
        }
    </script>
@endsection
