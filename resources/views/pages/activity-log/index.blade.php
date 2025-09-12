@extends('layouts.master')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Aktiviti Log</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Log Aktiviti</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<h6 class="mb-0 text-uppercase">Senarai Log Aktiviti</h6>
<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tindakan</th>
                        <th>Model</th>
                        <th>Dibuat oleh</th>
                        <th>Keterangan Perubahan</th>
                        <th>Tarikh</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($activities) > 0)
                    @foreach ($activities as $activityLog)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $activityLog->description }}</td>
                        <td>
                            @if ($activityLog->subject)
                            {{ class_basename($activityLog->subject_type) }} (ID: {{ $activityLog->subject_id }})
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if ($activityLog->causer)
                            {{ $activityLog->causer->name }} (ID: {{ $activityLog->causer_id }})
                            @else
                            System
                            @endif
                        </td>
                        <td>
                            @if ($activityLog->properties->isNotEmpty())
                            <ul>
                                @foreach ($activityLog->properties['attributes'] as $key => $value)
                                @if (is_array($value))
                                @foreach ($value as $subKey => $subValue)
                                <li><strong>{{ ucfirst($key) }} ({{ $subKey }}):</strong> {{ $subValue }}</li>
                                @endforeach
                                @else
                                <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                @endif
                                @endforeach
                            </ul>
                            @else
                            No changes
                            @endif
                        </td>
                        <td>{{ $activityLog->created_at }}</td>
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
                <form action="{{ route('activity-log') }}" method="GET" id="perPageForm">
                    <select name="perPage" id="perPage" class="form-select" onchange="document.getElementById('perPageForm').submit()">
                        <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                        <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                        <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                    </select>
                </form>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <div class="mx-1 mt-2">{{ $activities->firstItem() }} – {{ $activities->lastItem() }} dari
                    {{ $activities->total() }} rekod
                </div>
                <div>{{ $activities->links() }}</div>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection