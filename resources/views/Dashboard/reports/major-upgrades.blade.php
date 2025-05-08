@extends('Dashboard.layout')

@section('title', 'Major Upgrade Details')

@section('page-title', 'Upgrade Details for ' . $major->name)

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Report
        </button>
    </div>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>Major Information</h5>
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name:</th>
                        <td>{{ $major->name }}</td>
                    </tr>
                    <tr>
                        <th>Semester:</th>
                        <td>{{ $major->semester->name }}</td>
                    </tr>
                    <tr>
                        <th>Term:</th>
                        <td>{{ $major->term->name }}</td>
                    </tr>
                    <tr>
                        <th>Year:</th>
                        <td>{{ $major->year->name }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>Upgrade Summary</h5>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">Total Students:</th>
                        <td><span class="badge bg-primary">{{ $studentCount }}</span></td>
                    </tr>
                    <tr>
                        <th>Total Upgrades:</th>
                        <td><span class="badge bg-primary">{{ $upgrades->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td><span class="badge bg-success">{{ number_format($totalAmount, 2) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Upgrade List</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Subjects</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upgrades as $upgrade)
                    <tr>
                        <td>{{ $upgrade->id }}</td>
                        <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                        <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-info">{{ $upgrade->upgradeDetails->count() }} subjects</span>
                        </td>
                        <td>{{ number_format($upgrade->upgradeDetails->sum('price'), 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $upgrade->payment_status == 'success' ? 'success' : 'warning' }}">
                                {{ ucfirst($upgrade->payment_status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('upgrades.show', $upgrade->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No upgrades found for this major</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total:</th>
                        <th>{{ number_format($totalAmount, 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .navbar, .btn-group, .card-header, .sidebar, footer {
            display: none !important;
        }
        .container-fluid, .card, .card-body {
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        .table {
            width: 100% !important;
        }
    }
</style>
@endsection
