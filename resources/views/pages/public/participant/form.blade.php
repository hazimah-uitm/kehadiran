@extends('layouts.app')

@section('content')
    <div class="wrapper-main">
        <div class="container py-4" style="max-width:860px;">
            <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm mb-3">← Kembali</a>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-0 text-uppercase">{{ $str_mode ?? 'PENDAFTARAN PESERTA' }}</h5>
                    <div class="small text-muted mb-3">Program: {{ $program->title }}</div>

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

                        <div class="mt-3 d-flex gap-2">
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
