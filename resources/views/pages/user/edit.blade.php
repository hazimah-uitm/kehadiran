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
                    <label for="name" class="form-label">Nama Penuh</label>
                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                        name="name" value="{{ old('name', $user->name ?? '') }}">
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('name') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="staff_id" class="form-label">No. Pekerja</label>
                        <input type="number" class="form-control {{ $errors->has('staff_id') ? 'is-invalid' : '' }}"
                            id="staff_id" name="staff_id" value="{{ old('staff_id', $user->staff_id ?? '') }}">
                        @if ($errors->has('staff_id'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('staff_id') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <label for="email" class="form-label">Alamat Emel</label>
                        <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            id="email" name="email" value="{{ old('email', $user->email ?? '') }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('email') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="phone_no" class="form-label">No. Telefon</label>
                        <input type="number" class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}"
                            id="phone_no" name="phone_no" value="{{ old('phone_no', $user->phone_no ?? '') }}">
                        @if ($errors->has('phone_no'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('phone_no') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <label for="position_id" class="form-label">Jawatan</label>
                        <select class="form-select {{ $errors->has('position_id') ? 'is-invalid' : '' }}" id="position_id"
                            name="position_id">
                            <option value="" disabled>Pilih Jawatan</option>
                            @foreach ($positionList as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('position_id', $user->position_id ?? '') == $position->id ? 'selected' : '' }}>
                                    {{ $position->title }} ({{ $position->grade }})
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('position_id'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('position_id') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label for="ptj_id" class="form-label">PTJ</label>
                        <select class="form-select {{ $errors->has('ptj_id') ? 'is-invalid' : '' }}" id="ptj_id"
                            name="ptj_id">
                            <option value="" disabled>Pilih PTJ</option>
                            @foreach ($ptjList as $ptj)
                                <option value="{{ $ptj->id }}"
                                    {{ old('ptj_id', $user->ptj_id ?? '') == $ptj->id ? 'selected' : '' }}>
                                    {{ $ptj->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('ptj_id'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('ptj_id') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <label for="campus_id" class="form-label">Kampus</label>
                        <select class="form-select {{ $errors->has('campus_id') ? 'is-invalid' : '' }}" id="campus_id"
                            name="campus_id">
                            <option value="" disabled>Pilih Kampus</option>
                            @foreach ($campusList as $campus)
                                <option value="{{ $campus->id }}"
                                    {{ old('campus_id', $user->campus_id ?? '') == $campus->id ? 'selected' : '' }}>
                                    {{ $campus->name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('campus_id'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('campus_id') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-6">
                        <label class="form-label">Peranan Pengguna</label>
                        <div>
                            @foreach ($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" id="{{ $role->name }}"
                                        value="{{ $role->name }}"
                                        {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $role->name }}">
                                        {{ ucwords(str_replace('-', ' ', $role->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @if ($errors->has('roles'))
                            <div class="invalid-feedback">
                                @foreach ($errors->get('roles') as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label for="publish_status" class="form-label">Status</label>
                    <div class="form-check">
                        <input type="radio" id="aktif" name="publish_status" value="1"
                            {{ ($user->publish_status ?? '') == 'Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Aktif</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="tidak_aktif" name="publish_status" value="0"
                            {{ ($user->publish_status ?? '') == 'Tidak Aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="tidak_aktif">Tidak Aktif</label>
                    </div>
                    @if ($errors->has('publish_status'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('publish_status') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>

        </div>
    </div>
    <!-- End Page Wrapper -->

    <script>
        document.getElementById('phone_no').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/-/g, '');
        });
    </script>
@endsection
