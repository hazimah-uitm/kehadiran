@extends('layouts.app')

@section('content')
<div class="wrapper-main">
    <div class="container py-4" style="max-width:860px;">
        <a href="{{ route('public.programs') }}" class="btn btn-info btn-sm mb-3">← Back</a>

        <div class="card shadow-sm border-0">
            <div class="card-header text-center text-white h6 text-uppercase d-flex justify-content-center align-items-center gap-2"
                style="background-color:#03244c;">
                <i class="bx bx-user-plus fs-5"></i>
                REGISTRATION
            </div>
            <div class="card-body">

                {{-- Tajuk program --}}
                <h5 class="mb-1">{{ $program->title }}</h5>

                {{-- Tarikh & lokasi --}}
                <div class="small text-muted mb-2">
                    <i class="bx bx-calendar text-info me-1"></i>
                    {{ \Carbon\Carbon::parse($program->start_date)->format('d/m/Y') }}
                    – {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/Y') }}

                    <span class="fw-semibold mx-2"></span>

                    <i class="bx bx-map text-warning me-1"></i>
                    {{ $program->venue ?? '-' }}
                </div>

                <hr class="my-2" />

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

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
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
                            <label class="form-label">IC/Passport No.</label>
                            <input type="text" name="ic_passport" value="{{ old('ic_passport') }}"
                                class="form-control {{ $errors->has('ic_passport') ? 'is-invalid' : '' }}">
                            <!-- <div class="form-text">Please enter without dashes (-) or spaces (e.g., 901231011234).</div> -->
                            @if ($errors->has('ic_passport'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('ic_passport') as $error)
                                 {!! $error !!}
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Staff/Student ID (UiTM only)</label>
                            <input type="text" id="student_staff_id" name="student_staff_id"
                                class="form-control {{ $errors->has('student_staff_id') ? 'is-invalid' : '' }}"
                                value="{{ old('student_staff_id', $participant->student_staff_id ?? '') }}">
                            @if ($errors->has('student_staff_id'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('student_staff_id') as $error)
                                {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label for="nationality" class="form-label">Nationality</label>
                            <select id="nationality" name="nationality"
                                class="form-select {{ $errors->has('nationality') ? 'is-invalid' : '' }}" placeholder="Pilih Negara">
                                <option value="" selected></option>
                                @php
                                $asiaCountries = [
                                'Afghanistan','Armenia','Azerbaijan','Bahrain','Bangladesh','Bhutan','Brunei',
                                'Cambodia','China','Cyprus','East Timor','Georgia','India','Indonesia','Iran','Iraq','Israel',
                                'Japan','Jordan','Kazakhstan','Kuwait','Kyrgyzstan','Laos','Lebanon','Malaysia','Maldives',
                                'Mongolia','Myanmar','Nepal','North Korea','Oman','Pakistan','Palestine','Philippines','Qatar',
                                'Saudi Arabia','Singapore','South Korea','Sri Lanka','Syria','Taiwan','Tajikistan','Thailand',
                                'Turkey','Turkmenistan','United Arab Emirates','Uzbekistan','Vietnam','Yemen'
                                ];
                                @endphp
                                @foreach ($asiaCountries as $country)
                                <option value="{{ $country }}"
                                    {{ old('nationality', $participant->nationality ?? '') === $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('nationality'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('nationality') as $error)
                                {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Phone No.</label>
                            <input type="text" id="phone_no" name="phone_no"
                                class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}"
                                value="{{ old('phone_no', $participant->phone_no ?? '') }}">
                            @if ($errors->has('phone_no'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('phone_no') as $error)
                                {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Institution / Organization</label>
                            <input type="text" id="institution" name="institution"
                                class="form-control {{ $errors->has('institution') ? 'is-invalid' : '' }}"
                                value="{{ old('institution', $participant->institution ?? '') }}">
                            @if ($errors->has('institution'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('institution') as $error)
                                {{ $error }}
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Alert makluman simpan QR --}}
                    <div class="alert alert-info d-flex align-items-start align-items-center gap-2 p-2 small mt-3 mb-3"
                        role="alert">
                        <i class="bx bx-info-circle fs-6 mt-1"></i>
                        <div>
                            The Participant Code and QR Code will be generated automatically after successful registration.
                            Please <strong>save your participant code and download your QR Code</strong>, as it will be required to check in for attendance during the program.
                        </div>
                    </div>

                    {{-- Butang submit di bawah, align kanan --}}
                    <div class="mt-3 d-flex justify-content-between">
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect('#nationality', {
            plugins: ['clear_button'],
            maxOptions: 1000,
            placeholder: 'Select nationality',
        });
    });
</script>

@endsection