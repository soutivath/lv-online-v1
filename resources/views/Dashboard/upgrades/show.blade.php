@extends('Dashboard.layout')

@section('title', 'Upgrade Details')

@section('page-title', 'Grade Upgrade Details')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('upgrades.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Upgrade Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 150px">Upgrade ID</th>
                        <td>{{ $upgrade->id }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Employee</th>
                        <td>{{ $upgrade->employee->name }} {{ $upgrade->employee->sername }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td>
                            @if($upgrade->payment_status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-success">Success</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Student Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12 text-center mb-3">
                        @if($upgrade->student->picture)
                            <img src="{{ asset('storage/' . $upgrade->student->picture) }}" alt="Student Picture" class="img-thumbnail" style="max-height: 150px">
                        @else
                            <div class="border p-3 text-center">
                                <i class="fas fa-user fa-5x text-secondary"></i>
                                <p class="mt-2">No picture available</p>
                            </div>
                        @endif
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th style="width: 150px">Student ID</th>
                        <td>{{ $upgrade->student->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $upgrade->student->gender }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $upgrade->student->tell }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Major Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <p><strong>Major:</strong> {{ $upgrade->major->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <p><strong>Semester:</strong> {{ $upgrade->major->semester->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <p><strong>Term:</strong> {{ $upgrade->major->term->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <p><strong>Academic Year:</strong> {{ $upgrade->major->year->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Subject Upgrades</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Credits</th>
                        <th>Credit Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upgrade->upgradeDetails as $detail)
                        <tr>
                            <td>{{ $detail->subject->name }}</td>
                            <td>{{ $detail->subject->credit->qty }}</td>
                            <td>{{ number_format($detail->subject->credit->price, 2) }}</td>
                            <td>{{ number_format($detail->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th>{{ number_format($upgrade->upgradeDetails->sum('total_price'), 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($upgrade->payment_proof)
<div class="card mb-4">
    <div class="card-header">
        <h5>Payment Proof</h5>
    </div>
    <div class="card-body text-center">
        <img src="{{ asset('storage/' . $upgrade->payment_proof) }}" alt="Payment Proof" class="img-fluid img-thumbnail" style="max-height: 400px;">
    </div>
</div>
@endif

@if($upgrade->payment_status == 'pending')
<div class="mb-4">
    <form action="{{ route('upgrades.confirm-payment', $upgrade->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle me-1"></i> Confirm Payment
        </button>
    </form>
</div>
@endif

<div class="d-flex justify-content-end">
    <button class="btn btn-danger me-2" onclick="confirmDelete('delete-upgrade-form-{{ $upgrade->id }}')">
        <i class="fas fa-trash"></i> Delete Upgrade
    </button>
    <form id="delete-upgrade-form-{{ $upgrade->id }}" action="{{ route('upgrades.destroy', $upgrade->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
