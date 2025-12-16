@extends('AdminFasilitas.component')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 class="m-0">Daftar Jadwal</h1> --}}
                    <a href="#tabel-jadwal" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-arrow-down"></i> Lihat Tabel Jadwal
                    </a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Daftar Jadwal</li>
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
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Generator Jadwal Otomatis</h3>
                            <div class="card-tools">
                                {{-- Tombol ini memungkinkan card dilipat --}}
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form id="jadwalGeneratorForm">
                                @csrf
                                <!-- Pilih Fasilitas -->
                                <div class="form-group">
                                    <label for="fasilitas_id">Pilih Fasilitas</label>
                                    <select id="fasilitas_id" name="fasilitas_id" class="form-control select2" required>
                                        <option value="">-- Pilih Fasilitas --</option>
                                        @foreach ($fasilitas as $f)
                                            <option value="{{ $f->id }}">{{ $f->nama_fasilitas }} -
                                                {{ $f->lokasi }}
                                            </option>
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
                                            <input class="custom-control-input" type="checkbox" id="hari_1" name="hari[]"
                                                value="1" checked>
                                            <label for="hari_1" class="custom-control-label">Senin</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_2" name="hari[]"
                                                value="2" checked>
                                            <label for="hari_2" class="custom-control-label">Selasa</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_3" name="hari[]"
                                                value="3" checked>
                                            <label for="hari_3" class="custom-control-label">Rabu</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_4" name="hari[]"
                                                value="4" checked>
                                            <label for="hari_4" class="custom-control-label">Kamis</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_5" name="hari[]"
                                                value="5" checked>
                                            <label for="hari_5" class="custom-control-label">Jumat</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mr-3 mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_6" name="hari[]"
                                                value="6" checked>
                                            <label for="hari_6" class="custom-control-label">Sabtu</label>
                                        </div>
                                        <div class="custom-control custom-checkbox mb-2">
                                            <input class="custom-control-input" type="checkbox" id="hari_0" name="hari[]"
                                                value="0" checked>
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
                                                <input type="time" id="jam_buka" name="jam_buka" class="form-control"
                                                    value="08:00" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Selesai</span>
                                                </div>
                                                <input type="time" id="jam_tutup" name="jam_tutup" class="form-control"
                                                    value="22:00" required>
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
                                {{-- Tombol ini memungkinkan card dilipat --}}
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
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
                    <div class="card card-success" id="tabel-jadwal">
                        <div class="card-header">
                            <h3 class="card-title"><b>Daftar Jadwal</b></h3>
                            <div class="card-tools d-flex align-items-center">
                                {{-- TOMBOL HAPUS MASSAL (Awalnya Sembunyi) --}}
                                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm mr-3"
                                    style="display: none;">
                                    <i class="fas fa-trash"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                                </button>

                                <form action="{{ route('petugas_fasilitas.jadwal.index') }}" method="GET"
                                    class="input-group input-group-sm" style="width: 200px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="Cari"
                                        value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

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
                                        {{-- Checkbox All di Header Kanan --}}
                                        <th class="text-center" style="width: 50px;">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="checkAll">
                                                <label for="checkAll" class="custom-control-label"></label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($jadwals as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->id }}</td>
                                            <td>{{ $jadwal->fasilitas->nama_fasilitas }}</td>
                                            <td>{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</td>
                                            <td>{{ substr($jadwal->jam_mulai, 0, 5) }} -
                                                {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
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
                                                <a href="{{ route('petugas_fasilitas.jadwal.edit', $jadwal->id) }}"
                                                    class="btn btn-xs btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($jadwal->status != 'terbooking')
                                                    <button type="button" class="btn btn-xs btn-danger"
                                                        onclick="confirmDelete({{ $jadwal->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            {{-- Checkbox Item di Baris Kanan --}}
                                            <td class="text-center">
                                                @if($jadwal->status != 'terbooking')
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input check-item" type="checkbox"
                                                            id="check_{{ $jadwal->id }}" value="{{ $jadwal->id }}">
                                                        <label for="check_{{ $jadwal->id }}" class="custom-control-label"></label>
                                                    </div>
                                                @else
                                                    <i class="fas fa-lock text-muted" title="Sedang dibooking"></i>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data jadwal</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 justify-content-center">
                                {{ $jadwals->links() }}
                            </ul>
                        </div>
                    </div>
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

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Konfirmasi Hapus Jadwal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus jadwal ini?</p>
                    <p>Jadwal yang sudah dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript untuk form generator -->
    <script>
        // Event handler saat dokumen siap
        document.addEventListener('DOMContentLoaded', function () {
            // Setup tombol preview
            const previewButton = document.getElementById('previewButton');
            if (previewButton) {
                previewButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    generatePreview();
                });
            }

            // Setup tombol generate
            const generateButton = document.getElementById('generateButton');
            if (generateButton) {
                generateButton.addEventListener('click', function (e) {
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
                confirmButton.addEventListener('click', function () {
                    // Tambahkan class loading dan disable button
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                    // Submit form
                    submitGenerateJadwal();
                });
            }

            // Setup validasi tanggal
            document.getElementById('tanggal_mulai').addEventListener('change', validateDayCheckboxes);
            document.getElementById('tanggal_selesai').addEventListener('change', validateDayCheckboxes);

            // Set tanggal default ke hari ini
            const today = new Date().toISOString().split('T')[0];
            if (!document.getElementById('tanggal_mulai').value) {
                document.getElementById('tanggal_mulai').value = today;
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

        // Submit data jadwal ke server
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
            document.querySelectorAll('input[name="hari[]"]:checked').forEach(function (checkbox) {
                selectedDays.push(checkbox.value);
            });

            // Tambahkan data hari ke FormData
            selectedDays.forEach(day => {
                formData.append('hari[]', day);
            });

            // Kirim data ke server dengan fetch API
            fetch('{{ route('petugas_fasilitas.jadwal.generate') }}', {
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
            document.querySelectorAll('input[name="hari[]"]:checked').forEach(function (checkbox) {
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

        // Generate preview jadwal
        function generatePreview() {
            try {
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
                document.querySelectorAll('input[name="hari[]"]:checked').forEach(function (checkbox) {
                    selectedDays.push(parseInt(checkbox.value));
                });

                if (selectedDays.length === 0) {
                    Swal.fire('Error', 'Pilih minimal satu hari', 'error');
                    return;
                }

                // Tampilkan loading
                const previewContainer = document.getElementById('previewContainer');
                previewContainer.innerHTML =
                    '<div class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-3">Memuat preview...</p></div>';

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

                        shownItems.forEach(function (jadwal) {
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
                Swal.fire('Error', 'Terjadi kesalahan saat membuat preview jadwal: ' + error.message, 'error');
            }
        }

        // Validasi checkbox hari berdasarkan rentang tanggal
        function validateDayCheckboxes() {
            const tanggalMulai = new Date(document.getElementById('tanggal_mulai').value);
            const tanggalSelesai = new Date(document.getElementById('tanggal_selesai').value);

            if (!tanggalMulai || !tanggalSelesai || isNaN(tanggalMulai) || isNaN(tanggalSelesai)) {
                // Jika tanggal tidak valid, aktifkan semua checkbox
                document.querySelectorAll('input[name="hari[]"]').forEach(function (checkbox) {
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
            document.querySelectorAll('input[name="hari[]"]').forEach(function (checkbox) {
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

        // Generate data jadwal berdasarkan parameter
        function generateJadwalData(tanggalMulai, tanggalSelesai, jamBuka, jamTutup, durasiSlot, selectedDays, fasilitasText) {
            const jadwalData = [];
            const daysOfWeek = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

            // Gunakan tanggal murni tanpa jam untuk menghindari masalah timezone
            let current = new Date(tanggalMulai + 'T00:00:00');
            const end = new Date(tanggalSelesai + 'T23:59:59');

            // Validasi input jam
            const [startH, startM] = jamBuka.split(':').map(Number);
            const [endH, endM] = jamTutup.split(':').map(Number);
            const totalOpenMinutes = (endH * 60 + endM) - (startH * 60 + startM);
            const slotMinutes = durasiSlot * 60;

            if (slotMinutes <= 0) return []; // Cegah infinite loop

            while (current <= end) {
                // Cek hari (0-6)
                if (selectedDays.includes(current.getDay())) {
                    // Format Tanggal YYYY-MM-DD secara manual agar tidak kena offset timezone
                    const year = current.getFullYear();
                    const month = String(current.getMonth() + 1).padStart(2, '0');
                    const day = String(current.getDate()).padStart(2, '0');
                    const dateFormatted = `${year}-${month}-${day}`;

                    const dayName = daysOfWeek[current.getDay()];

                    // Generate Slot Waktu
                    let slotStartTotalMinutes = startH * 60 + startM;

                    // Loop slot dalam satu hari
                    // Batas loop: Waktu mulai slot + durasi <= Waktu tutup
                    while ((slotStartTotalMinutes + slotMinutes) <= (endH * 60 + endM)) {

                        // Konversi menit ke HH:MM
                        const hStart = Math.floor(slotStartTotalMinutes / 60);
                        const mStart = slotStartTotalMinutes % 60;

                        const slotEndTotalMinutes = slotStartTotalMinutes + slotMinutes;
                        const hEnd = Math.floor(slotEndTotalMinutes / 60);
                        const mEnd = slotEndTotalMinutes % 60;

                        const timeStart = `${String(hStart).padStart(2, '0')}:${String(mStart).padStart(2, '0')}`;
                        const timeEnd = `${String(hEnd).padStart(2, '0')}:${String(mEnd).padStart(2, '0')}`;

                        jadwalData.push({
                            fasilitas: fasilitasText,
                            tanggal: dateFormatted,
                            hari: dayName,
                            jam_mulai: timeStart,
                            jam_selesai: timeEnd
                        });

                        // Pindah ke slot berikutnya
                        slotStartTotalMinutes += slotMinutes;
                    }
                }
                // Lanjut ke hari berikutnya
                current.setDate(current.getDate() + 1);
            }

            return jadwalData;
        }

        // Konfirmasi hapus jadwal
        function confirmDelete(id) {
            document.getElementById('deleteForm').action = '{{ route("petugas_fasilitas.jadwal.destroy", ":id") }}'.replace(':id', id);
            $('#deleteModal').modal('show');
        }

        // --- LOGIC CHECKBOX & BULK DELETE ---
    const checkAll = document.getElementById('checkAll');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');

    // Event Listener untuk Checkbox All
    if(checkAll) {
        checkAll.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.check-item');
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkButton();
        });
    }

    // Event Listener untuk Checkbox per Item (Delegation)
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('check-item')) {
            // Jika ada satu saja yg tidak dicentang, uncheck 'Check All'
            if(!e.target.checked) {
                checkAll.checked = false;
            }
            // Jika semua dicentang manual, check 'Check All'
            const allChecked = document.querySelectorAll('.check-item:checked').length === document.querySelectorAll('.check-item').length;
            if(allChecked) {
                checkAll.checked = true;
            }
            toggleBulkButton();
        }
    });

    function toggleBulkButton() {
        const count = document.querySelectorAll('.check-item:checked').length;
        selectedCountSpan.textContent = count;

        if(count > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }

    // Logic Eksekusi Hapus Massal
    bulkDeleteBtn.addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.check-item:checked')).map(cb => cb.value);

        if(selectedIds.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + selectedIds.length + ' jadwal?',
            text: "Jadwal yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim AJAX Request
                fetch('{{ route("petugas_fasilitas.jadwal.bulk_destroy") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Berhasil!', data.message, 'success')
                        .then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Error!', 'Terjadi kesalahan server.', 'error');
                });
            }
        })
    });
    </script>
@endsection
