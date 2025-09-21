@extends('layouts.app')

@section('content')
    <div class="wrapper-main">
        <div class="container py-4">
            <div class="mt-auto d-flex justify-content-between align-items-center mb-3">
                <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm">← Kembali</a>

                <a href="{{ route('participant.public.create', $program->id) }}" class="btn btn-primary btn-sm">
                    Pendaftaran
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                    style="background-color:#03244c;">
                    <i class='bx bx-user fs-5'></i>
                    BUTIRAN PROGRAM
                </div>
                <div class="card-body">
                    <h5 class="mb-0">{{ $program->title }}</h5>
                    <div class="table-responsive small mt-2 mb-2">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th class="fw-normal text-secondary" style="width:90px;">
                                        <i class="bx bx-hash me-1 text-primary"></i> Kod
                                    </th>
                                    <td>{{ $program->program_code }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-normal text-secondary">
                                        <i class="bx bx-calendar me-1 text-info"></i> Tarikh
                                    </th>
                                    <td>
                                        {{ \Carbon\Carbon::parse($program->start_time)->format('d/m/Y H:i') }}
                                        – {{ \Carbon\Carbon::parse($program->end_time)->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="fw-normal text-secondary">
                                        <i class="bx bx-map me-1 text-warning"></i> Lokasi
                                    </th>
                                    <td class="text-break">{{ $program->venue }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if (!empty($program->description))
                        <p class="mb-4">{{ $program->description }}</p>
                    @endif

                    <hr class="my-2" />

                    <h6 class="fw-bold mt-3">Senarai Sesi</h6>
                    @if ($program->sessions->count())
                        <div class="list-group">
                            @foreach ($program->sessions as $session)
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="me-2">
                                        <div class="fw-semibold">{{ $session->title ?? 'Sesi' }}</div>
                                        <div class="small text-muted">
                                            @if ($session->start_time && $session->end_time)
                                                {{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, h:i A') }}
                                                –
                                                {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <a href="{{ route('attendance.public', $session->id) }}" class="btn btn-sm btn-primary">
                Kehadiran
              </a> --}}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border small mt-2">Tiada sesi direkodkan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
