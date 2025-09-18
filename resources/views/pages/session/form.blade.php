@extends('layouts.master')
@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Sesi</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Sesi</li>
                </ol>
            </nav>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase">{{ $str_mode }} Sesi</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $save_route }}">
                {{ csrf_field() }}
                @if (isset($session) && $session->id)
                    {{ method_field('PUT') }}
                @endif

                {{-- Hidden program_id --}}
                <input type="hidden" name="program_id" value="{{ $program->id }}">

                {{-- Nama Program (readonly) --}}
                <div class="mb-3">
                    <label class="form-label">Nama Program</label>
                    <input type="text" class="form-control" value="{{ $program->title }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Nama Sesi</label>
                    <input type="text" id="title" name="title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        value="{{ old('title', $session->title ?? '') }}">
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="venue" class="form-label">Tempat / Venue</label>
                    <input type="text" id="venue" name="venue"
                        class="form-control {{ $errors->has('venue') ? 'is-invalid' : '' }}"
                        value="{{ old('venue', $session->venue ?? '') }}">
                    @if ($errors->has('venue'))
                        <div class="invalid-feedback">{{ $errors->first('venue') }}</div>
                    @endif
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="start_time" class="form-label">Mula</label>
                        <input type="datetime-local" id="start_time" name="start_time"
                            class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}"
                            value="{{ old('start_time', isset($session->start_time) ? \Carbon\Carbon::parse($session->start_time)->format('Y-m-d\TH:i') : '') }}">
                        @if ($errors->has('start_time'))
                            <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="end_time" class="form-label">Tamat</label>
                        <input type="datetime-local" id="end_time" name="end_time"
                            class="form-control {{ $errors->has('end_time') ? 'is-invalid' : '' }}"
                            value="{{ old('end_time', isset($session->end_time) ? \Carbon\Carbon::parse($session->end_time)->format('Y-m-d\TH:i') : '') }}">
                        @if ($errors->has('end_time'))
                            <div class="invalid-feedback">{{ $errors->first('end_time') }}</div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="publish_status" class="form-label">Status</label>
                    <div class="form-check">
                        <input type="radio" id="aktif" name="publish_status" value="1"
                            {{ ($session->publish_status ?? '') == 'Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="tidak_aktif" name="publish_status" value="0"
                            {{ ($session->publish_status ?? '') == 'Tidak Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tidak_aktif">Tidak Aktif</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>
        </div>
    </div>
@endsection
