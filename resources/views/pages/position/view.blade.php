@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Jawatan</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('campus') }}">Senarai Jawatan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Maklumat {{ $position->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('campus.edit', $position->id) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
            </a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Maklumat {{ $position->title }}</h6>
    <hr />

    <!-- Campus Information Table -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama Jawatan</th>
                            <td>{{ $position->title }}</td>
                        </tr>
                        <tr>
                            <th>Gred</th>
                            <td>{{ $position->grade }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $position->publish_status }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Campus Information Table -->
    <!-- End Page Wrapper -->
@endsection
