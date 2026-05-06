@extends('layouts.landing')

@section('content')
    {{-- Header Section --}}
    <div class="content-header bg-black shadow-sm" style="padding-top: 110px; padding-bottom: 10px;">
        <div class="container bg-white p-4 rounded-lg shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="font-weight-bold text-dark" style="font-size: 1.8rem;">
                        Riwayat Aktivitas
                    </h1>
                    <p class="text-muted mb-0">
                        Pantau status booking dan pengajuan event Anda.
                    </p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('user.beranda') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content bg-light border-top" id="riwayat" style="min-height: 80vh; padding-top: 20px;">
        <div class="container py-4">

            {{-- Filter & Nav --}}
            <div class="row mb-4 justify-content-end">
                <div class="col-lg-8 col-md-12">
                    <div class="d-flex flex-wrap justify-content-end align-items-center" style="gap: 15px;">

                        {{-- Date Picker --}}
                        <div class="filter-date-riwayat shadow-sm">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0 rounded-left-pill">
                                        <i class="far fa-calendar-alt text-danger"></i>
                                    </span>
                                </div>
                                <input type="text" id="historyDatePicker"
                                    class="form-control border-left-0 rounded-right-pill bg-white"
                                    placeholder="Filter Tanggal..." value="{{ request('history_date') }}"
                                    style="width: 150px; cursor: pointer;">
                            </div>
                        </div>

                        {{-- Tabs --}}
                        <ul class="nav nav-pills shadow-sm rounded-pill bg-white p-1" id="riwayatTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active rounded-pill font-weight-bold px-4" id="tab-booking"
                                    data-toggle="pill" href="#content-booking" role="tab" onclick="switchTab('booking')">
                                    <i class="fas fa-calendar-check mr-2"></i> Booking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill font-weight-bold px-4" id="tab-event" data-toggle="pill"
                                    href="#content-event" role="tab" onclick="switchTab('event')">
                                    <i class="fas fa-trophy mr-2"></i> Event
                                </a>
                            </li>
                        </ul>

                        {{-- Reset Button --}}
                        <button onclick="resetFilters()" class="btn btn-light rounded-circle text-muted"
                            title="Reset Filter" id="btnReset" style="display: none;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="card border-0 shadow-lg rounded-lg overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small text-uppercase font-weight-bold" id="tableHead"></thead>
                            <tbody id="tableBody"></tbody>
                        </table>

                        <div id="tableLoader" class="text-center py-5">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div id="tableEmpty" class="text-center py-5 d-none">
                            <i class="fas fa-history fa-2x mb-3 d-block opacity-20 text-muted"></i>
                            <p class="text-muted">Belum ada riwayat aktivitas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .filter-date-riwayat .form-control { height: 45px; font-size: 0.9rem; border: 1px solid #e0e0e0; }
        .filter-date-riwayat .input-group-text { border: 1px solid #e0e0e0; padding-left: 20px; }
        .nav-pills#riwayatTab { border: 1px solid #e0e0e0; }
        .nav-pills#riwayatTab .nav-link.active { background-color: #dc3545; color: #fff; }
        .nav-pills#riwayatTab .nav-link { color: #555; }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentTab = 'booking';
            const tbody = document.getElementById('tableBody');
            const thead = document.getElementById('tableHead');
            const loader = document.getElementById('tableLoader');
            const empty = document.getElementById('tableEmpty');
            const btnReset = document.getElementById('btnReset');

            // --- Flatpickr ---
            const fpHistory = flatpickr('#historyDatePicker', {
                locale: "id", dateFormat: "Y-m-d", mode: "range",
                onChange: (selectedDates, dateStr) => { loadData(dateStr); toggleResetBtn(true); }
            });

            // --- Tab Switching ---
            window.switchTab = (type) => {
                currentTab = type;
                document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
                document.getElementById(`tab-${type}`).classList.add('active');
                tbody.innerHTML = ''; loader.classList.remove('d-none'); empty.classList.add('d-none');
                loadData(document.getElementById('historyDatePicker').value);
            };

            // --- Load Data List (AJAX) ---
            function loadData(dateFilter = '') {
                const url = currentTab === 'booking' ? "{{ route('user.api.riwayat.booking') }}" : "{{ route('user.api.riwayat.event') }}";
                const params = new URLSearchParams();
                if (dateFilter) params.append('date', dateFilter);

                fetch(`${url}?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.json())
                    .then(data => { renderHeader(); renderTable(data); })
                    .catch(err => { console.error(err); loader.classList.add('d-none'); });
            }

            function renderHeader() {
                if (currentTab === 'booking') {
                    thead.innerHTML = `<tr><th>Fasilitas</th><th>Tanggal Main</th><th>Status</th><th class="text-center">Aksi</th></tr>`;
                } else {
                    thead.innerHTML = `<tr><th>Nama Event</th><th>Fasilitas</th><th>Tanggal Pelaksanaan</th><th>Status</th><th class="text-center">Aksi</th></tr>`;
                }
            }

            function renderTable(data) {
                loader.classList.add('d-none'); tbody.innerHTML = '';
                if (!data || data.length === 0) { empty.classList.remove('d-none'); return; }
                empty.classList.add('d-none');

                const getBadge = (status) => {
                    const colors = { 'pending': 'warning', 'approved': 'success', 'lunas': 'success', 'rejected': 'danger', 'batal': 'danger', 'selesai': 'info' };
                    return `<span class="badge badge-${colors[status] || 'secondary'} px-3 py-2 rounded-pill text-uppercase small">${status}</span>`;
                };

                data.forEach(item => {
                    let row = '';
                    if (currentTab === 'booking') {
                        const detailUrl = `{{ route('user.riwayat.detail.booking', ':id') }}`.replace(':id', item.id);
                        row = `<td><span class="font-weight-bold">${item.fasilitas?.nama_fasilitas || '-'}</span></td>
                               <td>${item.tanggal_indo}</td>
                               <td>${getBadge(item.status)}</td>
                               <td class="text-center">
                                   <a href="${detailUrl}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Detail</a>
                               </td>`;
                    } else {
                        const detailUrl = `{{ route('user.riwayat.detail.event', ':id') }}`.replace(':id', item.id);
                        row = `<td><span class="font-weight-bold">${item.nama_event}</span></td>
                               <td>${item.fasilitas?.nama_fasilitas || '-'}</td>
                               <td>${item.tgl_mulai_indo} s.d ${item.tgl_selesai_indo}</td>
                               <td>${getBadge(item.status)}</td>
                               <td class="text-center">
                                   <a href="${detailUrl}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Detail</a>
                               </td>`;
                    }
                    tbody.insertAdjacentHTML('beforeend', `<tr>${row}</tr>`);
                });
            }

            window.resetFilters = () => { fpHistory.clear(); toggleResetBtn(false); loadData(); };
            function toggleResetBtn(show) { btnReset.style.display = show ? 'inline-block' : 'none'; }

            // Load awal
            renderHeader();
            loadData();
        });
    </script>
@endpush
