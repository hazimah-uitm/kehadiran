@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb mb-3">
        <div class="row align-items-center">
            <div class="col-12 col-md-9 d-flex align-items-center">
                <div class="breadcrumb-title pe-3">Edit Profil Pengguna</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('profile.show', ['id' => $user->id]) }}">Profil</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Profil</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Edit Profil {{ $user->name }}</h6>
    <hr />

    <div class="container">
        <div class="main-body">
            <div class="row">
                <!-- Sidebar (User Info) -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <form method="POST" action="{{ $save_route }}" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center text-center">
                                    <!-- User Image -->
                                    <img src="{{ !empty($user->profile_image) ? asset('public/storage/' . $user->profile_image) : asset('public/assets/images/avatars/user.png') }}"
                                        alt="Profile Image" class="rounded-circle p-1 bg-primary profile-preview"
                                        width="150" height="150">

                                    <!-- Profile Image Upload Form -->
                                    <div class="d-flex gap-2 justify-content-center mt-2">
                                        <input type="file" name="profile_image" id="profile_image"
                                            class="form-control d-none">
                                        <label for="profile_image" class="btn btn-primary">Edit Gambar</label>
                                        <button type="button" id="remove_photo" class="btn btn-danger">Padam
                                            Gambar</button>
                                    </div>
                                    <!-- Hidden Input to Indicate Photo Removal -->
                                    <input type="hidden" name="remove_photo" id="remove_photo_input" value="0">
                                </div>
                                <hr class="my-4">
                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                @php
                                   $isPengguna = auth()->user()->hasRole('Pengguna');
                                    $canEditAll = auth()
                                        ->user()
                                        ->hasAnyRole(['Superadmin', 'Admin']);
                                @endphp

                                <!-- Name Field -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                                        name="name" value="{{ $user->name }}"
                                        {{ $canEditAll ? '' : 'disabled' }}>
                                    @if (!$canEditAll)
                                        <input type="hidden" name="name" value="{{ $user->name }}">
                                    @endif
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('name') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Alamat Emel</label>
                                    <input type="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email"
                                        name="email" value="{{ $user->email }}"
                                        {{ $canEditAll ? '' : 'disabled' }}>
                                    @if (!$canEditAll)
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                    @endif
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('email') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Staff ID Field -->
                                <div class="mb-3">
                                    <label for="staff_id" class="form-label">No. Pekerja</label>
                                    <input type="number"
                                        class="form-control {{ $errors->has('staff_id') ? 'is-invalid' : '' }}"
                                        id="staff_id" name="staff_id" value="{{ $user->staff_id }}"
                                        {{ $canEditAll ? '' : 'disabled' }}>
                                    @if (!$canEditAll)
                                        <input type="hidden" name="staff_id" value="{{ $user->staff_id }}">
                                    @endif
                                    @if ($errors->has('staff_id'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('staff_id') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Position Field -->
                                <div class="mb-3">
                                    <label for="position_id" class="form-label">Jawatan</label>
                                    <select class="form-select {{ $errors->has('position_id') ? 'is-invalid' : '' }}"
                                        id="position_id" name="position_id"
                                        {{ $canEditAll ? '' : 'disabled' }}>
                                        @foreach ($positionList as $position)
                                            <option value="{{ $position->id }}"
                                                {{ old('position_id') == $position->id || ($user->position_id ?? '') == $position->id ? 'selected' : '' }}>
                                                {{ $position->title }} ({{ $position->grade }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if (!$canEditAll)
                                        <input type="hidden" name="position_id" value="{{ $user->position_id }}">
                                    @endif
                                    @if ($errors->has('position_id'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('position_id') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Dept Field -->
                                <div class="mb-3">
                                    <label for="ptj_id" class="form-label">PTJ</label>
                                    <select class="form-select {{ $errors->has('ptj_id') ? 'is-invalid' : '' }}"
                                        id="ptj_id" name="ptj_id" {{ $canEditAll ? '' : 'disabled' }}>
                                        @foreach ($ptjList as $ptj)
                                            <option value="{{ $ptj->id }}"
                                                {{ old('ptj_id', $user->ptj_id ?? '') == $ptj->id ? 'selected' : '' }}>
                                                {{ $ptj->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if (!$canEditAll)
                                        <input type="hidden" name="ptj_id" value="{{ $user->ptj_id }}">
                                    @endif
                                    @if ($errors->has('ptj_id'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('ptj_id') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Dept Field -->
                                <div class="mb-3">
                                    <label for="campus_id" class="form-label">campus</label>
                                    <select class="form-select {{ $errors->has('campus_id') ? 'is-invalid' : '' }}"
                                        id="campus_id" name="campus_id" {{ $canEditAll ? '' : 'disabled' }}>
                                        @foreach ($campusList as $campus)
                                            <option value="{{ $campus->id }}"
                                                {{ old('campus_id') == $campus->id || ($user->campus_id ?? '') == $campus->id ? 'selected' : '' }}>
                                                {{ $campus->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if (!$canEditAll)
                                        <input type="hidden" name="campus_id" value="{{ $user->campus_id }}">
                                    @endif
                                    @if ($errors->has('campus_id'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('campus_id') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <!-- Office Phone Field -->
                                <div class="mb-3">
                                    <label for="phone_no" class="form-label">No. Telefon Pejabat</label>
                                    <input type="number"
                                        class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}"
                                        id="phone_no" name="phone_no"
                                        value="{{ old('phone_no', $user->phone_no) }}"
                                        {{ $canEditAll || $isPengguna ? '' : 'disabled' }}>
                                    @if ($errors->has('phone_no'))
                                        <div class="invalid-feedback">
                                            @foreach ($errors->get('phone_no') as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Profile Form -->
    <script>
        const fileInput = document.getElementById('profile_image');
        const previewImg = document.querySelector('.profile-preview');
        const removeButton = document.getElementById('remove_photo');
        const removeInput = document.getElementById('remove_photo_input');

        // Preview uploaded image
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // When Remove Photo button is clicked, set the input to 1
        removeButton.addEventListener('click', function() {
            removeInput.value = '1'; // Mark photo for removal
            document.querySelector('.profile-preview').src =
            'https://via.placeholder.com/150'; // Set placeholder image
        });
    </script>


@endsection
