@extends('layouts.master')
@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Kehadiran</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    @if (isset($session) && $session)
                        <li class="breadcrumb-item">
                            <a href="{{ route('session', ['program' => $program->id]) }}">Senarai Sesi</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('attendance.index.session', ['program' => $program->id, 'session' => $session->id]) }}">Senarai
                                Kehadiran
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Kehadiran Sesi</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ route('attendance.index.program', $program->id) }}">Senarai Kehadiran
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Kehadiran Program</li>
                    @endif
                </ol>
            </nav>
        </div>
        {{-- (Optional) butang balik ke senarai --}}
        <div class="ms-auto">
            @if (isset($session) && $session)
                <a href="{{ route('session', ['program' => $program->id]) }}"
                    class="btn btn-secondary mt-2 mt-lg-0">Kembali</a>
            @else
                <a href="{{ route('program') }}" class="btn btn-secondary mt-2 mt-lg-0">Kembali</a>
            @endif
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">
        Borang Kehadiran {{ isset($session) && $session ? 'Sesi' : 'Program' }}
    </h6>
    <hr />

    <div class="card">
        <div class="card-body">
            {{-- Konteks dipaparkan supaya urusetia yakin borang yang betul --}}
            <div class="mb-3">
                <div><strong>Program:</strong> {{ $program->title }}</div>
                @if (isset($session) && $session)
                    <div><strong>Sesi:</strong> {{ $session->title }}</div>
                @endif
            </div>

            {{-- Mesej berjaya --}}
            {{-- @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif --}}

            <form method="POST" action="{{ $postRoute }}" id="attendanceForm" autocomplete="off">
                {{ csrf_field() }}

                <div class="mb-3">
                    <label for="participant_code" class="form-label">Kod Peserta</label>
                    <input type="text" id="participant_code" name="participant_code"
                        class="form-control {{ $errors->has('participant_code') ? 'is-invalid' : '' }}"
                        value="{{ old('participant_code') }}" placeholder="Imbas kod atau taip kod peserta"
                        inputmode="text" autofocus>
                    @if ($errors->has('participant_code'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('participant_code') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Hantar</button>
                <button type="button" class="btn btn-outline-secondary ms-1" id="clearBtn">Reset</button>
            </form>

            <div class="mt-3 small text-muted">
                Tip: Kebanyakan barcode/QR scanner berfungsi seperti papan kekunci. Letakkan kursor dalam kotak di atas,
                imbas, dan pastikan scanner hantar kekunci "Enter" untuk hantar borang secara automatik.
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('attendanceForm');
            const input = document.getElementById('participant_code');
            const clearBtn = document.getElementById('clearBtn');

            const MIN_LEN = 3; // elak submit kosong/terlalu pendek

            // Submit bila tekan Enter sahaja (scanner biasanya hantar Enter)
            input && input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const val = (input.value || '').trim();
                    if (val.length >= MIN_LEN) form.submit();
                }
            });

            // Butang reset
            clearBtn && clearBtn.addEventListener('click', function() {
                input.value = '';
                input.focus();
            });

            // Fokus semula lepas reload; kosongkan medan jika success
            window.addEventListener('pageshow', function() {
                if (input) input.focus();
                @if (session('success'))
                    if (input) input.value = '';
                @endif
            });
        })();
    </script>


@endsection
