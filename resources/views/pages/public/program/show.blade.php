@extends('layouts.app')

@section('content')
<div class="wrapper-main">
    <div class="container py-4">
        <div class="mt-auto d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm">← Back</a>

            <a href="{{ route('public.participant.create', $program->id) }}" class="btn btn-primary btn-sm"><i
                    class="bx bx-user-plus"></i>
                Registration
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                style="background-color:#03244c;">
                <i class='bx bx-calendar-event fs-5'></i>
                PROGRAM DETAILS
            </div>
            <div class="card-body">
                <h5 class="mb-0">{{ $program->title }}</h5>
                <div class="table-responsive small mt-2 mb-2">
                    <table class="table table-sm table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <th class="fw-normal text-secondary" style="width:90px;">
                                    <i class="bx bx-hash me-1 text-primary"></i> Program Code
                                </th>
                                <td>{{ $program->program_code }}</td>
                            </tr>
                            <tr>
                                <th class="fw-normal text-secondary">
                                    <i class="bx bx-calendar me-1 text-info"></i> Date
                                </th>
                                <td>
                                    {{ \Carbon\Carbon::parse($program->start_time)->format('d/m/Y') }}
                                    – {{ \Carbon\Carbon::parse($program->end_time)->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="fw-normal text-secondary">
                                    <i class="bx bx-map me-1 text-warning"></i> Venue
                                </th>
                                <td class="text-break">{{ $program->venue }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if (!empty($program->description))
                <p class="mb-4">{{ $program->description }}</p>
                @endif

                @if ($program->sessions->count())
                <hr class="my-2" />
                <h6 class="fw-500 mt-1">Session List</h6>
                <div class="list-group">
                    @foreach ($program->sessions as $session)
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="me-2">
                            {{-- Tarikh & Masa --}}
                            <div class="fw-semibold">
                                @if (!empty($session->start_time) && !empty($session->end_time))
                                {{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                @endif
                            </div>

                            {{-- Tajuk Sesi --}}
                            <div><i class="bx bx-layer me-1 text-success"></i>{{ $session->title ?? 'Session' }}</div>

                            {{-- Lokasi --}}
                            @if (!empty($session->venue))
                            <div class="small text-muted">
                                <i class="bx bx-map me-1 text-warning"></i>{{ $session->venue }}
                            </div>
                            @endif
                        </div>

                        {{-- Butang Kehadiran --}}
                        <!-- <a href="{{ route('attendance.create.session', ['program' => $program->id, 'session' => $session->id]) }}"
                            class="btn btn-sm btn-success" target="_blank">
                            <i class="bx bx-list-check"></i> Attendance Check-in
                        </a> -->
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection