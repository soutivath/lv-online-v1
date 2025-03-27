@extends('Dashboard.layout')

@section('title', 'Payment Details')

@section('page-title', $isGrouped ? 'Group Payment Details' : 'Payment Details')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('payments.export-pdf', $payment->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header {{ $isGrouped ? 'bg-info text-white' : '' }}">
                <h5>
                    @if($isGrouped)
                    <i class="fas fa-layer-group me-2"></i>Group Payment Information
                    @else
                    Payment Information
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <table class="table">
                    @if($payment->bill_number)
                    <tr>
                        <th style="width: 150px">Bill Number</th>
                        <td>
                            {{ $payment->bill_number }}
                            @if($isGrouped)
                                <span class="badge bg-info">Group ({{ $relatedPayments->count() + 1 }} items)</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th style="width: 150px">Payment ID</th>
                        <td>{{ $payment->id }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if(!$isGrouped)
                    <tr>
                        <th>Major</th>
                        <td>{{ $payment->major->name }}</td>
                    </tr>
                    <tr>
                        <th>Base Amount</th>
                        <td>{{ number_format($payment->detail_price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Discount</th>
                        <td>{{ $payment->pro }}% ({{ number_format($payment->detail_price - $payment->total_price, 2) }})</td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <td>{{ number_format($payment->total_price, 2) }}</td>
                    </tr>
                    @endif
                    @if($isGrouped)
                    <tr>
                        <th>Group Total</th>
                        <td class="fw-bold fs-5">{{ number_format($groupTotal, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($payment->status == 'pending')
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
                        @if($payment->student->picture)
                            <img src="{{ asset('storage/' . $payment->student->picture) }}" alt="Student Picture" class="img-thumbnail" style="max-height: 150px">
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
                        <td>{{ $payment->student->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>{{ $payment->student->gender }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $payment->student->tell }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($isGrouped)
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Items in this Payment Group</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Major</th>
                        <th>Semester</th>
                        <th>Term</th>
                        <th>Year</th>
                        <th>Base Price</th>
                        <th>Discount</th>
                        <th>Final Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Include the current payment as the first item -->
                    <tr class="table-active">
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->major->name }}</td>
                        <td>{{ $payment->major->semester->name }}</td>
                        <td>{{ $payment->major->term->name }}</td>
                        <td>{{ $payment->major->year->name }}</td>
                        <td>{{ number_format($payment->detail_price, 2) }}</td>
                        <td>{{ $payment->pro }}% ({{ number_format($payment->detail_price - $payment->total_price, 2) }})</td>
                        <td>{{ number_format($payment->total_price, 2) }}</td>
                        <td>
                            @if($payment->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-success">Success</span>
                            @endif
                        </td>
                    </tr>
                    <!-- Include related payments -->
                    @foreach($relatedPayments as $relatedPayment)
                    <tr>
                        <td>
                            <a href="{{ route('payments.show', $relatedPayment->id) }}">
                                {{ $relatedPayment->id }}
                            </a>
                        </td>
                        <td>{{ $relatedPayment->major->name }}</td>
                        <td>{{ $relatedPayment->major->semester->name }}</td>
                        <td>{{ $relatedPayment->major->term->name }}</td>
                        <td>{{ $relatedPayment->major->year->name }}</td>
                        <td>{{ number_format($relatedPayment->detail_price, 2) }}</td>
                        <td>{{ $relatedPayment->pro }}% ({{ number_format($relatedPayment->detail_price - $relatedPayment->total_price, 2) }})</td>
                        <td>{{ number_format($relatedPayment->total_price, 2) }}</td>
                        <td>
                            @if($relatedPayment->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @else
                                <span class="badge bg-success">Success</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">Group Total:</th>
                        <th colspan="2">{{ number_format($groupTotal, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif

@if($payment->payment_proof)
<div class="card mb-4">
    <div class="card-header">
        <h5>Payment Proof</h5>
    </div>
    <div class="card-body text-center">
        <img src="{{ asset('storage/' . $payment->payment_proof) }}" alt="Payment Proof" class="img-fluid img-thumbnail" style="max-height: 400px;">
    </div>
</div>
@endif

@if(($isGrouped && $relatedPayments->where('status', 'pending')->count() > 0) || (!$isGrouped && $payment->status == 'pending'))
<div class="mb-4">
    <form action="{{ route('payments.confirm', $payment->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle me-1"></i> 
            @if($isGrouped)
                Confirm All Payments in Group
            @else
                Confirm Payment
            @endif
        </button>
    </form>
</div>
@endif

<div class="d-flex justify-content-end">
    <button class="btn btn-danger" onclick="return confirmDelete('delete-payment-form-{{ $payment->id }}')">
        <i class="fas fa-trash"></i> Delete Payment
    </button>
    <form id="delete-payment-form-{{ $payment->id }}" action="{{ route('payments.destroy', $payment->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>

@endsection

@section('scripts')
<script>
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
