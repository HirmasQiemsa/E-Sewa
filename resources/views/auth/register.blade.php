<x-guest-layout>
    @section('title','Register E-Sewa')
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('register-proses') }}" id="registerForm">
            @csrf

            <div>
                <x-label for="username" value="Username" />
                <x-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
                <small id="usernameHelp" class="text-xs text-gray-500">Username tidak boleh mengandung spasi. Gunakan huruf, angka, dan underscore.</small>
                <small id="usernameError" class="text-xs text-red-600 hidden">Username tidak boleh mengandung spasi!</small>
            </div>

            <div class="mt-4">
                <x-label for="name" value="Nama Lengkap" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                <small id="nameHelp" class="text-xs text-gray-500">Masukkan nama lengkap Anda.</small>
            </div>

            <div class="mt-4">
                <x-label for="alamat" value="Alamat" />
                <x-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat')" required />
            </div>

            <div class="mt-4">
                <x-label for="no_hp" value="No. Handphone" />
                <x-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')" required />
                <small id="phoneHelp" class="text-xs text-gray-500">Format: 08xxxxxxxxxx (tanpa tanda atau spasi)</small>
                <small id="phoneError" class="text-xs text-red-600 hidden">Nomor handphone hanya boleh berisi angka!</small>
            </div>

            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <small id="passwordHelp" class="text-xs text-gray-500">Minimal 8 karakter</small>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Konfirmasi Password" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ url('/login') }}">
                    Sudah punya akun?
                </a>

                <x-button class="ml-4" id="submitBtn">
                    Registrasi
                </x-button>
            </div>
        </form>
    </x-authentication-card>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('usernameError');
            const phoneInput = document.getElementById('no_hp');
            const phoneError = document.getElementById('phoneError');
            const form = document.getElementById('registerForm');

            // Username validation (no spaces allowed)
            usernameInput.addEventListener('input', function() {
                if (this.value.includes(' ')) {
                    usernameError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    usernameError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });

            // Phone number validation (numbers only)
            phoneInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (!/^\d*$/.test(this.value)) {
                    phoneError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    phoneError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });

            // Form validation before submit
            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Check username
                if (usernameInput.value.includes(' ')) {
                    usernameError.classList.remove('hidden');
                    usernameInput.classList.add('border-red-500');
                    isValid = false;
                }

                // Check phone number
                if (!/^\d+$/.test(phoneInput.value)) {
                    phoneError.classList.remove('hidden');
                    phoneInput.classList.add('border-red-500');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-guest-layout>
