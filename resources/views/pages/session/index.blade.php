@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Sesi</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Senarai Sesi ({{ $program->title }})</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('session.trash', ['program' => $program->id]) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Senarai Rekod Dipadam</button>
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">Senarai Sesi</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <form action="{{ route('session.search', ['program' => $program->id]) }}" method="GET" id="searchForm"
                        class="d-lg-flex align-items-center gap-3">
                        <div class="input-group">
                            <input type="text" class="form-control rounded" placeholder="Carian..." name="search"
                                value="{{ request('search') }}" id="searchInput">

                            <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                            <button type="submit" class="btn btn-primary ms-1 rounded" id="searchButton">
                                <i class="bx bx-search"></i>
                            </button>
                            <button type="button" class="btn btn-secondary ms-1 rounded" id="resetButton">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>

                @hasanyrole('Superadmin|Admin')
                    <div class="ms-auto">
                        <a href="{{ route('session.create', ['program' => $program->id]) }}"
                            class="btn btn-primary radius-30 mt-2 mt-lg-0">
                            <i class="bx bxs-plus-square"></i> Tambah Sesi
                        </a>
                    </div>
                @endhasanyrole
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sesi</th>
                            <th>Masa</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th style="width:180px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sessions->count() > 0)
                            @foreach ($sessions as $session)
                                <tr>
                                    <td>{{ $loop->iteration + ($sessions->currentPage() - 1) * $sessions->perPage() }}</td>
                                    <td>{{ $session->title }}</td>
                                    <td>
                                        @if ($session->start_time || $session->end_time)
                                            {{ \Carbon\Carbon::parse($session->start_time)->format('d/m/Y H:i') }}
                                            –
                                            {{ \Carbon\Carbon::parse($session->end_time)->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $session->venue ?? '-' }}</td>
                                    <td>
                                        @if ($session->publish_status == 'Aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @hasanyrole('Superadmin|Admin')
                                            <a href="{{ route('session.edit', [$program->id, $session->id]) }}"
                                                class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Kemaskini">
                                                <i class="bx bxs-edit"></i>
                                            </a>
                                        @endhasanyrole

                                        <a href="{{ route('attendance.index.session', ['program' => $program->id, 'session' => $session->id]) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="bx bx-list-check"></i> Kehadiran
                                        </a>

                                        <a href="{{ route('session.show', [$program->id, $session->id]) }}"
                                            class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Papar">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        @hasanyrole('Superadmin|Admin')
                                            <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-title="Padam">
                                                <span class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $session->id }}">
                                                    <i class="bx bx-trash"></i>
                                                </span>
                                            </a>
                                        @endhasanyrole
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">Tiada rekod</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                    <form action="{{ route('session.search', ['program' => $program->id]) }}" method="GET"
                        id="perPageForm" class="d-flex align-items-center">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" id="perPage" class="form-select form-select-sm"
                            onchange="document.getElementById('perPageForm').submit()">
                            <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                            <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                        </select>
                    </form>
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    @if ($sessions->count())
                        <span class="mx-2 mt-2 small text-muted">
                            Menunjukkan {{ $sessions->firstItem() }} hingga {{ $sessions->lastItem() }}
                            daripada {{ $sessions->total() }} rekod
                        </span>
                    @endif
                    <div class="pagination-wrapper">
                        {{ $sessions->appends([
                                'search' => request('search'),
                                'perPage' => request('perPage', 10),
                            ])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @foreach ($sessions as $session)
        <div class="modal fade" id="deleteModal{{ $session->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Pengesahan Padam Rekod</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Adakah anda pasti ingin memadam rekod
                        <span style="font-weight: 600;">Sesi {{ $session->title }}</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <form class="d-inline" method="POST"
                            action="{{ route('session.destroy', [$program->id, $session->id]) }}">
                            {{ method_field('delete') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger">Padam</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!--end page wrapper -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit carian bila taip
            var searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    document.getElementById('searchForm').submit();
                });
            }

            // Reset carian
            document.getElementById('resetButton').addEventListener('click', function() {
                window.location.href = "{{ route('session', ['program' => $program->id]) }}";
            });
        });
    </script>
@endsection
