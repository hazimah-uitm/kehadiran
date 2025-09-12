<!-- resources/views/logs/debug.blade.php -->
@extends('layouts.master')

@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Debug Log</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Debug Log</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<h6 class="mb-0 text-uppercase">Debug Log</h6>
<hr />
<div class="card">
    <div class="card-body">
        @if (count($logs) > 0)
            <pre>{{ implode("\n", $logs) }}</pre>
        @else
            <p>Tiada rekod debug log ditemui.</p>
        @endif
    </div>
</div>
@endsection
