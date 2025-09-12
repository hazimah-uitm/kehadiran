@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Pengguna</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-role') }}">Senarai Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">Maklumat {{ ucfirst($userRole->name) }}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('user-role.edit', $userRole->id) }}">
            <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
        </a>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">Maklumat {{ ucfirst($userRole->name) }}</h6>
<hr />

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Nama Peranan Pengguna</th>
                        <td>{{ ucfirst($userRole->name) }}</td>
                    </tr>
                    <tr>
                        <th>Senarai Akses</th>
                        <td>
                            <ul>
                                @foreach ($userRole->permissions as $permission)
                                <li>{{ $permission->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            {{ $userRole->publish_status ? 'Aktif' : 'Tidak Aktif' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Page Wrapper -->
@endsection