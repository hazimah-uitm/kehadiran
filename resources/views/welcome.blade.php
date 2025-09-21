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

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="{{ route('participant.public.create', $program->id) }}"
                                            class="btn btn-primary btn-sm"><i class="bx bx-user-plus"></i>
                                            Pendaftaran
                                        </a>
                                        <a href="{{ route('public.participant.check', $program->id) }}"
                                            class="btn btn-warning btn-sm"><i class="bx bx-grid-alt"></i>
                                            Jana Kod QR
                                        </a>
                                    </div>

                                    <a href="{{ route('public.program.show', $program->id) }}" class="btn btn-info btn-sm">
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
    </div>
@endsection
