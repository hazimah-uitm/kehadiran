@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Pengguna</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('user') }}">Senarai Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">Maklumat {{ ucfirst($user->name) }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('user.edit', $user->id) }}">
            <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
        </a>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat {{ ucfirst($user->name) }}</h6>
<hr />

<!-- Campus Information Table -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Nama Penuh</th>
                        <td>{{ ucfirst($user->name) }}</td>
                    </tr>
                    <tr>
                        <th>ID Pekerja</th>
                        <td>{{ $user->staff_id }}</td>
                    </tr>
                    <tr>
                        <th>Jawatan</th>
                        <td>{{ $user->position->title }} ({{ $user->position->grade }})</td>
                    </tr>
                    <tr>
                        <th>Alamat Emel</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>No. Telefon Pejabat</th>
                        <td>{{ $user->office_phone_no }}</td>
                    </tr>
                    <tr>
                        <th>Kampus</th>
                        <td>{{ $user->campus->name }}</td>
                    </tr>
                    <tr>
                        <th>Peranan</th>
                        <td>
                            @if ($user->roles->count() === 1)
                            {{ ucwords(str_replace('-', ' ', $user->roles->first()->name)) }}
                            @else
                            <ul>
                                @foreach ($user->roles as $role)
                                <li>{{ ucwords(str_replace('-', ' ', $role->name)) }}</li>
                                @endforeach
                            </ul>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $user->publish_status }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Campus Information Table -->
<!-- End Page Wrapper -->
@endsection