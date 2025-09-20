@extends('layouts.master')

@section('content')
    <div class="container-fluid mb-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="mb-0">Senarai Program</h2>

            <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center">
                <label class="me-2 mb-0 small text-muted">Papar</label>
                @php
                    $currentPerPage = (int) request('perPage', $perPage ?? 12);
                @endphp
                <select name="perPage" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ([6, 12, 24, 48] as $n)
                        <option value="{{ $n }}" {{ $currentPerPage === $n ? 'selected' : '' }}>
                            {{ $n }}</option>
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
                                <div><i class="bx bx-calendar me-1"></i>
                                    {{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}
                                    –
                                    {{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}
                                </div>
                                <div><i class="bx bx-map me-1"></i>{{ $program->venue }}</div>
                            </div>

                            @if (!empty($program->description))
                                <p class="text-muted small mb-3"
                                    style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $program->description }}
                                </p>
                            @endif

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                {{-- Untuk admin, tunjukkan butang pengurusan --}}
                                <a href="{{ route('session', ['program' => $program->id]) }}"
                                    class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                    title="Urus Sesi">
                                    <i class="bx bx-calendar"></i> Urus Sesi
                                </a>

                                <div class="btn-group">
                                    <a href="{{ route('participant', ['program' => $program->id]) }}"
                                        class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        title="Peserta">
                                        <i class="bx bx-group"></i> Peserta
                                    </a>

                                    <a href="{{ route('program.edit', $program->id) }}" class="btn btn-warning btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                        <i class="bx bx-edit"></i> Edit
                                    </a>

                                    <a href="{{ route('program.show', $program->id) }}" class="btn btn-info btn-sm"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Papar">
                                        <i class="bx bx-show"></i> Papar
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
@endsection
