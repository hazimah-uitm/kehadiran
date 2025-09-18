@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Program</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Program</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">{{ $str_mode }} Program</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $save_route }}">
                {{ csrf_field() }}

                {{-- Nama Program --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Nama Program</label>
                    <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        id="title" name="title" value="{{ old('title') ?? ($program->title ?? '') }}">
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('title') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Kod Program --}}
                <div class="mb-3">
                    <label for="program_code" class="form-label">Kod Program</label>
                    <input type="text" class="form-control {{ $errors->has('program_code') ? 'is-invalid' : '' }}"
                        id="program_code" name="program_code" value="{{ old('program_code') ?? ($program->program_code ?? '') }}">
                    @if ($errors->has('program_code'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('program_code') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Keterangan --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Keterangan</label>
                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" id="description" name="description"
                        rows="3">{{ old('description') ?? ($program->description ?? '') }}</textarea>
                    @if ($errors->has('description'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('description') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Tarikh Mula & Tamat --}}
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Tarikh Mula</label>
                        <input type="date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}"
                            id="start_date" name="start_date"
                            value="{{ old('start_date') ?? ($program->start_date ?? '') }}">
                        @if ($errors->has('start_date'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('start_date') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">Tarikh Tamat</label>
                        <input type="date" class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}"
                            id="end_date" name="end_date" value="{{ old('end_date') ?? ($program->end_date ?? '') }}">
                        @if ($errors->has('end_date'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('end_date') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Venue --}}
                <div class="mb-3">
                    <label for="venue" class="form-label">Lokasi</label>
                    <input type="text" class="form-control {{ $errors->has('venue') ? 'is-invalid' : '' }}"
                        id="venue" name="venue" value="{{ old('venue') ?? ($program->venue ?? '') }}">
                    @if ($errors->has('venue'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('venue') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="publish_status" class="form-label">Status</label>
                    <div class="form-check">
                        <input type="radio" id="aktif" name="publish_status" value="1"
                            {{ ($program->publish_status ?? '') == 'Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="tidak_aktif" name="publish_status" value="0"
                            {{ ($program->publish_status ?? '') == 'Tidak Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tidak_aktif">Tidak Aktif</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>
        </div>
    </div>
    <!-- End Page Wrapper -->
@endsection
