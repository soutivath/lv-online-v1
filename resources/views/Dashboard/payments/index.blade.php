@extends('Dashboard.layout')

@section('title', 'ການຊຳລະເງິນ')

@section('page-title', 'ການຊຳລະເງິນຂອງນັກສຶກສາ')

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
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> ເພີ່ມການຊຳລະເງິນ
        </a>
        <a href="{{ route('payments.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກທັງໝົດ
        </a>
    </div>
@endsection

@section('content')
<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('payments.index') }}" class="row g-3">
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
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> ລ້າງຕົວກອງ
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

@if(count($groupedPayments) > 0)
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>ການຊຳລະເງິນກຸ່ມ</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ເລກໃບບິນ</th>
                        <th>ນັກສຶກສາ</th>
                        <th>ວັນທີ</th>
                        <th>ລາຍການ</th>
                        <th>ຈຳນວນທັງໝົດ</th>
                        <th>ສະຖານະ</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedPayments as $group)
                        <tr>
                            <td>{{ substr($group['payment']->bill_number, 0, 12) }}...</td>
                            <td>{{ $group['payment']->student->name }} {{ $group['payment']->student->sername }}</td>
                            <td>{{ \Carbon\Carbon::parse($group['payment']->date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $group['count'] }} ລາຍການ</span>
                            </td>
                            <td class="fw-bold">{{ number_format($group['total'], 2) }}</td>
                            <td>
                                @if($group['all_success'])
                                    <span class="badge bg-success">ສຳເລັດ</span>
                                @elseif($group['all_pending'])
                                    <span class="badge bg-warning">ລໍຖ້າ</span>
                                @else
                                    <span class="badge bg-info">ບາງສ່ວນ</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $group['payment']->id) }}" class="btn btn-sm btn-info" title="ເບິ່ງລາຍລະອຽດ">
                                    <i class="fas fa-eye"></i> ເບິ່ງກຸ່ມ
                                </a>
                                
                                <a href="{{ route('payments.export-pdf', $group['payment']->id) }}" class="btn btn-sm btn-success" target="_blank" title="ສົ່ງອອກ PDF">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                
                                @if($group['all_pending'])
                                    <form action="{{ route('payments.confirm', $group['payment']->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" title="ຢືນຢັນການຊຳລະເງິນທັງໝົດ">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header {{ count($groupedPayments) > 0 ? 'bg-secondary' : 'bg-primary' }} text-white">
        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>ການຊຳລະເງິນເທື່ອລະລາຍການ</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ນັກສຶກສາ</th>
                        <th>ວັນທີ</th>
                        <th>ສາຂາ</th>
                        <th>ປີການສຶກສາ</th>
                        <th>ເທີມ</th>
                        <th>ພາກຮຽນ</th>
                        <th>ຈຳນວນເງິນ</th>
                        <th>ສະຖານະ</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($individualPayments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                            <td>{{ $payment->major->name }}</td>
                            <td>{{ $payment->major->year->name ?? 'ບໍ່ມີ' }}</td>
                            <td>{{ $payment->major->term->name ?? 'ບໍ່ມີ' }}</td>
                            <td>{{ $payment->major->semester->name ?? 'ບໍ່ມີ' }}</td>
                            <td>{{ number_format($payment->total_price, 2) }}</td>
                            <td>
                                @if($payment->status == 'pending')
                                    <span class="badge bg-warning">ລໍຖ້າ</span>
                                @else
                                    <span class="badge bg-success">ສຳເລັດ</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info" title="ເບິ່ງລາຍລະອຽດ">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payments.export-pdf', $payment->id) }}" class="btn btn-sm btn-success" target="_blank" title="ສົ່ງອອກ PDF">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                @if($payment->status == 'pending')
                                    <form action="{{ route('payments.confirm', $payment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" title="ຢືນຢັນການຊຳລະເງິນ">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-payment-form-{{ $payment->id }}')" title="ລຶບການຊຳລະເງິນ">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-payment-form-{{ $payment->id }}" action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">ບໍ່ພົບລາຍການການຊຳລະເງິນເທື່ອລະລາຍການ</td>
                        </tr>
                    @endforelse
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
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
    
    function confirmDelete(formId) {
        // Use SweetAlert2 instead of native confirm
        Swal.fire({
            title: 'ທ່ານແນ່ໃຈບໍ?',
            text: 'ທ່ານກຳລັງຈະລຶບລາຍການຈ່າຍເງິນນີ້. ການກະທຳນີ້ບໍ່ສາມາດຍ້ອນກັບໄດ້!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ແມ່ນ, ລຶບເລີຍ!',
            cancelButtonText: 'ຍົກເລີກ'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection
