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
                    <li class="breadcrumb-item active" aria-current="page">Maklumat {{ $ptj->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('ptj.edit', $ptj->id) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
            </a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Maklumat {{ $ptj->name }}</h6>
    <hr />

    <!-- ptj Information Table -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama PTJ</th>
                            <td>{{ $ptj->name }}</td>
                        </tr>
                        <tr>
                            <th>Jenis PTJ</th>
                            <td>{{ $ptj->type }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $ptj->publish_status }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End ptj Information Table -->
    <!-- End Page Wrapper -->
@endsection
