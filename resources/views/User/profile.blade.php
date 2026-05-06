@extends('layouts.landing')

@section('content')
    {{-- Header Section --}}
    <div class="content-header bg-black shadow-sm mb-4" style="padding-top: 110px; padding-bottom: 20px;">
        <div class="container bg-white p-4 rounded-lg shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="font-weight-bold text-dark" style="font-size: 1.8rem;">Pengaturan Profil</h1>
                    <p class="text-muted mb-0">Kelola informasi pribadi dan keamanan akunmu di sini.</p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('user.beranda') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="alert alert-success shadow-sm mb-4 border-0">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger shadow-sm mb-4 border-0">{{ session('error') }}</div>
                @endif

                {{-- TAB NAVIGATION BUTTONS --}}
                <div class="d-flex justify-content-center mb-5">
                    <div class="custom-tab-container">
                        <button type="button" onclick="switchTab('profile')" id="btn-profile" class="btn-tab active">
                            <i class="fas fa-user mr-2"></i> Data Diri
                        </button>

                        <button type="button" onclick="switchTab('account')" id="btn-account" class="btn-tab">
                            <i class="fas fa-shield-alt mr-2"></i> Akun & Keamanan
                        </button>
                    </div>
                </div>

                {{-- SINGLE FORM START --}}
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 shadow-lg rounded-lg overflow-hidden">
                        <div class="card-body p-4 p-md-5">

                            {{-- ================= TAB 1: DATA DIRI ================= --}}
                            <div id="tab-profile" class="tab-section">
                                <h5 class="font-weight-bold mb-4 text-dark border-bottom pb-2">Informasi Biodata</h5>

                                <div class="row">
                                    {{-- Foto Profil --}}
                                    <div class="col-md-4 text-center mb-4">
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('images/default-user.png') }}"
                                                class="img-fluid rounded-circle shadow-sm"
                                                style="width:160px; height:160px; object-fit:cover; border: 4px solid #f8f9fa;">
                                            <label for="foto-input"
                                                class="position-absolute bg-primary text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; bottom: 10px; right: 10px; cursor: pointer;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                        </div>
                                        <input type="file" id="foto-input" name="foto" class="d-none"
                                            onchange="previewImage(this)">
                                        <div class="mt-2 text-muted small">Klik ikon kamera untuk ubah</div>
                                        @error('foto')
                                            <small class="text-danger d-block">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{-- Input Fields --}}
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="font-weight-bold small text-uppercase text-muted">Nama
                                                Lengkap</label>
                                            <input type="text" name="name"
                                                class="form-control form-control-lg bg-light border-0"
                                                value="{{ old('name', $user->name) }}" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold small text-uppercase text-muted">No
                                                        HP</label>
                                                    <input type="text" name="no_hp"
                                                        class="form-control form-control-lg bg-light border-0"
                                                        value="{{ old('no_hp', $user->no_hp) }}" required>
                                                    @error('no_hp')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold small text-uppercase text-muted">No KTP
                                                        (Opsional)</label>
                                                    <input type="text" name="no_ktp"
                                                        class="form-control form-control-lg bg-light border-0"
                                                        value="{{ old('no_ktp', $user->no_ktp) }}">
                                                    @error('no_ktp')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold small text-uppercase text-muted">Alamat</label>
                                            <textarea name="alamat" class="form-control form-control-lg bg-light border-0" rows="3" required>{{ old('alamat', $user->alamat) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ================= TAB 2: AKUN & PASSWORD ================= --}}
                            <div id="tab-account" class="tab-section" style="display: none;">
                                <h5 class="font-weight-bold mb-4 text-dark border-bottom pb-2">Kredensial Login</h5>

                                <div class="bg-light p-4 rounded-lg mb-4">
                                    <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-id-card mr-2"></i>Data
                                        Akun</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" name="username"
                                                    class="form-control border-0 shadow-sm"
                                                    value="{{ old('username', $user->username) }}" required>
                                                @error('username')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email"
                                                    class="form-control border-0 shadow-sm"
                                                    value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white border p-4 rounded-lg">
                                    <h6 class="text-danger font-weight-bold mb-3"><i class="fas fa-lock mr-2"></i>Ubah
                                        Password</h6>
                                    <p class="small text-muted mb-3">Kosongkan jika tidak ingin mengubah password.</p>

                                    {{-- 1. Password Lama --}}
                                    <div class="form-group">
                                        <label>Password Lama</label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="current_password"
                                                class="form-control bg-light border-right-0"
                                                placeholder="Wajib diisi jika ganti password">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-light border-left-0"
                                                    onclick="togglePassword('current_password')" style="cursor: pointer;">
                                                    <i class="fas fa-eye" id="icon-current_password"></i>
                                                </span>
                                            </div>
                                        </div>
                                        @error('current_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        {{-- 2. Password Baru --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password Baru</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" id="new_password"
                                                        class="form-control bg-light border-right-0">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-light border-left-0"
                                                            onclick="togglePassword('new_password')"
                                                            style="cursor: pointer;">
                                                            <i class="fas fa-eye" id="icon-new_password"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- 3. Konfirmasi Password --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Konfirmasi Password Baru</label>
                                                <div class="input-group">
                                                    <input type="password" name="password_confirmation"
                                                        id="confirm_password"
                                                        class="form-control bg-light border-right-0">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text bg-light border-left-0"
                                                            onclick="togglePassword('confirm_password')"
                                                            style="cursor: pointer;">
                                                            <i class="fas fa-eye" id="icon-confirm_password"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- SINGLE FOOTER BUTTON --}}
                        <div class="card-footer bg-white p-4 border-top-0 text-right">
                            <button type="submit"
                                class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm font-weight-bold">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
                {{-- SINGLE FORM END --}}

            </div>
        </div>
    </div>

    {{-- Script Langsung Disatuin Disini (Tanpa @push) --}}
    <script>
        // Ganti fungsi switchTab yang lama dengan yang ini:

        function switchTab(tabName) {
            // 1. Logic Hide/Show Form
            document.querySelectorAll('.tab-section').forEach(el => {
                el.style.opacity = '0'; // Efek fade out dikit
                setTimeout(() => el.style.display = 'none', 200); // Tunggu animasi
            });

            // Tampilkan tab baru dengan sedikit delay biar smooth
            setTimeout(() => {
                let activeTab = document.getElementById('tab-' + tabName);
                activeTab.style.display = 'block';
                // Hack sedikit biar transisi opacity jalan (browser butuh waktu render display:block dulu)
                setTimeout(() => activeTab.style.opacity = '1', 50);
            }, 200);

            // 2. Logic Ganti Style Tombol
            // Reset semua tombol ke style "mati"
            document.querySelectorAll('.btn-tab').forEach(btn => {
                btn.classList.remove('active');
            });

            // Set tombol yang diklik jadi "hidup"
            document.getElementById('btn-' + tabName).classList.add('active');
        }
        // 2. Logic Preview Foto (Biar pas upload langsung keliatan fotonya)
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    input.previousElementSibling.querySelector('img').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // 3. Logic Toggle Password (Mata)
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById('icon-' + fieldId);

            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection

