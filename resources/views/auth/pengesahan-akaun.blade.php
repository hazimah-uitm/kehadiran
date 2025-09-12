@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="text-center">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-md-row mb-4">
                        <img src="{{ asset('public/assets/images/putih.png') }}" class="logo-icon-login" alt="logo icon">
                        <div class="ms-3">
                            <h4 class="logo-text-login mb-0">SISTEM KEHADIRAN</h4>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="border p-4 rounded">

                                    <div class="text-center mb-3">
                                        <h3 class="">Pengesahan Akaun</h3>
                                        <p class="text-muted">
                                            @if (!isset($user))
                                                Sila semak No. Pekerja anda.
                                            @else
                                                Sila lengkapkan <strong>Emel UiTM</strong> dan <strong>Kata Laluan</strong> anda untuk
                                                menerima pautan pengesahan.
                                            @endif
                                        </p>
                                    </div>

                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('firsttime.handle') }}">
                                        {{ csrf_field() }}

                                        <div class="mb-1">
                                            <label>No. Pekerja</label>
                                            <input type="text" name="staff_id" class="form-control"
                                                value="{{ old('staff_id', $user->staff_id ?? '') }}" required
                                                {{ isset($user) ? 'readonly' : '' }}>
                                        </div>

                                        @if (isset($user))
                                            <div class="mb-1">
                                                <label>Alamat Emel UiTM</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email') }}" required>
                                            </div>


                                            <div class="row mb-1">
                                                <div class="col-6">
                                                    <label for="password" class="form-label">Kata Laluan</label>
                                                    <input type="password"
                                                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                        id="password" name="password" required>
                                                    @if ($errors->has('password'))
                                                        <div class="invalid-feedback">
                                                            @foreach ($errors->get('password') as $error)
                                                                {{ $error }}
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="col-6">
                                                    <label for="password-confirm" class="form-label">Sahkan Kata
                                                        Laluan</label>
                                                    <input type="password" class="form-control" id="password-confirm"
                                                        name="password_confirmation" required>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row mb-1">
                                                <div class="col-6">
                                                    <label>Nama</label>
                                                    <input type="text" class="form-control" value="{{ $user->name }}"
                                                        readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label>PTJ</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $user->ptj->name ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <label>Jawatan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $user->position->title ?? '-' }}" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label>Kampus</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ $user->campus->name ?? '-' }}" readonly>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="mt-3 d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                {{ isset($user) ? 'Hantar Pautan Pengesahan' : 'Semak' }}
                                            </button>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <a href="{{ route('login') }}">Kembali ke Log Masuk</a>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
