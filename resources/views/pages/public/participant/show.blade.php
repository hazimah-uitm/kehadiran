@extends('layouts.app')
@section('head')
<meta name="robots" content="noindex, nofollow">
@endsection
@section('content')
<div class="wrapper-main">
    <div class="container py-4" style="max-width:980px;">
        <a href="{{ route('public.programs'}}" class="btn btn-info btn-sm mb-3">← Kembali</a>

        {{-- Alert inline (ikut layout public) --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                style="background-color:#03244c;">
                <i class='bx bx-user fs-5'></i>
                MAKLUMAT PESERTA
            </div>
            <div class="card-body">
                <h5 class="mb-1">{{ $program->title }}</h5>

                {{-- Tarikh & lokasi --}}
                <div class="text-muted mb-2">
                    <i class="bx bx-calendar text-info me-1"></i>
                    {{ \Carbon\Carbon::parse($program->start_date)->format('d/m/Y') }}
                    – {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/Y') }}

                    <span class="fw-semibold mx-2">•</span>

                    <i class="bx bx-map text-warning me-1"></i>
                    {{ $program->venue ?? '-' }}
                </div>

                <hr class="my-2" />

                <div class="row g-3 mt-2">
                    <div class="col-lg-7">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width:180px;">Nama</th>
                                <td>{{ $participant->name }}</td>
                            </tr>
                            <tr>
                                <th>Warganegara</th>
                                <td>{{ $participant->nationality ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Institusi</th>
                                <td>{{ $participant->institution ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kod Peserta</th>
                                <td>
                                    @if ($participant->participant_code)
                                    <div class="input-group input-group-sm" style="max-width:260px;">
                                        <input type="text" class="form-control"
                                            value="{{ $participant->participant_code }}" readonly>
                                        <button type="button" class="btn btn-secondary"
                                            onclick="navigator.clipboard.writeText('{{ $participant->participant_code }}')">
                                            Salin
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-lg-5">
                        <div class="border rounded p-3 text-center">
                            <div class="mb-2 fw-semibold">Kod QR</div>
                            @if ($participant->qr_path)
                            <img src="{{ asset('public/storage/' . $participant->qr_path) }}" alt="QR"
                                style="max-height:180px;">
                            <div class="mt-2">
                                <a class="btn btn-sm btn-primary"
                                    href="{{ asset('public/storage/' . $participant->qr_path) }}" download>
                                    Muat Turun QR
                                </a>
                            </div>
                            @else
                            <div class="text-muted small">QR belum dijana.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection