@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Peserta</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('participant', ['program' => $program->id]) }}">Senarai
                            Peserta</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Peserta</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">{{ $str_mode }} Peserta</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $save_route }}">
                {{ csrf_field() }}
                @if (isset($participant) && $participant->id)
                    {{ method_field('PUT') }}
                @endif

                <input type="hidden" name="program_id" value="{{ $program->id }}">

                <div class="mb-3">
                    <label class="form-label">Nama Program</label>
                    <input type="text" class="form-control" value="{{ $program->title }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" id="name" name="name"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        value="{{ old('name', $participant->name ?? '') }}">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('name') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="ic_passport" class="form-label">No. IC / Passport</label>
                        <input type="text" id="ic_passport" name="ic_passport"
                            class="form-control {{ $errors->has('ic_passport') ? 'is-invalid' : '' }}"
                            value="{{ old('ic_passport', $participant->ic_passport ?? '') }}">
                        @if ($errors->has('ic_passport'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('ic_passport') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="student_staff_id" class="form-label">ID Pelajar/Staf (UiTM sahaja)</label>
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
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="nationality" class="form-label">Warganegara</label>
                        <input type="text" id="nationality" name="nationality"
                            class="form-control {{ $errors->has('nationality') ? 'is-invalid' : '' }}"
                            value="{{ old('nationality', $participant->nationality ?? '') }}">
                        @if ($errors->has('nationality'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('nationality') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="phone_no" class="form-label">No. Telefon</label>
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
                </div>

                <div class="mb-3">
                    <label for="institution" class="form-label">Institusi / Organisasi</label>
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

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>
        </div>
    </div>
@endsection
