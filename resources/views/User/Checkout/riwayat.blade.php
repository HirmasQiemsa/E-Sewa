@extends('User.user')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Pemesanan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('user.fasilitas') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Riwayat</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filter and Search Tools -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>Filter dan Pencarian
                    </h3>
                </div>
                <div class="card-body">
                    <form id="filter-form" action="{{ route('user.riwayat') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter Status:</label>
                                    <select id="status-filter" name="status" class="form-control">
                                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                                        <option value="fee" {{ request('status') == 'fee' ? 'selected' : '' }}>Menunggu Pelunasan (DP)</option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Filter Tanggal:</label>
                                    <input type="month" id="month-filter" name="month" class="form-control" value="{{ request('month', date('Y-m')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Pencarian:</label>
                                    <div class="input-group">
                                        <input type="text" id="booking-search" name="search" class="form-control"
                                               placeholder="Cari fasilitas, lokasi..." value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <a href="{{ route('user.riwayat') }}" class="btn btn-default btn-block">
                                        <i class="fas fa-redo mr-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat Pemesanan List -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-2"></i>Daftar Riwayat Pemesanan
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="printSummary()">
                            <i class="fas fa-print mr-1"></i> Cetak Riwayat
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" id="riwayat-table">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 12%">ID Booking</th>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 20%">Fasilitas</th>
                                <th style="width: 10%">Durasi</th>
                                <th style="width: 12%">Total</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 16%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkouts as $index => $checkout)
                                <tr class="booking-row"
                                    data-id="{{ $checkout->id }}"
                                    data-status="{{ $checkout->status }}"
                                    data-date="{{ date('Y-m', strtotime($checkout->jadwal ? $checkout->jadwal->tanggal : $checkout->created_at)) }}">
                                    <td>{{ ($checkouts->currentPage() - 1) * $checkouts->perPage() + $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">#{{ $checkout->id }}</span>
                                    </td>
                                    <td>{{ $checkout->jadwal ? date('d-m-Y', strtotime($checkout->jadwal->tanggal)) : '-' }}</td>
                                    <td>
                                        @if($checkout->jadwal && $checkout->jadwal->fasilitas)
                                            <strong>{{ $checkout->jadwal->fasilitas->nama_fasilitas }}</strong><br>
                                            <small class="text-muted">{{ $checkout->jadwal->fasilitas->lokasi }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $checkout->totalDurasi ?? '-' }} jam</td>
                                    <td>Rp {{ number_format($checkout->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($checkout->status == 'fee')
                                            <span class="badge badge-warning">Menunggu Pelunasan</span>
                                        @elseif($checkout->status == 'lunas')
                                            <span class="badge badge-success">Lunas</span>
                                        @elseif($checkout->status == 'batal')
                                            <span class="badge badge-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('user.checkout.detail', $checkout->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($checkout->status == 'lunas')
                                                <button type="button" class="btn btn-sm btn-primary" title="Cetak Bukti"
                                                        onclick="printReceipt({{ $checkout->id }})">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            @endif

                                            @if ($checkout->status == 'fee')
                                                <a href="{{ route('user.checkout.pelunasan', $checkout->id) }}" class="btn btn-sm btn-success" title="Lunasi Pembayaran">
                                                    <i class="fas fa-money-bill"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" title="Batalkan Pesanan"
                                                        onclick="confirmCancel({{ $checkout->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>Belum ada riwayat pemesanan
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-left">
                        {{ $checkouts->links() }}
                    </div>
                    <div class="float-right">
                        <span id="showing-entries">
                            Menampilkan {{ $checkouts->firstItem() ?? 0 }}-{{ $checkouts->lastItem() ?? 0 }} dari
                            {{ $checkouts->total() }} entri
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Batal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Apakah Anda yakin ingin membatalkan booking ini? DP tidak akan dikembalikan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="cancel-form" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Receipt Template (hidden) -->
    <div id="print-receipt-template" style="display: none;">
        <div style="width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="margin-bottom: 5px; color: #c00000;">E-SEWA FASILITAS DISPORA SEMARANG</h2>
                <p style="margin-top: 0; color: #666;">Jl. Pemuda No. 10, Semarang</p>
                <div style="margin: 15px 0; border-bottom: 2px dashed #ccc;"></div>
                <h3>BUKTI PEMBAYARAN</h3>
            </div>

            <div id="receipt-content">
                <!-- This will be filled dynamically -->
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <p style="margin-bottom: 5px;">Terima kasih telah menggunakan fasilitas DISPORA Kota Semarang</p>
                <p style="color: #666; font-size: 12px;">Dokumen ini dibuat pada: <span id="print-date"></span></p>
                <p style="color: #666; font-size: 12px;">Dokumen ini sah tanpa tanda tangan dan stempel</p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Function to handle cancel confirmation
        function confirmCancel(id) {
            document.getElementById('cancel-form').action = `/checkout/cancel/${id}`;
            $('#cancelModal').modal('show');
        }

        // Auto submit form on select change
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when these filters change
            document.getElementById('status-filter').addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });

            document.getElementById('month-filter').addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });

            // Prepare all rows for printing data
            document.querySelectorAll('.booking-row').forEach(row => {
                const idCell = row.querySelector('td:nth-child(2)');
                if (idCell) {
                    const bookingId = row.dataset.id;
                    row.setAttribute('data-booking-id', bookingId);
                }
            });
        });

        // Function to print individual receipt
        function printReceipt(bookingId) {
            // Get details from the data attributes or make an AJAX call
            const row = document.querySelector(`.booking-row[data-id="${bookingId}"]`);

            if (!row) {
                alert('Data tidak ditemukan');
                return;
            }

            // Get booking details from the row
            const cells = row.querySelectorAll('td');
            const bookingNumber = cells[1].textContent.trim();
            const bookingDate = cells[2].textContent.trim();
            const facility = cells[3].textContent.trim();
            const duration = cells[4].textContent.trim();
            const total = cells[5].textContent.trim();
            const status = cells[6].textContent.trim();

            // Build receipt content
            const receiptContent = `
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                    <tr>
                        <td style="width: 30%; padding: 8px; font-weight: bold;">Nomor Booking</td>
                        <td style="width: 70%; padding: 8px;">${bookingNumber}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 8px; font-weight: bold;">Tanggal Booking</td>
                        <td style="padding: 8px;">${bookingDate}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; font-weight: bold;">Fasilitas</td>
                        <td style="padding: 8px;">${facility}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 8px; font-weight: bold;">Durasi</td>
                        <td style="padding: 8px;">${duration}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; font-weight: bold;">Total Pembayaran</td>
                        <td style="padding: 8px; font-weight: bold;">${total}</td>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <td style="padding: 8px; font-weight: bold;">Status</td>
                        <td style="padding: 8px;">
                            <span style="padding: 3px 8px; background-color: #28a745; color: white; border-radius: 4px;">
                                ${status}
                            </span>
                        </td>
                    </tr>
                </table>

                <div style="border: 1px solid #ddd; padding: 15px; background-color: #f9f9f9; border-radius: 4px;">
                    <p style="margin-top: 0;"><strong>Catatan:</strong></p>
                    <ol style="margin-bottom: 0; padding-left: 20px;">
                        <li>Bukti pembayaran ini harap dibawa saat menggunakan fasilitas.</li>
                        <li>Keterlambatan 15 menit dari jadwal yang ditentukan akan dianggap tidak hadir.</li>
                        <li>Untuk informasi lebih lanjut, silakan hubungi (024) 86437489.</li>
                    </ol>
                </div>
            `;

            // Set receipt content and current date
            document.getElementById('receipt-content').innerHTML = receiptContent;
            document.getElementById('print-date').textContent = new Date().toLocaleString('id-ID', {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            // Print the receipt
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Bukti Pembayaran #${bookingId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        @media print {
                            body { margin: 0; padding: 20px; }
                        }
                    </style>
                </head>
                <body>
                    ${document.getElementById('print-receipt-template').innerHTML}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            // Print after a slight delay to ensure content is loaded
            setTimeout(() => {
                printWindow.print();
                if (!printWindow.closed) {
                    printWindow.close();
                }
            }, 500);
        }

        // Function to print all booking summary
        function printSummary() {
            // Make AJAX call to get all filtered data
            // For demonstration, we'll use the currently visible rows
            const visibleRows = document.querySelectorAll('.booking-row');

            if (visibleRows.length === 0) {
                alert('Tidak ada data yang dapat dicetak');
                return;
            }

            // Build the summary table
            let summaryHtml = `
                <h3 style="text-align: center; margin-bottom: 20px;">RINGKASAN PEMESANAN FASILITAS</h3>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">No.</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">ID Booking</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Tanggal</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Fasilitas</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Durasi</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Total</th>
                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            // Add row data
            let totalAmount = 0;
            visibleRows.forEach((row, index) => {
                const cells = row.querySelectorAll('td');
                const bookingNumber = cells[1].textContent.trim();
                const bookingDate = cells[2].textContent.trim();
                const facility = cells[3].textContent.trim();
                const duration = cells[4].textContent.trim();
                const total = cells[5].textContent.trim();
                const totalValue = parseInt(total.replace(/[^0-9]/g, '')) || 0;
                const status = cells[6].textContent.trim();

                // Only add lunas transactions to total
                if (status.toLowerCase().includes('lunas')) {
                    totalAmount += totalValue;
                }

                const rowBg = index % 2 === 0 ? '' : 'background-color: #f9f9f9;';
                summaryHtml += `
                    <tr style="${rowBg}">
                        <td style="border: 1px solid #ddd; padding: 8px;">${index + 1}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${bookingNumber}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${bookingDate}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${facility}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${duration}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${total}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">${status}</td>
                    </tr>
                `;
            });

            // Close table and add summary
            summaryHtml += `
                    </tbody>
                </table>

                <div style="text-align: right; margin-top: 20px; margin-bottom: 40px;">
                    <p style="margin-bottom: 5px;"><strong>Total Pemesanan:</strong> ${visibleRows.length} pemesanan</p>
                    <p style="margin-bottom: 5px;"><strong>Total Pembayaran Lunas:</strong> Rp ${totalAmount.toLocaleString('id-ID')}</p>
                </div>
            `;

            // Set print content and date
            document.getElementById('receipt-content').innerHTML = summaryHtml;
            document.getElementById('print-date').textContent = new Date().toLocaleString('id-ID', {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });

            // Print the summary
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Ringkasan Pemesanan Fasilitas</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        @media print {
                            body { margin: 0; padding: 20px; }
                        }
                    </style>
                </head>
                <body>
                    ${document.getElementById('print-receipt-template').innerHTML}
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();

            setTimeout(() => {
                printWindow.print();
                if (!printWindow.closed) {
                    printWindow.close();
                }
            }, 500);
        }
    </script>
@endsection
