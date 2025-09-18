@extends('layouts.master')
@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Maklumat Sesi</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('session', $program->id) }}">Senarai Sesi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $session->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('session.edit', [$program->id, $session->id]) }}" class="btn btn-primary">Kemaskini
                Maklumat</a>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase">Maklumat {{ $session->title }}</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th>Nama Sesi</th>
                    <td>{{ $session->title }}</td>
                </tr>
                <tr>
                    <th>Venue</th>
                    <td>{{ $session->venue }}</td>
                </tr>
                <tr>
                    <th>Masa</th>
                    <td>{{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y H:i') }}
                        –
                        {{ \Carbon\Carbon::parse($session->end_time)->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($session->publish_status == 'Aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Tidak Aktif</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
