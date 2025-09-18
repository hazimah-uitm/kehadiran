@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Maklumat Peserta</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('participant', ['program' => $program->id]) }}">Senarai
                            Peserta</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $participant->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('participant.edit', ['program' => $program->id, 'participant' => $participant->id]) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">Maklumat {{ $participant->name }}</h6>
    <hr />

    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td>{{ $participant->name }}</td>
                        </tr>
                        <tr>
                            <th>IC/Passport</th>
                            <td>{{ $participant->ic_passport }}</td>
                        </tr>
                        <tr>
                            <th>ID Pelajar/Staf</th>
                            <td>{{ $participant->student_staff_id ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Warganegara</th>
                            <td>{{ $participant->nationality ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. Telefon</th>
                            <td>{{ $participant->phone_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Institusi</th>
                            <td>{{ $participant->institution ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
