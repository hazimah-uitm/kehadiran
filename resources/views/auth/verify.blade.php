@extends('layouts.app')

@section('content')
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="text-center">
                    <div class="d-flex align-items-center justify-content-center flex-column flex-md-row mb-4">
                        <img src="{{ asset('public/assets/images/putih.png') }}" class="logo-icon-login" alt="logo icon">
                        <div class="ms-3">
                            <h4 class="logo-text-login mb-0">ATTENDANCE SYSTEM</h4>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 justify-content-center">
                    <div class="col mx-auto">
                        <div class="card shadow-none">
                            <div class="card-body">
                                <div class="border p-2 rounded">
                                    <div class="text-center mb-4">
                                        <h3>{{ __('Please Verify Your Email Address') }}</h3>
                                    </div>
                                    @if (session('resent'))
                                        <div class="alert alert-success text-center" role="alert">
                                            {{ __('A new verification link has been sent to your email.') }}
                                        </div>
                                    @endif

                                    <div class="form-body text-center">
                                        <form method="GET" action="{{ route('verification.resend') }}">
                                            {{ csrf_field() }}
                                            <p class="mb-2">
                                                {{ __('Please check your email for the verification link. If you did not receive it,') }}</p>
                                            <button type="submit"
                                                class="btn btn-primary">{{ __('Click here to request a new link.') }}</button>
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
