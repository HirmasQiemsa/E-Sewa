
<x-guest-layout>
    @section('title','Register E-Sewa')
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />
        <form method="POST" action="{{ route('register-proses') }}">
            @csrf

            <div>
                <x-label for="name" value="Nama Lengkap" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="alamat" value="Alamat" />
                <x-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat')" required />
            </div>

            <div class="mt-4">
                <x-label for="no_ktp" value="Nomor KTP" />
                <x-input id="no_ktp" class="block mt-1 w-full" type="text" name="no_ktp" :value="old('no_ktp')" required />
            </div>

            <div class="mt-4">
                <x-label for="no_hp" value="No. Handphone" />
                <x-input id="no_hp" class="block mt-1 w-full" type="text" name="no_hp" :value="old('no_hp')" required />
            </div>

            <div class="mt-4">
                <x-label for="email" value="Email" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Password" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="Konfirmasi Password" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ url('/login') }}">
                    Sudah punya akun?
                </a>

                <x-button class="ml-4">
                    Registrasi
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
