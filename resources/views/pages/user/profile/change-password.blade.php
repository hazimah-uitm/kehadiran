@extends('layouts.master')

@section('content')
<div class="page-breadcrumb mb-3">
    <div class="row align-items-center">
        <!-- Breadcrumb Title and Navigation -->
        <div class="col-12 col-md-9 d-flex align-items-center">
            <div class="breadcrumb-title pe-3">Tukar Kata Laluan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('profile.show', $user->id) }}">Profil {{ $user->name }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tukar Kata Laluan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<h6 class="mb-0 text-uppercase">Tukar Kata Laluan {{ $user->name }}</h6>
<hr />

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('profile.change-password', $user->id) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Kata Laluan Semasa</label>
                        <input type="password" class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}" id="current_password" name="current_password" required>
                        @if ($errors->has('current_password'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('current_password') as $error)
                            {{ $error }}
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Laluan Baru</label>
                        <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" required>
                        @if ($errors->has('password'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('password') as $error)
                            {{ $error }}
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Sahkan Kata Laluan Baru</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Kemaskini Kata Laluan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection