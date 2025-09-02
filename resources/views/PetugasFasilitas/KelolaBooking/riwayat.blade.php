@extends('PetugasFasilitas.component')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Riwayat Aktivitas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('petugas_fasilitas.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Riwayat Aktivitas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-default">
            <div class="card-header bg-light">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-1"></i>
                    Filter Aktivitas
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="filter-form" method="GET" action="{{ route('petugas_fasilitas.riwayat') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dari Tanggal:</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sampai Tanggal:</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="type" class="form-control">
                                    <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="fee" {{ $type == 'fee' ? 'selected' : '' }}>DP Terbayar</option>
                                    <option value="lunas" {{ $type == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="batal" {{ $type == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                                    <option value="selesai" {{ $type == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cari:</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama, fasilitas..." value="{{ $search }}">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter mr-1"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('petugas_fasilitas.riwayat') }}" class="btn btn-default">
                                    <i class="fas fa-sync-alt mr-1"></i> Reset
                                </a>
                                <button type="button" class="btn btn-success" onclick="printRiwayat()">
                                    <i class="fas fa-print mr-1"></i> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Riwayat Aktivitas Table -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i>
                    Daftar Aktivitas Booking
                </h3>
                <div class="card-tools">
                    <span class="badge bg-white text-dark">{{ $activities->total() }} aktivitas ditemukan</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Tanggal/Jam</th>
                                <th width="15%">User</th>
                                <th width="15%">Fasilitas</th>
                                <th width="30%">Kegiatan</th>
                                <th width="10%">Status</th>
                                <th width="10%" class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($formattedActivities as $index => $activity)
                                <tr>
                                    <td>{{ $activities->firstItem() + $index }}</td>
                                    <td>{{ date('d/m/Y H:i', strtotime($activity['created_at'])) }}</td>
                                    <td>
                                        @if($activity['user'])
                                            <strong>{{ $activity['user']->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $activity['user']->email ?? 'No email' }}</small>
                                        @else
                                            <span class="text-muted">System</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity['fasilitas'])
                                            <strong>{{ $activity['fasilitas']->nama_fasilitas }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $activity['fasilitas']->lokasi }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $activity['description'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $activity['status_color'] }} px-2 py-1">
                                            {{ $activity['status'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('petugas_fasilitas.booking.detail', $activity['id']) }}"
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>Tidak ada data aktivitas yang ditemukan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <div class="float-left">
                    Menampilkan {{ $activities->firstItem() ?? 0 }}-{{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} aktivitas
                </div>
                <div class="float-right">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Print Template (hidden) -->
<div id="print-template" style="display: none;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2 style="margin-bottom: 5px;">LAPORAN RIWAYAT AKTIVITAS BOOKING</h2>
            <h3 style="margin-top: 5px; margin-bottom: 10px;">DISPORA KOTA SEMARANG</h3>
            <p style="margin-top: 5px;">Periode: <span id="print-period"></span></p>
        </div>
        <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; background-color: #f5f5f5;">
            <p style="margin-bottom: 5px;"><strong>Filter yang digunakan:</strong></p>
            <p style="margin-top: 5px;">Tanggal: <span id="print-dates"></span> | Status: <span id="print-status"></span> | Pencarian: <span id="print-search"></span></p>
        </div>
        <table id="print-table" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">No</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Tanggal/Jam</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">User</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Fasilitas</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Kegiatan</th>
                    <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Will be filled by JavaScript -->
            </tbody>
        </table>
        <div style="margin-top: 30px; text-align: right;">
            <p>Dicetak pada: <span id="print-date"></span></p>
            <p style="margin-top: 50px;">Petugas Fasilitas</p>
            <p style="margin-top: 40px; border-bottom: 1px solid #000; display: inline-block; min-width: 200px;"></p>
            <p id="print-user"></p>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit when selecting date or status
        document.querySelectorAll('input[name="start_date"], input[name="end_date"], select[name="type"]').forEach(function(el) {
            el.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
    });

    // Print functionality
    function printRiwayat() {
        // Set print template content
        document.getElementById('print-period').textContent = '{{ $startDate }} sampai {{ $endDate }}';
        document.getElementById('print-dates').textContent = '{{ $startDate }} - {{ $endDate }}';
        document.getElementById('print-status').textContent = document.querySelector('select[name="type"] option:checked').text;
        document.getElementById('print-search').textContent = '{{ $search }}' || '-';
        document.getElementById('print-date').textContent = new Date().toLocaleString('id-ID');
        document.getElementById('print-user').textContent = '{{ Auth::guard("petugas_fasilitas")->user()->name }}';

        // Fill table
        const printTableBody = document.querySelector('#print-table tbody');
        printTableBody.innerHTML = '';

        const rows = document.querySelectorAll('.table tbody tr');
        rows.forEach((row, index) => {
            if (!row.querySelector('td[colspan]')) { // Skip "no data" rows
                const printRow = document.createElement('tr');

                // Create cells except the last column (Detail)
                const cells = row.querySelectorAll('td');
                for (let i = 0; i < cells.length - 1; i++) {
                    const cell = document.createElement('td');
                    cell.style.border = '1px solid #ddd';
                    cell.style.padding = '8px';

                    // Special case for status badges
                    if (i === 5) { // Status column
                        const badge = cells[i].querySelector('.badge');
                        if (badge) {
                            cell.textContent = badge.textContent.trim();
                        } else {
                            cell.textContent = cells[i].textContent.trim();
                        }
                    } else {
                        cell.textContent = cells[i].textContent.trim();
                    }

                    printRow.appendChild(cell);
                }

                printTableBody.appendChild(printRow);
            }
        });

        // Open print window
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Laporan Riwayat Aktivitas</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    @media print {
                        body { margin: 0; padding: 20px; }
                    }
                </style>
            </head>
            <body>
                ${document.getElementById('print-template').innerHTML}
            </body>
            </html>
        `);

        printWindow.document.close();

        // Print after slight delay to ensure content is loaded
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 500);
    }
</script>
@endsection
