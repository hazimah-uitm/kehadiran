@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan PTJ</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ptj') }}">Senarai PTJ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} PTJ</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">{{ $str_mode }} PTJ</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $save_route }}">
                {{ csrf_field() }}

                <div class="mb-3">
                    <label for="name" class="form-label">Nama PTJ</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                        name="name" value="{{ old('name') ?? ($ptj->name ?? '') }}">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('name') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis PTJ</label>
                    <div class="form-check">
                        <input type="radio" id="pentadbiran" name="type" value="Pentadbiran"
                            {{ old('type', $ptj->type ?? '') == 'Pentadbiran' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="pentadbiran">Pentadbiran</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="akademik" name="type" value="Akademik"
                            {{ old('type', $ptj->type ?? '') == 'Akademik' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="akademik">Akademik</label>
                    </div>
                    @if ($errors->has('type'))
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="publish_status" class="form-label">Status</label>
                    <div class="form-check">
                        <input type="radio" id="aktif" name="publish_status" value="1"
                            {{ ($ptj->publish_status ?? '') == 'Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="tidak_aktif" name="publish_status" value="0"
                            {{ ($ptj->publish_status ?? '') == 'Tidak Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tidak_aktif">Tidak Aktif</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>
        </div>
    </div>
    <!-- End Page Wrapper -->
@endsection
