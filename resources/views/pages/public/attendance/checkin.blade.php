@extends('layouts.app')

@section('content')
    <div class="container py-4" style="max-width:720px;">
        <a href="{{ route('public.programs') }}" class="btn btn-link px-0 mb-3">← Kembali ke Senarai</a>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                {{-- Konteks supaya pengguna yakin --}}
                <h4 class="mb-0">{{ $session->program->title }}</h4>
                <div class="small text-muted mb-2">Kod: {{ $session->program->program_code }}</div>

                {{-- Mesej berjaya (kalau ada) --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('attendance.public.store', $session->id) }}" id="attendanceForm"
                    autocomplete="off">
                    {{ csrf_field() }}

                    <div class="mb-3">
                        <label for="participant_code" class="form-label">Kod Peserta</label>
                        <input type="text" id="participant_code" name="participant_code"
                            class="form-control {{ $errors->has('participant_code') ? 'is-invalid' : '' }}"
                            value="{{ old('participant_code') }}" placeholder="Imbas kod atau taip kod peserta"
                            autofocus>
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
                    Tip: Letak kursor dalam kotak di atas, imbas, dan pastikan scanner hantar kekunci “Enter” atau biar
                    sistem auto-submit.
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('attendanceForm');
            const input = document.getElementById('participant_code');
            const clearBtn = document.getElementById('clearBtn');

            // Submit bila tekan Enter
            input && input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const val = (input.value || '').trim();
                    if (val.length >= 3) form.submit();
                }
            });

            // Reset button
            clearBtn && clearBtn.addEventListener('click', function() {
                input.value = '';
                input.focus();
            });

            // Fokus semula lepas reload
            window.addEventListener('pageshow', function() {
                if (input) input.focus();
                @if (session('success'))
                    if (input) input.value = '';
                @endif
            });
        })();
    </script>

@endsection
