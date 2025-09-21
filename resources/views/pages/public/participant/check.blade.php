@extends('layouts.app')

@section('content')
<div class="wrapper-main">
    <div class="container py-4" style="max-width:680px;">
        <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm mb-3">← Kembali</a>

        <div class="card shadow-sm border-0">
            <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                style="background-color:#03244c;">
                <i class="bx bx-user-plus fs-5"></i>
                SEMAK KOD QR
            </div>
            <div class="card-body">
                {{-- Tajuk program --}}
                <h5 class="mb-1">{{ $program->title }}</h5>

                {{-- Tarikh & lokasi --}}
                <div class="text-muted mb-3">
                    <i class="bx bx-calendar text-info me-1"></i>
                    {{ \Carbon\Carbon::parse($program->start_date)->format('d/m/Y') }}
                    – {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/Y') }}

                    <span class="fw-semibold mx-2">•</span>

                    <i class="bx bx-map text-warning me-1"></i>
                    {{ $program->venue ?? '-' }}
                </div>

                <hr class="my-2" />

                <form method="POST" action="{{ route('public.participant.check.submit', $program->id) }}" class="row g-3">
                    {{ csrf_field() }}
                    <div class="col-12">
                        <label class="form-label">IC / Passport</label>
                        <input type="text" name="ic_passport" value="{{ old('ic_passport') }}" class="form-control {{ $errors->has('ic_passport') ? 'is-invalid' : '' }}" required>
                        @if ($errors->has('ic_passport'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('ic_passport') as $error)
                            {{ $error }}
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 d-flex justify-content-between">
                        <button type="button" id="btnReset" class="btn btn-outline-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Semak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('btnReset');
        if (!btn) return;
        btn.addEventListener('click', function() {
            var form = this.closest('form');
            if (!form) return;
            form.reset(); // reset standard
            // kosongkan nilai supaya tak kembali ke "value" asal (old)
            var ic = form.querySelector('input[name="ic_passport"]');
            if (ic) ic.value = '';
            // buang state error Bootstrap
            form.querySelectorAll('.is-invalid').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            form.querySelectorAll('.invalid-feedback').forEach(function(el) {
                el.remove();
            });
        });
    });
</script>
@endsection