@extends('Dashboard.layout')

@section('title', 'ການລົງທະບຽນ')

@section('page-title', 'ການລົງທະບຽນຂອງນັກສຶກສາ')

@push('styles')
<style>
    body, h1, h2, h3, h4, h5, h6, p, span, div, button, input, select, textarea, label, a, th, td {
        font-family: 'Phetsarath OT', sans-serif !important;
    }
    .btn {
        font-family: 'Phetsarath OT', sans-serif !important;
    }
    ::placeholder {
        font-family: 'Phetsarath OT', sans-serif !important;
    }
</style>
@endpush

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('registrations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> ເພີ່ມການລົງທະບຽນ
        </a>
        <a href="{{ route('registrations.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກທັງໝົດ
        </a>
    </div>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('registrations.index') }}" class="row g-3">
            <div class="col-md-6">
                <label for="major_name" class="form-label">ກັ່ນຕອງຕາມສາຂາ</label>
                <div class="input-group">
                    <select class="form-select" id="major_name" name="major_name" onchange="this.form.submit()">
                        <option value="">ທັງໝົດ</option>
                        @foreach($majors as $major)
                            <option value="{{ $major['name'] }}" {{ $majorName == $major['name'] ? 'selected' : '' }}>
                                {{ $major['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @if($majorName)
                        <a href="{{ route('registrations.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> ລ້າງຕົວກອງ
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ນັກສຶກສາ</th>
                        <th>ວັນທີ</th>
                        <th>ສາຂາ</th>
                        <th>ສ່ວນຫຼຸດ</th>
                        <th>ລວມ</th>
                        <th>ສະຖານະການຊຳລະ</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $registration)
                        <tr>
                            <td>{{ $registration->id }}</td>
                            <td>{{ $registration->student->name }} {{ $registration->student->sername }}</td>
                            <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($registration->registrationDetails->count() > 0)
                                    <div class="d-flex flex-column gap-1">
                                        @foreach($registration->registrationDetails as $index => $detail)
                                            @if($index < 2)
                                                <span class="badge bg-info">{{ $detail->major->name }}</span>
                                            @elseif($index == 2)
                                                <span class="badge bg-secondary">+{{ $registration->registrationDetails->count() - 2 }} ເພີ່ມເຕີມ</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-secondary">ບໍ່ມີ</span>
                                @endif
                            </td>
                            <td>{{ $registration->pro }}%</td>
                            <td>
                                {{ number_format($registration->registrationDetails->sum('total_price'), 2) }}
                            </td>
                            <td>
                                @if($registration->payment_status == 'pending')
                                    <span class="badge bg-warning">ລໍຖ້າ</span>
                                @else
                                    <span class="badge bg-success">ສຳເລັດ</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('registrations.show', $registration->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('registrations.export-pdf', $registration->id) }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @if($registration->payment_status == 'pending')
                                    <form action="{{ route('registrations.confirm-payment', $registration->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" title="ຢືນຢັນການຊຳລະເງິນ">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-registration-form-{{ $registration->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-registration-form-{{ $registration->id }}" action="{{ route('registrations.destroy', $registration->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Force page reload when navigating back
    window.addEventListener('pageshow', function(event) {
        // Check if the page is loaded from cache (back/forward navigation)
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            // Refresh the page to get updated data
            window.location.reload();
        }
    });
</script>
@endsection
