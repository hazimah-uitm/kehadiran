@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="mb-0">Senarai Program</h2>

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
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1 text-wrap" title="{{ $program->title }}">{{ $program->title }}</h5>
                            <div class="small text-muted mb-2">
                                Kod: <span class="fw-semibold">{{ $program->program_code }}</span>
                            </div>

                            <div class="mb-2 small">
                                <div><i
                                        class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}
                                    – {{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}</div>
                                <div><i class="bx bx-map me-1"></i>{{ $program->venue }}</div>
                            </div>

                            @if (!empty($program->description))
                                <p class="text-muted small mb-3"
                                    style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $program->description }}
                                </p>
                            @endif

                            <div class="mt-auto">
                                @if ($program->sessions->count())
                                    <div class="small fw-semibold mb-2">Sesi:</div>
                                    <div class="list-group list-group-flush">
                                        @foreach ($program->sessions as $session)
                                            <div
                                                class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                                <div class="me-2">
                                                    <div class="fw-semibold">{{ $session->title ?? 'Sesi' }}</div>
                                                    <div class="small text-muted">
                                                        @if (!empty($session->start_time) && !empty($session->end_time))
                                                            {{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, h:i A') }}
                                                            –
                                                            {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <a href="{{ route('attendance.public', $session->id) }}"
                                                    class="btn btn-sm btn-primary">Kehadiran</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-light border small m-0">Tiada sesi buat masa ini.</div>
                                @endif

                                <a href="{{ route('public.program.detail', $program->id) }}" class="btn btn-link px-0 mt-2">
                                    Butiran Program →
                                </a>
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
@endsection
