@extends('Admin.admin')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gunakan Fasilitas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ '/fasilitas' }}">Kelola Fasilitas</a></li>
                        <li class="breadcrumb-item active">Gunakan Fasilitas</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.fasilitas.storeG') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-8">
                        <!-- general form elements -->
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Form Penggunaan Fasilitas</h3>
                            </div>
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Fasilitas</label>
                                    <input type="text" class="form-control"
                                        value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas . ' - ' . $fasilitas->tipe . ' - ' . $fasilitas->lokasi) }}"
                                        readonly>
                                    @error('nama_fasilitas')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <!-- Input tersembunyi untuk id dan harga fasilitas -->
                                    <input type="hidden" name="fasilitas_id" id="fasilitas" value="{{ $fasilitas->id }}">
                                    <input type="hidden" id="fasilitas_harga" value="{{ $fasilitas->harga }}">
                                </div>

                                <div class="form-group">
                                    <label for="user_id">Pilih User (kosongkan jika event)</label>
                                    <select name="user_id" class="form-control">
                                        <option value="">-- Tidak ada user --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tanggal, waktu mulai & selesai (dengan id disesuaikan ke script) -->
                                <div class="form-group d-flex justify-content-between align-items-start">
                                    <div>
                                        <label for="InputTanggal1">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" id="InputTanggal1"
                                            placeholder="Tanggal Digunakan">
                                        @error('tanggal')
                                            <small>{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <label for="jam_mulai">Waktu Mulai</label>
                                            <input type="time" name="waktu_mulai" class="form-control" id="jam_mulai"
                                                step="3600" value="12:00">
                                            @error('waktu_mulai')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="mr-4">
                                            <label for="jam_selesai">Waktu Selesai</label>
                                            <input type="time" name="waktu_selesai" class="form-control" id="jam_selesai"
                                                step="3600" value="12:00">
                                            @error('waktu_selesai')
                                                <small>{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="mb-0">Durasi: </label>
                                            <p id="durasi_text" class="form-control-plaintext font-weight-bold mb-0 ml-2">-
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Total harga -->
                                <div class="form-group">
                                    <label for="harga">Total Harga</label>
                                    <input type="number" name="harga" value="0" step="1000" class="form-control"
                                        id="harga" placeholder="Harga">
                                    @error('harga')
                                        <small>{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-start">
                                    <!-- Checkbox event -->
                                    <div class="text-danger">
                                        <label>
                                            <input type="hidden" name="is_event" value="0">
                                            <input type="checkbox" name="is_event" id="is_event" value="1"> Event
                                            Resmi
                                        </label>
                                        @error('is_event')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-dark w-100">Submit</button>
                                </div>
                            </div>
                        </div>
                        <!--/.col (left) -->
                    </div>
            </form>
        </div>
    </section>
    <!-- /.content -->


    <script>
        const hargaInput = document.getElementById('harga');
        const jamMulaiInput = document.getElementById('jam_mulai');
        const jamSelesaiInput = document.getElementById('jam_selesai');
        const isEvent = document.getElementById('is_event');
        const isKomunitas = document.getElementById('is_komunitas'); // pindahkan ke sini
        const fasilitasHarga = document.getElementById('fasilitas_harga');

        function hitungHarga() {
            const durasiText = document.getElementById('durasi_text');

            const jamMulai = jamMulaiInput.value;
            const jamSelesai = jamSelesaiInput.value;

            if (jamMulai && jamSelesai) {
                const [h1] = jamMulai.split(':').map(Number);
                const [h2] = jamSelesai.split(':').map(Number);

                if (h2 <= h1) {
                    hargaInput.value = 0;
                    durasiText.textContent = '-';
                    return;
                }

                const durasi = h2 - h1;
                durasiText.textContent = `${durasi} jam`;

                const hargaPerJam = parseInt(fasilitasHarga.value) || 0;
                let totalHarga = durasi * hargaPerJam;

                if (isEvent.checked) {
                    totalHarga = 0;
                } else if (isKomunitas.checked) {
                    totalHarga *= 0.5;
                }

                hargaInput.value = totalHarga;
            } else {
                hargaInput.value = 0;
                durasiText.textContent = '-';
            }
        }

        function toggleHargaInput() {
            const hargaFormGroup = document.getElementById('harga').closest('.form-group');

            if (isEvent.checked) {
                hargaFormGroup.style.display = 'none';
            } else {
                hargaFormGroup.style.display = 'block';
            }
        }

        jamMulaiInput.addEventListener('input', hitungHarga);
        jamSelesaiInput.addEventListener('input', hitungHarga);

        isEvent.addEventListener('change', () => {
            if (isEvent.checked) {
                isKomunitas.checked = false;
            }
            hitungHarga();
            toggleHargaInput();
        });

        isKomunitas.addEventListener('change', () => {
            if (isKomunitas.checked) {
                isEvent.checked = false;
            }
            hitungHarga();
        });

        document.addEventListener('DOMContentLoaded', toggleHargaInput);
    </script>

@endsection
