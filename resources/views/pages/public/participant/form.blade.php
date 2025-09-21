@extends('layouts.app')

@section('content')
    <div class="wrapper-main">
        <div class="container py-4" style="max-width:860px;">
            <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm mb-3">← Kembali</a>

            <div class="card shadow-sm border-0">
                <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                    style="background-color:#03244c;">
                    <i class="bx bx-user-plus fs-5"></i>
                    PENDAFTARAN PESERTA
                </div>
                <div class="card-body">

                    {{-- Tajuk program --}}
                    <h5 class="mb-1">{{ $program->title }}</h5>

                    {{-- Tarikh & lokasi --}}
                    <div class="text-muted mb-4">
                        <i class="bx bx-calendar text-info me-1"></i>
                        {{ \Carbon\Carbon::parse($program->start_date)->format('d/m/Y') }}
                        – {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/Y') }}

                        <span class="fw-semibold mx-2">•</span>

                        <i class="bx bx-map text-warning me-1"></i>
                        {{ $program->venue ?? '-' }}
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ $save_route }}" autocomplete="off">
                        {{ csrf_field() }}

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Penuh</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        @foreach ($errors->get('name') as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. IC/Passport</label>
                                <input type="text" name="ic_passport" value="{{ old('ic_passport') }}"
                                    class="form-control {{ $errors->has('ic_passport') ? 'is-invalid' : '' }}">
                                @if ($errors->has('ic_passport'))
                                    <div class="invalid-feedback">
                                        @foreach ($errors->get('ic_passport') as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Staf/Pelajar (UiTM sahaja)</label>
                                <input type="text" name="student_staff_id" value="{{ old('student_staff_id') }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Warganegara</label>
                                <input type="text" name="nationality" value="{{ old('nationality') }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No. Telefon</label>
                                <input type="text" name="phone_no" value="{{ old('phone_no') }}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Institusi / Organisasi</label>
                                <input type="text" name="institution" value="{{ old('institution') }}"
                                    class="form-control">
                            </div>
                        </div>

                        {{-- Butang submit di bawah, align kanan --}}
                        <div class="mt-3 d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Hantar</button>
                        </div>

                        <div class="small text-muted mt-3">
                            Nota: Kod peserta dan QR akan dijana secara automatik selepas pendaftaran berjaya.
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
