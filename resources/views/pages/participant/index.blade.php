@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Peserta</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Senarai Peserta</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('participant.trash', ['program' => $program->id]) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Senarai Rekod Dipadam</button>
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">Senarai Peserta ({{ $program->title }})</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <form action="{{ route('participant.search', ['program' => $program->id]) }}" method="GET"
                        id="searchForm" class="d-lg-flex align-items-center gap-3">
                        <div class="input-group">
                            <input type="text" class="form-control rounded"
                                placeholder="Carian nama/IC/telefon/institusi..." name="search"
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
                        <a href="{{ route('participant.create', ['program' => $program->id]) }}"
                            class="btn btn-primary radius-30 mt-2 mt-lg-0">
                            <i class="bx bxs-plus-square"></i> Tambah Peserta
                        </a>
                    </div>
                @endhasanyrole
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>IC/Passport</th>
                            <th>No. Telefon</th>
                            <th>Kod Peserta</th>
                            <th>Kod QR</th>
                            <th>Institusi</th>
                            <th style="width:180px">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($participants->count() > 0)
                            @foreach ($participants as $p)
                                <tr>
                                    <td>{{ $loop->iteration + ($participants->currentPage() - 1) * $participants->perPage() }}
                                    </td>
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->ic_passport }}</td>
                                    <td>{{ $p->phone_no ?? '-' }}</td>
                                    <td>
                                        @if ($p->participant_code)
                                            <div class="input-group input-group-sm" style="max-width:220px">
                                                <input type="text" class="form-control"
                                                    value="{{ $p->participant_code }}" readonly>
                                                <button type="button" class="btn btn-secondary"
                                                    onclick="navigator.clipboard.writeText('{{ $p->participant_code }}')">Salin</button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->qr_path)
                                            <img src="{{ asset('public/storage/' . $p->qr_path) }}" alt="QR"
                                                style="height:56px">
                                            <div><a class="btn btn-sm btn-primary mt-1"
                                                    href="{{ asset('public/storage/' . $p->qr_path) }}" download>Muat Turun</a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->institution ?? '-' }}</td>
                                    <td>
                                        @hasanyrole('Superadmin|Admin')
                                            <a href="{{ route('participant.edit', ['program' => $program->id, 'participant' => $p->id]) }}"
                                                class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="Kemaskini">
                                                <i class="bx bxs-edit"></i>
                                            </a>
                                        @endhasanyrole

                                        <a href="{{ route('participant.show', ['program' => $program->id, 'participant' => $p->id]) }}"
                                            class="btn btn-primary btn-sm" data-bs-toggle="tooltip"
                                            data-bs-placement="bottom" title="Papar">
                                            <i class="bx bx-show"></i>
                                        </a>

                                        @hasanyrole('Superadmin|Admin')
                                            <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-title="Padam">
                                                <span class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $p->id }}">
                                                    <i class="bx bx-trash"></i>
                                                </span>
                                            </a>
                                        @endhasanyrole
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">Tiada rekod</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                    <form action="{{ route('participant.search', ['program' => $program->id]) }}" method="GET"
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
                    @if ($participants->count())
                        <span class="mx-2 mt-2 small text-muted">
                            Menunjukkan {{ $participants->firstItem() }} hingga {{ $participants->lastItem() }}
                            daripada {{ $participants->total() }} rekod
                        </span>
                    @endif
                    <div class="pagination-wrapper">
                        {{ $participants->appends([
                                'search' => request('search'),
                                'perPage' => request('perPage', 10),
                            ])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @foreach ($participants as $p)
        <div class="modal fade" id="deleteModal{{ $p->id }}" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Pengesahan Padam Rekod</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Adakah anda pasti ingin memadam rekod
                        <span style="font-weight: 600;">Peserta {{ $p->name }}</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <form class="d-inline" method="POST"
                            action="{{ route('participant.destroy', ['program' => $program->id, 'participant' => $p->id]) }}">
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
            var searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    document.getElementById('searchForm').submit();
                });
            }
            document.getElementById('resetButton').addEventListener('click', function() {
                window.location.href = "{{ route('participant', ['program' => $program->id]) }}";
            });
        });
    </script>
@endsection
