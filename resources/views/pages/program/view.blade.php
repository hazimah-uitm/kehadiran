@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Program</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                <li class="breadcrumb-item active" aria-current="page">Maklumat Program</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('program.edit', $program->id) }}" class="btn btn-primary mt-2 mt-lg-0">
            Kemaskini Maklumat
        </a>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat Program</h6>
<hr />

<!-- Program Info + Ringkasan -->
<div class="row">
    <!-- Jadual Ringkas (kiri) -->
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-borderless align-middle mb-0">
                    <tr>
                        <th style="width:180px;">Nama Program</th>
                        <td>{{ $program->title }}</td>
                    </tr>
                    <tr>
                        <th>Kod Program</th>
                        <td>{{ $program->program_code }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $program->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tarikh Program</th>
                        <td>
                            @if(!empty($program->start_date))
                            {{ \Carbon\Carbon::parse($program->start_date)->format('d/m/Y') }}
                            @endif
                            @if(!empty($program->end_date))
                            &ndash; {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/Y') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tempat / Venue</th>
                        <td>{{ $program->venue ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if ($program->publish_status == 'Aktif')
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Program Info + Ringkasan -->

<!-- Senarai Sesi -->
@if ($program->sessions && $program->sessions->count())
<div class="card shadow-sm">
    <div class="card-body">
        <h6 class="fw-500 mt-1">Senarai Sesi</h6>
        <div class="list-group">
            @foreach ($program->sessions as $session)
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-2">
                    {{-- Tarikh & Masa (dulu) --}}
                    <div class="fw-semibold">
                        @if (!empty($session->start_time))
                        {{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, h:i A') }}
                        @endif
                        @if (!empty($session->end_time))
                        – {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                        @endif
                    </div>

                    {{-- Tajuk Sesi --}}
                    <div>
                        <i class="bx bx-layer me-1 text-success"></i>{{ $session->title ?? 'Sesi' }}
                    </div>

                    {{-- Lokasi --}}
                    @php
                    // Sokong kedua-dua $session->venue atau $session->location
                    $sessionVenue = $session->venue ?? $session->location ?? null;
                    @endphp
                    @if (!empty($sessionVenue))
                    <div class="small text-muted">
                        <i class="bx bx-map me-1 text-warning"></i>{{ $sessionVenue }}
                    </div>
                    @endif
                </div>

                {{-- Butang Kehadiran --}}
                <a href="{{ route('attendance.create.session', ['program' => $program->id, 'session' => $session->id]) }}"
                    class="btn btn-sm btn-success" target="_blank">
                    <i class="bx bx-list-check"></i> Kehadiran
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- End Senarai Sesi -->
@endsection