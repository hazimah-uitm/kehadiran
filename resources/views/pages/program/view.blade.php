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
                    <li class="breadcrumb-item active" aria-current="page">Maklumat Program</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('program.edit', $program->id) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
            </a>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Maklumat Program</h6>
    <hr />

    <!-- Program Information Table -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama Program</th>
                            <td>{{ $program->title }}</td>
                        </tr>
                        <tr>
                            <th>Kod Program</th>
                            <td>{{ $program->program_code }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $program->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tarikh</th>
                            <td>
                                {{ \Carbon\Carbon::parse($program->start_date)->format('d/m/Y') }}
                                &ndash;
                                {{ \Carbon\Carbon::parse($program->end_date)->format('d/m/Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Tempat / Venue</th>
                            <td>{{ $program->venue }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($program->publish_status == 'Aktif')
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Program Information Table -->
@endsection
