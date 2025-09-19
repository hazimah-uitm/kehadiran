@extends('layouts.app')

@section('content')
<div class="container py-4">
  <a href="{{ route('public.programs') }}" class="btn btn-link px-0 mb-3">← Kembali ke Senarai</a>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h3 class="card-title mb-1">{{ $program->title }}</h3>
      <div class="small text-muted mb-3">
        Kod: <span class="fw-semibold">{{ $program->program_code }}</span>
      </div>

      <div class="mb-3 small">
        <div><i class="bx bx-calendar me-1"></i>
          {{ \Carbon\Carbon::parse($program->start_date)->format('d M Y') }}
          –
          {{ \Carbon\Carbon::parse($program->end_date)->format('d M Y') }}
        </div>
        <div><i class="bx bx-map me-1"></i>{{ $program->venue }}</div>
      </div>

      @if(!empty($program->description))
        <p class="mb-4">{{ $program->description }}</p>
      @endif

      <h6 class="fw-bold">Senarai Sesi</h6>
      @if($program->sessions->count())
        <div class="list-group">
          @foreach($program->sessions as $session)
            <div class="list-group-item d-flex justify-content-between align-items-start">
              <div class="me-2">
                <div class="fw-semibold">{{ $session->title ?? 'Sesi' }}</div>
                <div class="small text-muted">
                  @if($session->start_time && $session->end_time)
                    {{ \Carbon\Carbon::parse($session->start_time)->format('d M Y, h:i A') }}
                    –
                    {{ \Carbon\Carbon::parse($session->end_time)->format('h:i A') }}
                  @endif
                </div>
              </div>
              <a href="{{ route('attendance.public', $session->id) }}" class="btn btn-sm btn-primary">
                Kehadiran
              </a>
            </div>
          @endforeach
        </div>
      @else
        <div class="alert alert-light border small mt-2">Tiada sesi direkodkan.</div>
      @endif
    </div>
  </div>
</div>
@endsection
