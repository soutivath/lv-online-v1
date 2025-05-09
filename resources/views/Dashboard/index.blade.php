@extends('Dashboard.layout')

@section('title', 'ໜ້າຫຼັກ')

@section('page-title', 'ສະຫຼຸບຂໍ້ມູນລະບົບ')

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

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">ນັກສຶກສາທັງໝົດ</h5>
                        <h2>{{ $studentCount }}</h2>
                    </div>
                    <i class="fas fa-user-graduate fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">ພະນັກງານທັງໝົດ</h5>
                        <h2>{{ $employeeCount }}</h2>
                    </div>
                    <i class="fas fa-user-tie fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">ການລົງທະບຽນທັງໝົດ</h5>
                        <h2>{{ $registrationCount }}</h2>
                    </div>
                    <i class="fas fa-clipboard-list fa-3x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                Recent Registrations
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Employee</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentRegistrations as $registration)
                                <tr>
                                    <td>{{ $registration->student->name }} {{ $registration->student->sername }}</td>
                                    <td>{{ $registration->date->format('d/m/Y H:i') }}</td>
                                    <td>{{ $registration->employee->name }} {{ $registration->employee->sername }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- 
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                Recent Payments
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Date</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                                    <td>{{ $payment->date->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($payment->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    --}}

</div>

<!-- Add this student payment search section somewhere appropriate in the dashboard view -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>ຄົ້ນຫາການຊຳລະເງິນຂອງນັກສຶກສາ</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="student_payment_search" class="form-control" placeholder="ປ້ອນຊື່ ຫຼື ID ນັກສຶກສາ..." value="{{ request('student_payment_search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> ຄົ້ນຫາ
                        </button>
                        @if(request('student_payment_search'))
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ລ້າງ
                            </a>
                        @endif
                    </div>
                </form>

                @if(request('student_payment_search'))
                    @if($searchedStudent)
                        <div class="alert alert-success mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">ພົບນັກສຶກສາ: {{ $searchedStudent->name }} {{ $searchedStudent->sername }}</h5>
                                <span class="badge bg-primary">ທັງໝົດ {{ $studentPayments->count() }} ລາຍການ</span>
                            </div>
                        </div>

                        @if($studentPayments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ລະຫັດ</th>
                                            <th>ວັນທີ</th>
                                            <th>ສາຂາ</th>
                                            <th>ຈຳນວນເງິນ</th>
                                            <th>ສະຖານະ</th>
                                            <th>ການກະທຳ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($studentPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                                                <td>{{ $payment->major->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($payment->total_price, 2) }}</td>
                                                <td>
                                                    @if($payment->status == 'pending')
                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                    @else
                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('payments.export-pdf', $payment->id) }}" class="btn btn-sm btn-success" target="_blank">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">ລວມທັງໝົດ:</th>
                                            <th>{{ number_format($studentPaymentTotal, 2) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> ນັກສຶກສານີ້ຍັງບໍ່ມີການຊຳລະເງິນ
                            </div>
                        @endif
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i> ບໍ່ພົບນັກສຶກສາທີ່ຄົ້ນຫາ
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End student payment search section -->

<!-- Add this student upgrade search section below the payment search section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>ຄົ້ນຫາການອັບເກຣດວິຊາຂອງນັກສຶກສາ</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard') }}" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="student_upgrade_search" class="form-control" placeholder="ປ້ອນຊື່ ຫຼື ID ນັກສຶກສາ..." value="{{ request('student_upgrade_search') }}">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-search"></i> ຄົ້ນຫາ
                        </button>
                        @if(request('student_upgrade_search'))
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ລ້າງ
                            </a>
                        @endif
                    </div>
                </form>

                @if(request('student_upgrade_search'))
                    @if($searchedUpgradeStudent)
                        <div class="alert alert-success mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">ພົບນັກສຶກສາ: {{ $searchedUpgradeStudent->name }} {{ $searchedUpgradeStudent->sername }}</h5>
                                <span class="badge bg-warning">ທັງໝົດ {{ $studentUpgrades->count() }} ລາຍການ</span>
                            </div>
                        </div>

                        @if($studentUpgrades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ລະຫັດ</th>
                                            <th>ວັນທີ</th>
                                            <th>ສາຂາ</th>
                                            <th>ວິຊາທີ່ອັບເກຣດ</th>
                                            <th>ຈຳນວນເງິນ</th>
                                            <th>ສະຖານະ</th>
                                            <th>ການກະທຳ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($studentUpgrades as $upgrade)
                                            <tr>
                                                <td>{{ $upgrade->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                                                <td>{{ $upgrade->major->name ?? 'N/A' }}</td>
                                                <td>
                                                    @foreach($upgrade->upgradeDetails as $detail)
                                                        <span class="badge bg-info">{{ $detail->subject->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($upgrade->upgradeDetails->sum('total_price'), 2) }}</td>
                                                <td>
                                                    @if($upgrade->payment_status == 'pending')
                                                        <span class="badge bg-warning">ລໍຖ້າ</span>
                                                    @else
                                                        <span class="badge bg-success">ສຳເລັດ</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('upgrades.show', $upgrade->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-sm btn-success" target="_blank">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4" class="text-end">ລວມທັງໝົດ:</th>
                                            <th>{{ number_format($studentUpgradeTotal, 2) }}</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i> ນັກສຶກສານີ້ຍັງບໍ່ມີການອັບເກຣດວິຊາຮຽນ
                            </div>
                        @endif
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i> ບໍ່ພົບນັກສຶກສາທີ່ຄົ້ນຫາ
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
<!-- End student upgrade search section -->

<div class="row mt-4">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>ນັກສຶກສາຕາມສາຂາ</h5>
            </div>
            <div class="card-body">
                @if($majorStudentCounts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ສາຂາ</th>
                                    <th class="text-center">ນັກສຶກສາ</th>
                                    <th class="text-end">ເປີເຊັນ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalStudents = $majorStudentCounts->sum('student_count');
                                @endphp
                                @foreach($majorStudentCounts as $major)
                                    <tr>
                                        <td>
                                            <a href="{{ route('students.index', ['major' => $major->major_name]) }}" class="text-decoration-none">
                                                {{ $major->major_name }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $major->student_count }}</span>
                                        </td>
                                        <td class="text-end">
                                            @php 
                                                $percentage = ($major->student_count / $totalStudents) * 100;
                                            @endphp
                                            <div class="progress" style="height: 10px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $percentage }}%;" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"></div>
                                            </div>
                                            <small>{{ number_format($percentage, 1) }}%</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        ບໍ່ມີຂໍ້ມູນການລົງທະບຽນນັກສຶກສາ.
                    </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('majors.index') }}" class="btn btn-sm btn-outline-primary">ເບິ່ງທັງໝົດຂອງສາຂາ</a>
            </div>
        </div>
    </div>
</div>
{{-- 
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Total Payment by Major</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Major</th>
                                <th>Total Payment (₭)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paymentTotalsByMajor as $majorName => $total)
                                <tr>
                                    <td>{{ $majorName }}</td>
                                    <td>{{ number_format($total, 2) }}</td>
                                </tr>
                            @endforeach
                            @if(count($paymentTotalsByMajor) == 0)
                                <tr>
                                    <td colspan="2" class="text-center">No payment data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Total Upgrade by Major</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Major</th>
                                <th>Total Upgrade (₭)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upgradeTotalsByMajor as $majorName => $total)
                                <tr>
                                    <td>{{ $majorName }}</td>
                                    <td>{{ number_format($total, 2) }}</td>
                                </tr>
                            @endforeach
                            @if(count($upgradeTotalsByMajor) == 0)
                                <tr>
                                    <td colspan="2" class="text-center">No upgrade data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Payment Statistics by Major -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>ສະຖິຕິການຊຳລະເງິນຕາມສາຂາ</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ສາຂາ</th>
                                <th class="text-center">ນັກສຶກສາ</th>
                                <th class="text-end">ຍອດເງິນລວມ</th>
                                <th class="text-center">ຄຳສັ່ງ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentsByMajor as $payment)
                            <tr>
                                <td>{{ $payment->major_name }}</td>
                                <td class="text-center">{{ $payment->student_count }}</td>
                                <td class="text-end">{{ number_format($payment->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.major-payments', $payment->major_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> ເບິ່ງ
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">ບໍ່ມີຂໍ້ມູນການຊຳລະເງິນ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-level-up-alt me-2"></i>ສະຖິຕິການອັບເກຣດຕາມສາຂາ</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ສາຂາ</th>
                                <th class="text-center">ນັກສຶກສາ</th>
                                <th class="text-end">ຍອດເງິນລວມ</th>
                                <th class="text-center">ຄຳສັ່ງ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upgradesByMajor as $upgrade)
                            <tr>
                                <td>{{ $upgrade->major_name }}</td>
                                <td class="text-center">{{ $upgrade->student_count }}</td>
                                <td class="text-end">{{ number_format($upgrade->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.major-upgrades', $upgrade->major_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> ເບິ່ງ
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">ບໍ່ມີຂໍ້ມູນການອັບເກຣດ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Keep any existing dashboard initialization code here
    });
</script>
@endsection
