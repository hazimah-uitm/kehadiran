@extends('layouts.app')

@section('content')
<div class="wrapper-main">
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="fw-500 mb-3 mb-md-0 d-flex align-items-center flex-wrap" style="font-size: 1.4rem;">
                SENARAI PROGRAM
            </h2>

            <form method="GET" action="{{ route('public.programs') }}" class="d-flex align-items-center">
                <label class="me-2 mb-0 small text-muted">Papar</label>
                <select name="perPage" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ([6, 12, 24, 48] as $n)
                    <option value="{{ $n }}"
                        {{ (int) request('perPage', $perPage ?? 12) === $n ? 'selected' : '' }}>{{ $n }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="row g-3">
            @forelse($programs as $program)
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1 text-wrap" title="{{ $program->title }}">{{ $program->title }}
                        </h5>

                        <div class="mb-2 small">
                            <div><i
                                    class="bx bx-calendar text-info me-1"></i>{{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}
                                – {{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}</div>
                            <div><i class="bx bx-map text-warning me-1"></i>{{ $program->venue }}</div>
                        </div>

                        @if (!empty($program->description))
                        <p class="text-muted small mb-3"
                            style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $program->description }}
                        </p>
                        @endif

                        <!-- @if ($program->sessions->count())
                        <hr class="my-2" />
                        <h6 class="fw-500 mt-1">Senarai Sesi</h6>
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
                                    <div><i class="bx bx-layer me-1 text-success"></i>{{ $session->title ?? 'Sesi' }}</div>

                                    {{-- Lokasi --}}
                                    @if (!empty($session->venue))
                                    <div class="small text-muted">
                                        <i class="bx bx-map me-1 text-warning"></i>{{ $session->venue }}
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
                        @endif -->

                        <div class="mt-auto row g-2">
                            <div class="col-12 col-sm">
                                <a href="{{ route('public.participant.create', $program->id) }}"
                                    class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center px-2 py-1 w-100">
                                    <i class="bx bx-user-plus me-1"></i>Pendaftaran
                                </a>
                            </div>

                            <div class="col-12 col-sm">
                                <a href="{{ route('public.participant.check', $program->id) }}"
                                    class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center px-2 py-1 w-100">
                                    <i class="bx bx-grid-alt me-1"></i>Jana Kod QR
                                </a>
                            </div>

                            <div class="col-12 col-sm">
                                <a href="{{ route('public.program.show', $program->id) }}"
                                    class="btn btn-info btn-sm d-inline-flex align-items-center justify-content-center px-2 py-1 w-100">
                                    <span class="text-nowrap">Butiran Program →</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-light border">Tiada program ditemui.</div>
            </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $programs->appends(['perPage' => request('perPage', $perPage ?? 12)])->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection