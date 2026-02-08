@extends('User.component')

@push('css')
    <style>
        /* WRAPPER PENCARIAN & TANGGAL */
        .search-date-wrapper {
            background: #fff;
            border-radius: 50px;
            padding: 5px 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            height: 60px;
            width: 100%;
            border: 1px solid #f0f0f0;
            position: relative; /* Penting untuk posisi dropdown search */
        }

        .custom-search-input {
            border: none;
            background: transparent;
            height: 100%;
            width: 100%;
            padding-left: 15px;
            font-size: 1rem;
            outline: none;
        }

        .vertical-divider {
            width: 1px;
            height: 30px;
            background-color: #e0e0e0;
            margin: 0 15px;
        }

        .date-btn-custom {
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            color: #333;
            font-weight: 600;
            padding-right: 15px;
            cursor: pointer;
            height: 100%;
        }
        .date-btn-custom:hover { color: #dc3545; }

        /* Card Fasilitas */
        .facility-card-link {
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            transition: transform 0.2s;
        }
        .facility-card-link:hover { transform: translateY(-5px); }

        .status-badge {
            position: absolute; top: 15px; right: 15px;
            padding: 5px 12px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 700; z-index: 10;
        }

        .bg-overlay-dark {
            background: linear-gradient(to top, rgba(0,0,0,0.8), rgba(0,0,0,0));
            position: absolute; bottom: 0; left: 0;
            width: 100%; height: 70%;
            border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;
        }

        /* FIX KALENDER AGAR MUNCUL DI ATAS */
        .flatpickr-calendar { z-index: 99999 !important; }
    </style>
@endpush

@section('content')
    <section class="content pt-4">
        <div class="container-fluid">

            {{-- Alert Pembayaran --}}
            @if ($pendingBooking && $pendingBooking->jadwal)
                <div class="alert alert-warning border-0 shadow-sm rounded-lg mb-4 d-flex align-items-center">
                    <i class="fas fa-bell fa-lg mr-3 text-warning"></i>
                    <div>
                        <strong>Menunggu Pembayaran!</strong> Selesaikan tagihan {{ $pendingBooking->jadwal->fasilitas->nama_fasilitas }}.
                    </div>
                    <a href="{{ route('user.checkout.detail', $pendingBooking->id) }}" class="btn btn-warning btn-sm ml-auto rounded-pill px-4">Bayar</a>
                </div>
            @endif

            {{-- Header Search & Date --}}
            <div class="row mb-5 align-items-center">
                <div class="col-md-5 mb-3 mb-md-0">
                    <h2 class="font-weight-bold text-dark mb-1">Fasilitas & Jadwal</h2>
                    <p class="text-muted m-0">Pilih tanggal dan temukan lapangan favoritmu.</p>
                </div>

                <div class="col-md-7">
                    <div class="search-date-wrapper">
                        <i class="fas fa-search text-muted ml-2"></i>
                        <input type="text" id="liveSearch" class="custom-search-input" placeholder="Temukan nama lapangan..." autocomplete="off">

                        <div class="vertical-divider d-none d-sm-block"></div>

                        {{-- Tombol Tanggal sebagai Trigger --}}
                        <button type="button" id="dateTrigger" class="date-btn-custom">
                            {{-- Tampilkan Tanggal Terpilih --}}
                            <span id="dateLabel">{{ \Carbon\Carbon::parse($selectedDate)->isoFormat('D MMMM Y') }}</span>
                            <i class="fas fa-calendar-alt text-danger" style="font-size: 1.2rem;"></i>
                        </button>

                        {{-- Input Hidden tapi VISIBLE secara CSS agar Flatpickr bisa nempel --}}
                        <input type="text" id="datePickerInput" value="{{ $selectedDate }}"
                               style="position: absolute; opacity: 0; height: 0; width: 0; pointer-events: none;">
                    </div>
                </div>
            </div>

            {{-- Grid Fasilitas --}}
            <div class="row" id="facilityContainer">
                @forelse($fasilitas as $f)
                    <div class="col-lg-4 col-md-6 mb-4 facility-item" data-name="{{ strtolower($f->nama_fasilitas) }}">
                        @php
                            $isDisabled = in_array($f->status_type, ['closed', 'locked']);
                            // Pastikan parameter date terisi agar terbawa ke halaman detail
                            $link = $isDisabled ? '#' : route('user.fasilitas.detail', ['id' => $f->id, 'date' => $selectedDate]);
                        @endphp

                        <a href="{{ $link }}" class="facility-card-link {{ $isDisabled ? 'cursor-not-allowed' : '' }}"
                           @if($isDisabled) onclick="return false;" @endif>

                            <div class="card border-0 shadow-sm h-100 overflow-hidden text-white"
                                 style="border-radius: 15px; min-height: 250px; background-size: cover; background-position: center;
                                 background-image: url('{{ !empty($f->foto) ? asset('storage/' . $f->foto) : 'https://source.unsplash.com/random/500x300/?sport' }}');">

                                @if($f->status_type == 'available')
                                    <span class="status-badge bg-success shadow">Buka</span>
                                @elseif($f->status_type == 'full')
                                    <span class="status-badge bg-danger shadow">Penuh</span>
                                @else
                                    <span class="status-badge bg-secondary shadow">Tutup</span>
                                @endif

                                <div class="bg-overlay-dark"></div>

                                <div class="card-body d-flex flex-column justify-content-end position-relative h-100 p-4">
                                    <h3 class="font-weight-bold mb-1">{{ $f->nama_fasilitas }}</h3>
                                    <p class="mb-2 text-white-50"><i class="fas fa-map-marker-alt mr-1"></i> {{ $f->lokasi }}</p>

                                    <div class="d-flex justify-content-between align-items-end mt-2">
                                        <div>
                                            @if($f->status_type == 'available')
                                                <span class="text-warning font-weight-bold" style="font-size: 1.1rem;">{{ $f->total_slot }} Slot</span>
                                                <small class="d-block text-white">Tersedia Hari Ini</small>
                                            @else
                                                <span class="text-white font-weight-bold">{{ $f->status_text }}</span>
                                            @endif
                                        </div>
                                        <div class="btn btn-light rounded-circle btn-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-arrow-right text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('img/empty.svg') }}" alt="Kosong" width="150" class="mb-3 opacity-50">
                        <h5 class="text-muted">Tidak ada fasilitas tersedia.</h5>
                    </div>
                @endforelse

                <div id="noResult" class="col-12 text-center py-5 d-none">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Fasilitas tidak ditemukan...</h5>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. LIVE SEARCH
            const searchInput = document.getElementById('liveSearch');
            const items = document.querySelectorAll('.facility-item');
            const noResult = document.getElementById('noResult');

            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                let hasVisible = false;
                items.forEach(item => {
                    if (item.getAttribute('data-name').includes(term)) {
                        item.classList.remove('d-none'); item.classList.add('d-block');
                        hasVisible = true;
                    } else {
                        item.classList.remove('d-block'); item.classList.add('d-none');
                    }
                });
                if(!hasVisible) noResult.classList.remove('d-none');
                else noResult.classList.add('d-none');
            });

            // 2. FIX KALENDER FLATPICKR
            const dateTrigger = document.getElementById("dateTrigger");
            const dateInput = document.getElementById("datePickerInput");

            const fp = flatpickr(dateInput, {
                locale: "id",
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: "true",
                positionElement: dateTrigger, // Kalender muncul nempel di tombol
                clickOpens: true, // Pastikan bisa diklik

                onChange: function(selectedDates, dateStr, instance) {
                     // Loading Effect
                     if(typeof Swal !== 'undefined'){
                        Swal.fire({ title: 'Memuat...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                     }
                     // Reload Halaman dengan Parameter Tanggal Baru
                     window.location.href = "{{ route('user.fasilitas') }}?date=" + dateStr;
                }
            });

            // Trigger manual jika tombol diklik (Safety net)
            dateTrigger.addEventListener("click", function(e) {
                e.stopPropagation();
                fp.open();
            });
        });
    </script>
@endpush
