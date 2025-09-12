@extends('layouts.app')

@section('content')
<div class="wrapper">
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="text-center">
                <div class="d-flex align-items-center justify-content-center flex-column flex-md-row mb-4">
                    <img src="{{ asset('public/assets/images/putih.png') }}" class="logo-icon-login" alt="logo icon">
                    <div class="ms-3">
                        <h4 class="logo-text-login mb-0">COLMAS</h4>
                        <h6 class="logo-subtitle-login mb-0">Computer Lab Management System</h6>
                    </div>
                </div>
            </div>
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 justify-content-center">
                <div class="col mx-auto">
                    <div class="card shadow-none">
                        <div class="card-body">
                            <div class="border p-2 rounded">
                                <div class="text-center mb-4">
                                    <h3>{{ __('Sila Sahkan Alamat Emel Anda') }}</h3>
                                </div>
                                @if (session('resent'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ __('Pautan pengesahan baru telah dihantar ke emel anda.') }}
                                </div>
                                @endif

                                <div class="form-body text-center">
                                    <form method="GET" action="{{ route('verification.resend') }}">
                                        {{ csrf_field() }}
                                        <p class="mb-2">{{ __('Sila periksa emel untuk pautan pengesahan. Jika tiada,') }}</p>
                                        <button type="submit" class="btn btn-primary">{{ __('Klik di sini untuk pautan baru') }}</button>
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
@endsection