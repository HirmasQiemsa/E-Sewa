{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h4 text-gray-900 mb-2">Daftar Akun Baru</h2>
        <p class="text-muted small">Bergabunglah untuk menyewa fasilitas olahraga DISPORA</p>
    </div>

    <form method="POST" action="{{ route('register-proses') }}" id="registerForm">
        @csrf

        <!-- Nama Lengkap -->
        <div class="form-group">
            <label for="name" class="form-label">
                <i class="fas fa-user text-primary mr-2"></i>Nama Lengkap
            </label>
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="Masukkan nama lengkap Anda">
            @error('name')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Username -->
        <div class="form-group">
            <label for="username" class="form-label">
                <i class="fas fa-at text-primary mr-2"></i>Username
            </label>
            <input id="username" class="form-control @error('username') is-invalid @enderror" type="text"
                name="username" value="{{ old('username') }}" required autocomplete="username"
                placeholder="Pilih username unik Anda">
            @error('username')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </div>
            @enderror
            <small class="form-text text-muted">
                <i class="fas fa-info-circle mr-1"></i>Username hanya boleh huruf dan angka, tanpa spasi
            </small>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope text-primary mr-2"></i>Email
                <span class="text-muted">(Opsional)</span>
            </label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                name="email" value="{{ old('email') }}" autocomplete="email"
                placeholder="contoh@email.com (boleh dikosongkan)">
            @error('email')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </div>
            @enderror
            <small class="form-text text-muted">
                <i class="fas fa-info-circle mr-1"></i>Email berguna untuk notifikasi booking. Jika belum punya email,
                bisa dikosongkan dahulu dan diisi nanti di profil.
            </small>
        </div>

        <!-- No HP -->
        <div class="form-group">
            <label for="no_hp" class="form-label">
                <i class="fas fa-phone text-primary mr-2"></i>Nomor HP/WhatsApp
            </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">+62</span>
                </div>
                <input id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" type="tel"
                    name="no_hp" value="{{ old('no_hp') }}" required autocomplete="tel" placeholder="8123456789"
                    pattern="[0-9]{10,13}">
                @error('no_hp')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            <small class="form-text text-muted">
                <i class="fas fa-info-circle mr-1"></i>Nomor akan digunakan untuk konfirmasi booking
            </small>
        </div>

        <!-- Alamat -->
        <div class="form-group">
            <label for="alamat" class="form-label">
                <i class="fas fa-map-marker-alt text-primary mr-2"></i>Alamat Lengkap
            </label>
            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat" required
                rows="3" placeholder="Masukkan alamat lengkap Anda">{{ old('alamat') }}</textarea>
            @error('alamat')
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock text-primary mr-2"></i>Password
            </label>
            <div class="input-group">
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password"
                    name="password" required autocomplete="new-password" placeholder="Minimal 6 karakter">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            <small class="form-text text-muted">
                <i class="fas fa-info-circle mr-1"></i>Gunakan kombinasi huruf, angka, dan simbol
            </small>
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock text-primary mr-2"></i>Konfirmasi Password
            </label>
            <div class="input-group">
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation"
                    required autocomplete="new-password" placeholder="Ulangi password yang sama">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        {{-- <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="terms" name="terms" required>
                <label class="custom-control-label" for="terms">
                    Saya setuju dengan <a href="#" class="auth-link" data-toggle="modal"
                        data-target="#termsModal">Syarat & Ketentuan</a>
                    dan <a href="#" class="auth-link" data-toggle="modal"
                        data-target="#privacyModal">Kebijakan Privasi</a>
                </label>
            </div>
        </div> --}}

        <!-- Submit Button -->
        <div class="form-group mb-0">
            <button type="submit" class="btn btn-primary btn-auth btn-block">
                <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-4">
            <p class="mb-0">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="auth-link">
                    <i class="fas fa-sign-in-alt mr-1"></i>Masuk di sini
                </a>
            </p>
        </div>
    </form>

    {{-- <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat & Ketentuan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>1. Pendaftaran Akun</h6>
                    <p>Dengan mendaftar, Anda menyetujui untuk memberikan informasi yang akurat dan terkini.</p>

                    <h6>2. Penggunaan Fasilitas</h6>
                    <p>Fasilitas hanya dapat digunakan sesuai jadwal yang telah dibooking dan dilunasi.</p>

                    <h6>3. Pembayaran</h6>
                    <p>DP 50% wajib dibayar saat booking, pelunasan dilakukan sebelum penggunaan fasilitas.</p>

                    <h6>4. Pembatalan</h6>
                    <p>Pembatalan dapat dilakukan dengan konsekuensi DP tidak dikembalikan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kebijakan Privasi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Pengumpulan Data</h6>
                    <p>Kami mengumpulkan data personal untuk keperluan administrasi booking fasilitas.</p>

                    <h6>Penggunaan Data</h6>
                    <p>Data digunakan untuk konfirmasi booking, komunikasi, dan pelayanan customer service.</p>

                    <h6>Keamanan Data</h6>
                    <p>Data Anda diamankan dengan enkripsi dan tidak akan dibagikan ke pihak ketiga.</p>
                </div>
            </div>
        </div>
    </div> --}}

    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                const icon = $(this).find('i');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            $('#togglePasswordConfirm').click(function() {
                const passwordField = $('#password_confirmation');
                const passwordFieldType = passwordField.attr('type');
                const icon = $(this).find('i');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Real-time password confirmation
            $('#password, #password_confirmation').on('keyup', function() {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                const confirmField = $('#password_confirmation');

                if (confirmPassword.length > 0) {
                    if (password === confirmPassword) {
                        confirmField.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        confirmField.removeClass('is-valid').addClass('is-invalid');
                    }
                }
            });

            // Email validation (opsional tapi jika diisi harus valid)
            $('#email').on('blur', function() {
                const email = $(this).val();
                const emailField = $(this);

                if (email.length > 0) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (emailRegex.test(email)) {
                        emailField.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        emailField.removeClass('is-valid').addClass('is-invalid');
                    }
                } else {
                    emailField.removeClass('is-invalid is-valid');
                }
            });

            // Phone number formatting
            $('#no_hp').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');

                // Remove leading zero if exists
                if (value.startsWith('0')) {
                    value = value.substring(1);
                }

                $(this).val(value);
            });

            // Form validation enhancement
            $('#registerForm').on('submit', function(e) {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                const email = $('#email').val();

                if (password !== confirmPassword) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Tidak Cocok',
                        text: 'Konfirmasi password harus sama dengan password.',
                        confirmButtonColor: '#dc3545'
                    });
                    return false;
                }

                // Validasi email jika diisi
                if (email.length > 0) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(email)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Email Tidak Valid',
                            text: 'Jika mengisi email, pastikan format email sudah benar.',
                            confirmButtonColor: '#dc3545'
                        });
                        return false;
                    }
                }

                if (!$('#terms').is(':checked')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Persetujuan Diperlukan',
                        text: 'Anda harus menyetujui Syarat & Ketentuan untuk melanjutkan.',
                        confirmButtonColor: '#ffc107'
                    });
                    return false;
                }
            });

            // Auto-resize textarea
            $('textarea').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
</x-guest-layout>
