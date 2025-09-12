@extends('layouts.master')

@section('content')
<!-- Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Peranan Pengguna</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('user-role') }}">Senarai Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Pengguna</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<h6 class="mb-0 text-uppercase">{{ $str_mode }} Pengguna</h6>
<hr />

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $save_route }}">
            {{ csrf_field() }}

            <div class="mb-3">
                <label for="name" class="form-label">Nama Peranan</label>
                <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name" name="name" value="{{ old('name') }}">
                @if ($errors->has('name'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('name') as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="permissions" class="form-label">Pilih Akses</label>
                <div class="row">
                    @foreach ($userPermissionList as $permissionGroup)
                    <div class="col-md-6">
                        <strong>{{ $permissionGroup['category'] }}</strong><br>

                        <div class="form-check">
                            <input class="form-check-input select-all" type="checkbox" onclick="selectAllGroupCheckboxes(this)">
                            <label class="form-check-label" for="select-all">Select All</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input deselect-all" type="checkbox" onclick="deselectAllGroupCheckboxes(this)">
                            <label class="form-check-label" for="deselect-all">Deselect All</label>
                        </div>

                        @foreach ($permissionGroup['permissions'] as $permission)
                        <div class="form-check">
                            <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" id="{{ $permission }}" value="{{ $permission }}" {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $permission }}">{{ $permission }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @if ($errors->has('permissions'))
                <div class="invalid-feedback">
                    @foreach ($errors->get('permissions') as $error)
                    {{ $error }}
                    @endforeach
                </div>
                @endif
            </div>

            <div class="mb-3">
                <label for="publish_status" class="form-label">Status</label>
                <div class="form-check">
                    <input type="radio" id="aktif" name="publish_status" value="1" {{ isset($userRole) && $userRole->publish_status ? 'checked' : '' }}>
                    <label class="form-check-label" for="aktif">Aktif</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="tidak_aktif" name="publish_status" value="0" {{ isset($userRole) && !$userRole->publish_status ? 'checked' : '' }}>
                    <label class="form-check-label" for="tidak_aktif">Tidak Aktif</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
        </form>
    </div>
</div>

<!-- End Page Wrapper -->
@endsection