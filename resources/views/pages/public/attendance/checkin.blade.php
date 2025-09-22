@extends('layouts.app')

@section('content')
<div class="wrapper-main">
    <div class="container py-4" style="max-width:860px;">

        {{-- Back button (public listing) --}}
        <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm mb-3">← Back</a>

        <div class="row g-4">
            <div class="col-lg-12 order-2 order-lg-1">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                        style="background-color:#03244c;">
                        <i class='bx bx-user-check fs-5'></i>
                        ATTENDANCE CHECK-IN
                    </div>
                    <div class="card-body">

                        <h5 class="mb-0">{{ $program->title }}</h5>

                        <div class="table-responsive small mt-2">
                            <table class="table table-sm table-borderless align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="fw-normal text-secondary" style="width:90px;">
                                            <i class="bx bx-hash me-1 text-primary"></i> Program code
                                        </th>
                                        <td>{{ $program->program_code }}</td>
                                    </tr>
                                    @if ($session)
                                    <tr>
                                        <th class="fw-normal text-secondary">
                                            <i class="bx bx-layer me-1 text-success"></i> Session
                                        </th>
                                        <td class="text-break">{{ $session->title }}</td>
                                    </tr>
                                    <tr>
                                        <th class="fw-normal text-secondary">
                                            <i class="bx bx-calendar me-1 text-info"></i> Date
                                        </th>
                                        <td>
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y H:i') }}
                                            – {{ \Carbon\Carbon::parse($session->end_time)->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="fw-normal text-secondary">
                                            <i class="bx bx-map me-1 text-warning"></i> Venue
                                        </th>
                                        <td class="text-break">{{ $session->venue }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-2" />

                        {{-- Borang kehadiran (by program / by sesi) --}}
                        <form method="POST" action="{{ $postRoute }}" id="attendanceForm" autocomplete="off">
                            {{ csrf_field() }}

                            <div class="mb-3">
                                <label for="participant_code" class="form-label">Participant Code</label>
                                <input type="text" id="participant_code" name="participant_code"
                                    class="form-control {{ $errors->has('participant_code') ? 'is-invalid' : '' }}"
                                    value="{{ old('participant_code') }}" placeholder="Scan or Enter Participant Code" autofocus>
                                @if (session('error'))
                                <div class="invalid-feedback d-block mt-2">
                                    <div class="p-2 rounded small bg-danger-subtle border border-danger">
                                        {!! session('error') !!}
                                    </div>
                                </div>
                                @endif

                                {{-- Mesej berjaya / gagal --}}
                                @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                    {!! session('success') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif

                                @if (session('info'))
                                <div class="alert alert-info alert-dismissible fade show mt-2" role="alert">
                                    {!! session('info') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                @endif
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" id="clearBtn" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                        {{-- <div class="mt-3 small text-muted">
                        Tip: Letak kursor dalam kotak di atas, imbas, dan pastikan scanner hantar kekunci “Enter” untuk
                        auto-submit.
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        {{-- Register --}}
                        <div class="d-flex align-items-center mb-2">
                            <div class="fw-semibold">Don’t have a code yet?</div>
                        </div>
                        <div class="text-center py-2">
                            <img src="{{ asset('public/assets/images/daftar.png') }}" alt="Register QR"
                                class="img-fluid" style="max-width:160px;">
                        </div>
                        <div class="small text-center text-muted mb-2">
                            Scan to register & get your Participant Code
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="fw-semibold">Forgot your code?</div>
                        </div>
                        <div class="text-center py-2">
                            <img src="{{ asset('public/assets/images/semak.png') }}" alt="Check Code QR"
                                class="img-fluid" style="max-width:160px;">
                        </div>
                        <div class="small text-center text-muted mb-2">
                            Scan to retrieve your Participant Code
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('attendanceForm');
        const input = document.getElementById('participant_code');
        const clearBtn = document.getElementById('clearBtn');
        const MIN_LEN = 3;

        // Submit bila tekan Enter
        input && input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = (input.value || '').trim();
                if (val.length >= MIN_LEN) form.submit();
            }
        });

        // Reset
        clearBtn && clearBtn.addEventListener('click', function() {
            if (input) {
                input.value = '';
                input.classList.remove('is-invalid');
                // buang mesej error kalau ada
                const feedback = input.closest('.mb-3')?.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
                input.focus();
            }
        });

        // Fokus semula lepas reload; kosongkan input jika success
        window.addEventListener('pageshow', function() {
            if (input) input.focus();
            @if(session('success'))
            if (input) input.value = '';
            @endif
        });

        // Auto-dismiss alert selepas 2s
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 2000);
    })();
</script>

@endsection