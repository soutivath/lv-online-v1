@extends('Dashboard.layout')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Students</h5>
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
                        <h5 class="card-title">Total Employees</h5>
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
                        <h5 class="card-title">Total Registrations</h5>
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
</div>
@endsection
