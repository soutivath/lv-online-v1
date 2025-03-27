@extends('Dashboard.layout')

@section('title', 'Registration Details')

@section('page-title', 'Registration Details')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('registrations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('registrations.export-pdf', $registration->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Registration Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 150px">Registration ID</th>
                        <td>{{ $registration->id }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Discount</th>
                        <td>{{ $registration->pro }}%</                    </tr>
                    <tr>
                        <th>Employee</th>
                        <td>{{ $registration->employee->name }} {{ $registration->employee->sername }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td>
                            @if($registration->payment_status == 'pending')
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
                        @if($registration->student->picture)
                            <img src="{{ asset('storage/' . $registration->student->picture) }}" alt="Student Picture" class="img-thumbnail" style="max-height: 150px">
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
                        <td>{{ $registration->student->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $registration->student->name }} {{ $registration->student->sername }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $registration->student->gender }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $registration->student->tell }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>Registration Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Major</th>
                        <th>Semester</th>
                        <th>Term</th>
                        <th>Year</th>
                        <th>Base Price</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registration->registrationDetails as $detail)
                        <tr>
                            <td>{{ $detail->major->name }}</td>
                            <td>{{ $detail->major->semester->name }}</td>
                            <td>{{ $detail->major->term->name }}</td>
                            <td>{{ $detail->major->year->name }}</td>
                            <td>{{ number_format($detail->detail_price, 2) }}</td>
                            <td>{{ $registration->pro }}% ({{ number_format($detail->detail_price - $detail->total_price, 2) }})</td>
                            <td>{{ number_format($detail->total_price, 2) }}</td>
                            <td>
                                @if(isset($majorPaymentStatuses[$detail->major_id]))
                                    @if($majorPaymentStatuses[$detail->major_id]['paid_directly'])
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> 
                                            Paid
                                            <a href="{{ route('payments.show', $majorPaymentStatuses[$detail->major_id]['payment_id']) }}" class="text-white">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </span>
                                    @elseif($registration->payment_status == 'success')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> 
                                            Paid via Registration
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> 
                                            Pending
                                        </span>
                                    @endif
                                @else
                                    @if($registration->payment_status == 'success')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> 
                                            Paid
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> 
                                            Pending
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Total</th>
                        <th>{{ number_format($registration->registrationDetails->sum('total_price'), 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($registration->payment_proof)
<div class="card mb-4">
    <div class="card-header">
        <h5>Payment Proof</h5>
    </div>
    <div class="card-body text-center">
        <img src="{{ asset('storage/' . $registration->payment_proof) }}" alt="Payment Proof" class="img-fluid img-thumbnail" style="max-height: 400px;">
    </div>
</div>
@endif

@if($registration->payment_status == 'pending')
<div class="mb-4">
    <form action="{{ route('registrations.confirm-payment', $registration->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle me-1"></i> Confirm Payment
        </button>
    </form>
</div>
@endif

<div class="d-flex justify-content-end">
    <button class="btn btn-danger me-2" onclick="confirmDelete('delete-registration-form-{{ $registration->id }}')">
        <i class="fas fa-trash"></i> Delete Registration
    </button>
    <form id="delete-registration-form-{{ $registration->id }}" action="{{ route('registrations.destroy', $registration->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
