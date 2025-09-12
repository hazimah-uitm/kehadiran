@extends('layouts.app')

@section('content')
<!--wrapper-->
<div class="wrapper">
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center flex-column flex-md-row mb-4">
                    <img src="{{ asset('public/assets/images/putih.png') }}" class="logo-icon-login" alt="logo icon">
                    <div class="ms-3">
                        <h4 class="logo-text-login mb-0">SISTEM KEHADIRAN</h4>
                        {{-- <h6 class="logo-subtitle-login mb-0">Main Template</h6> --}}
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <div class="border p-4 rounded">
                                <div class="text-center mb-4">
                                    <h3 class="">Log Masuk</h3>
                                </div>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    @if ($errors->count() == 1)
                                    <p class="mb-0">{{ $errors->first() }}</p>
                                    @else
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </div>
                                @endif
                                <div class="form-body">
                                    <form method="POST" action="{{ route('login') }}" class="row g-4">
                                        {{ csrf_field() }}
                                        <div class="col-12">
                                            <label for="staff_id" class="form-label">No. Pekerja</label>
                                            <input type="number" class="form-control" id="staff_id" name="staff_id"
                                                value="{{ old('staff_id') }}" required autocomplete="staff_id"
                                                autofocus>
                                        </div>
                                        <div class="col-12">
                                            <label for="password" class="form-label">Kata Laluan</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input type="password" class="form-control border-end-0" id="password"
                                                    name="password" required autocomplete="current-password">
                                                <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                        class='bx bx-hide'></i></a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="flexSwitchCheckChecked" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexSwitchCheckChecked">Ingat
                                                    saya</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <a href="{{ route('password.request') }}">Lupa kata laluan?</a>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary"><i
                                                        class="bx bxs-lock-open"></i> Log Masuk</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end wrapper-->
@endsection