@extends('layouts.landing')

@section('content')
    {{-- Header Section --}}
    <div class="content-header bg-black shadow-sm" style="padding-top: 110px; padding-bottom: 10px;">
        <div class="container bg-white p-4 rounded-lg shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="font-weight-bold text-dark" style="font-size: 1.8rem;">
                        <i class="fas fa-trophy text-primary mr-2"></i> Detail Pengajuan Event
                    </h1>
                    <p class="text-muted mb-0">
                        Informasi lengkap pengajuan event Anda
                    </p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ route('user.history') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content bg-light border-top" style="min-height: 80vh; padding-top: 20px;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    {{-- Status Badge --}}
                    <div class="text-center mb-4">
                        @php
                            $badges = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                            $badgeClass = $badges[$event->status] ?? 'secondary';
                        @endphp
                        <span class="badge badge-{{ $badgeClass }} px-5 py-3 rounded-pill text-uppercase" style="font-size: 1.2rem;">
                            {{ $event->status }}
                        </span>
                    </div>

                    {{-- Detail Card --}}
                    <div class="card border-0 shadow-lg rounded-lg">
                        <div class="card-header bg-gradient-primary text-white py-3">
                            <h5 class="mb-0 font-weight-bold">
                                <i class="fas fa-info-circle mr-2"></i> Informasi Event
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="label-detail">ID Pengajuan</td>
                                        <td class="value-detail font-weight-bold text-primary">
                                            EVT-{{ str_pad($event->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Nama Event</td>
                                        <td class="value-detail font-weight-bold" style="font-size: 1.1rem;">
                                            {{ $event->nama_event }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Nama Panitia</td>
                                        <td class="value-detail">
                                            <i class="fas fa-user-tie text-primary mr-2"></i>
                                            {{ $event->nama_panitia }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Fasilitas</td>
                                        <td class="value-detail">
                                            <i class="{{ $event->fasilitas->icon }} mr-2"></i>
                                            {{ $event->fasilitas->nama_fasilitas }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Tanggal Pelaksanaan</td>
                                        <td class="value-detail">
                                            <i class="far fa-calendar-alt text-primary mr-2"></i>
                                            {{ $event->tgl_mulai->translatedFormat('d F Y') }}
                                            <span class="mx-2">s.d</span>
                                            {{ $event->tgl_selesai->translatedFormat('d F Y') }}
                                            <br>
                                            <small class="text-muted">
                                                ({{ $event->tgl_mulai->diffInDays($event->tgl_selesai) + 1 }} hari)
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail align-top">Deskripsi Event</td>
                                        <td class="value-detail">
                                            <div class="p-3 bg-light rounded">
                                                {{ $event->deskripsi_event }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-detail">Waktu Pengajuan</td>
                                        <td class="value-detail text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $event->created_at->translatedFormat('d F Y, H:i') }} WIB
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- Download Proposal --}}
                            @if($event->file_surat)
                            <hr class="my-4">
                            <div class="text-center">
                                <a href="{{ asset('storage/' . $event->file_surat) }}"
                                   target="_blank"
                                   class="btn btn-primary btn-lg rounded-pill px-5">
                                    <i class="fas fa-download mr-2"></i> Unduh Proposal / Surat Pengajuan
                                </a>
                            </div>
                            @endif

                            {{-- Alasan Penolakan --}}
                            @if($event->status == 'rejected' && $event->alasan_admin)
                            <hr class="my-4">
                            <div class="alert alert-danger mb-0">
                                <h6 class="font-weight-bold mb-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Alasan Penolakan
                                </h6>
                                <p class="mb-0">{{ $event->alasan_admin }}</p>
                            </div>
                            @endif

                            {{-- Info Approved --}}
                            @if($event->status == 'approved')
                            <hr class="my-4">
                            <div class="alert alert-success mb-0">
                                <h6 class="font-weight-bold mb-2">
                                    <i class="fas fa-check-circle mr-2"></i> Pengajuan Disetujui
                                </h6>
                                <p class="mb-0">
                                    Event Anda telah disetujui. Silakan hubungi admin untuk koordinasi lebih lanjut.
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .label-detail {
            font-weight: 600;
            color: #6c757d;
            width: 35%;
            padding: 12px 8px;
        }
        .value-detail {
            font-weight: 500;
            color: #343a40;
            padding: 12px 8px;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
    </style>
@endpush
