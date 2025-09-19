@extends('layouts.app')

@section('content')
    <div class="wrapper-main">
        <div class="container py-4" style="max-width:980px;">
            <a href="{{ route('public.program.show', $program->id) }}" class="btn btn-info btn-sm mb-3">← Kembali</a>

            {{-- Alert inline (ikut layout public) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-1 text-uppercase">Maklumat Peserta</h5>
                    <div class="small text-muted mb-3">
                        Program: <strong>{{ $program->title }}</strong> ({{ $program->program_code }})
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-7">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th style="width:180px;">Nama</th>
                                    <td>{{ $participant->name }}</td>
                                </tr>
                                {{-- <tr>
              <th>IC/Passport</th>
              <td>{{ $participant->ic_passport }}</td>
            </tr>
            <tr>
              <th>ID Pelajar/Staf</th>
              <td>{{ $participant->student_staff_id ?? '-' }}</td>
            </tr> --}}
                                <tr>
                                    <th>Warganegara</th>
                                    <td>{{ $participant->nationality ?? '-' }}</td>
                                </tr>
                                {{-- <tr>
              <th>No. Telefon</th>
              <td>{{ $participant->phone_no ?? '-' }}</td>
            </tr> --}}
                                <tr>
                                    <th>Institusi</th>
                                    <td>{{ $participant->institution ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Kod Peserta</th>
                                    <td>
                                        @if ($participant->participant_code)
                                            <div class="input-group input-group-sm" style="max-width:260px;">
                                                <input type="text" class="form-control"
                                                    value="{{ $participant->participant_code }}" readonly>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="navigator.clipboard.writeText('{{ $participant->participant_code }}')">
                                                    Salin
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-lg-5">
                            <div class="border rounded p-3 text-center">
                                <div class="mb-2 fw-semibold">Kod QR</div>
                                @if ($participant->qr_path)
                                    <img src="{{ asset('public/storage/' . $participant->qr_path) }}" alt="QR"
                                        style="max-height:180px;">
                                    <div class="mt-2">
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ asset('public/storage/' . $participant->qr_path) }}" download>
                                            Muat Turun QR
                                        </a>
                                    </div>
                                @else
                                    <div class="text-muted small">QR belum dijana.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
