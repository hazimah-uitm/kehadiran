@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Kehadiran</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    @if (isset($session) && $session)
                        <li class="breadcrumb-item">
                            <a href="{{ route('session', ['program' => $program->id]) }}">Senarai Sesi</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Kehadiran Sesi</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">Kehadiran Program
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            {{-- Butang paparkan borang attendance (urusetia key-in/scan) --}}
            @if (isset($session) && $session)
                <a href="{{ route('attendance.create.session', ['program' => $program->id, 'session' => $session->id]) }}"
                    class="btn btn-primary mt-2 mt-lg-0">
                    <i class="bx bx-qr"></i> Papar Borang Kehadiran
                </a>
            @else
                <a href="{{ route('attendance.create.program', ['program' => $program->id]) }}"
                    class="btn btn-primary mt-2 mt-lg-0">
                    <i class="bx bx-qr"></i> Papar Borang Kehadiran
                </a>
            @endif
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">
        Senarai Kehadiran
        @if (isset($session) && $session)
            Sesi ({{ $session->title }})
        @else
            Program ({{ $program->title }})
        @endif
    </h6>

    <hr />

    <div class="card">
        <div class="card-body">
            <div class="d-lg-flex align-items-center mb-4 gap-3">
                <div class="position-relative">
                    <form
                        action="{{ isset($session) && $session
                            ? route('attendance.search.session', ['program' => $program->id, 'session' => $session->id])
                            : route('attendance.search.program', ['program' => $program->id]) }}"
                        method="GET" id="searchForm" class="d-lg-flex align-items-center gap-3">
                        <div class="input-group">
                            <input type="text" class="form-control rounded" placeholder="Carian nama / kod peserta..."
                                name="search" value="{{ request('search') }}" id="searchInput">

                            <input type="hidden" name="perPage" value="{{ request('perPage', 20) }}">
                            <button type="submit" class="btn btn-primary ms-1 rounded" id="searchButton">
                                <i class="bx bx-search"></i>
                            </button>
                            <button type="button" class="btn btn-secondary ms-1 rounded" id="resetButton">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>

                {{-- (Optional) tambah butang lain jika perlu --}}
            </div>

            <div class="mb-3">
                <div><strong>Program:</strong> {{ $program->title }}</div>
                @if (isset($session) && $session)
                    <div><strong>Sesi:</strong> {{ $session->title }}</div>
                    <div><strong>Lokasi:</strong> {{ $session->venue }}</div>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Peserta</th>
                            <th>Kod Peserta</th>
                            <th>No. IC / Passport</th>
                            <th>No. Telefon</th>
                            <th>Institusi / Organisasi</th>
                            <th>Direkod Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($attendances->count() > 0)
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}
                                    </td>
                                    <td>{{ $attendance->participant->name ?? '-' }}</td>
                                    <td>{{ $attendance->participant_code ?? '-' }}</td>
                                    <td>{{ $attendance->participant->ic_passport ?? '-' }}</td>
                                    <td>{{ $attendance->participant->phone_no ?? '-' }}</td>
                                    <td>{{ $attendance->participant->institution ?? '-' }}</td>
                                    <td>{{ optional($attendance->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">Tiada rekod</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                    <form
                        action="{{ isset($session) && $session
                            ? route('attendance.search.session', ['program' => $program->id, 'session' => $session->id])
                            : route('attendance.search.program', ['program' => $program->id]) }}"
                        method="GET" id="perPageForm" class="d-flex align-items-center">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="perPage" id="perPage" class="form-select form-select-sm"
                            onchange="document.getElementById('perPageForm').submit()">
                            <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                            <option value="20" {{ Request::get('perPage', 20) == '20' ? 'selected' : '' }}>20</option>
                            <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                        </select>
                    </form>
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    @if ($attendances->count())
                        <span class="mx-2 mt-2 small text-muted">
                            Menunjukkan {{ $attendances->firstItem() }} hingga {{ $attendances->lastItem() }}
                            daripada {{ $attendances->total() }} rekod
                        </span>
                    @endif
                    <div class="pagination-wrapper">
                        {{ $attendances->appends([
                                'search' => request('search'),
                                'perPage' => request('perPage', 20),
                            ])->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    document.getElementById('searchForm').submit();
                });
            }

            document.getElementById('resetButton').addEventListener('click', function() {
                // Reset ke route index mengikut konteks
                @if (isset($session) && $session)
                    window.location.href =
                        "{{ route('attendance.index.session', ['program' => $program->id, 'session' => $session->id]) }}";
                @else
                    window.location.href =
                        "{{ route('attendance.index.program', ['program' => $program->id]) }}";
                @endif
            });
        });
    </script>
@endsection
