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
