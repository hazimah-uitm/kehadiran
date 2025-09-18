@extends('layouts.master')
@section('content')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Senarai Peserta Dipadam</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('program') }}">Senarai Program</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('participant', ['program' => $program->id]) }}">Senarai Peserta</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rekod Dipadam</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <h6 class="mb-0 text-uppercase">Senarai Peserta Dipadam</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nama</th>
                        <th>IC/Passport</th>
                        <th>No. Telefon</th>
                        <th>Institusi</th>
                        <th>Tindakan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ($trashList->count() > 0)
                        @foreach ($trashList as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->ic_passport }}</td>
                                <td>{{ $p->phone_no ?? '-' }}</td>
                                <td>{{ $p->institution ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('participant.restore', ['program' => $program->id, 'id' => $p->id]) }}"
                                       class="btn btn-success btn-sm" title="Kembalikan">
                                        <i class="bx bx-undo"></i>
                                    </a>
                                    <a type="button" data-bs-toggle="modal"
                                       data-bs-target="#deleteModal{{ $p->id }}"
                                       class="btn btn-danger btn-sm" title="Padam Kekal">
                                        <i class="bx bx-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="5">Tiada rekod</td></tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $trashList->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>

    @foreach ($trashList as $p)
        <div class="modal fade" id="deleteModal{{ $p->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pengesahan Padam Kekal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Adakah anda pasti ingin memadam kekal rekod
                        <span style="font-weight:600;">Peserta {{ $p->name }}</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <form method="POST"
                              action="{{ route('participant.forceDelete', ['program' => $program->id, 'id' => $p->id]) }}"
                              class="d-inline">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger">Padam</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