@push('css')
    <style>
        /* Container pembungkus tombol (Background abu-abu lembut) */
        .custom-tab-container {
            background-color: #f1f3f5;
            /* Abu-abu muda modern */
            padding: 6px;
            border-radius: 50rem;
            /* Bikin lonjong (Pill shape) */
            display: inline-flex;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
            /* Shadow kedalam biar kesan 'tenggelam' */
        }

        /* Style dasar tombol */
        .btn-tab {
            border: none;
            border-radius: 50rem;
            padding: 10px 32px;
            /* Padding kiri-kanan lebih lega */
            font-weight: 600;
            font-size: 0.95rem;
            color: #868e96;
            /* Warna text abu-abu kalau gak aktif */
            background: transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            /* Animasi smooth */
            position: relative;
        }

        /* Efek Hover pas mouse lewat (sebelum diklik) */
        .btn-tab:hover:not(.active) {
            color: #343a40;
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Style tombol AKTIF (Hitam Elegan) */
        .btn-tab.active {
            background-color: #212529;
            /* Hitam pekat */
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            /* Shadow bayangan biar melayang */
            transform: scale(1.02);
            /* Sedikit membesar biar "pop" */
        }

        /* Fokus outline dihilangkan biar rapi */
        .btn-tab:focus {
            outline: none;
        }
    </style>
@endpush
