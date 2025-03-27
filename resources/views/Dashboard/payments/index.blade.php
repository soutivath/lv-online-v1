@extends('Dashboard.layout')

@section('title', 'Payments')

@section('page-title', 'Student Payments')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('payments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Payment
        </a>
        <a href="{{ route('payments.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export All
        </a>
    </div>
@endsection

@section('content')
@if(count($groupedPayments) > 0)
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>Grouped Payments</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Bill #</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedPayments as $group)
                        <tr>
                            <td>{{ substr($group['payment']->bill_number, 0, 12) }}...</td>
                            <td>{{ $group['payment']->student->name }} {{ $group['payment']->student->sername }}</td>
                            <td>{{ \Carbon\Carbon::parse($group['payment']->date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-info">{{ $group['count'] }} items</span>
                            </td>
                            <td class="fw-bold">{{ number_format($group['total'], 2) }}</td>
                            <td>
                                @if($group['all_success'])
                                    <span class="badge bg-success">Success</span>
                                @elseif($group['all_pending'])
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-info">Partial</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $group['payment']->id) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i> View Group
                                </a>
                                
                                <a href="{{ route('payments.export-pdf', $group['payment']->id) }}" class="btn btn-sm btn-success" target="_blank" title="Export PDF">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                
                                @if($group['all_pending'])
                                    <form action="{{ route('payments.confirm', $group['payment']->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" title="Confirm All Payments">
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
        <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Individual Payments</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Major</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($individualPayments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                            <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                            <td>{{ $payment->major->name }}</td>
                            <td>{{ number_format($payment->total_price, 2) }}</td>
                            <td>
                                @if($payment->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-success">Success</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('payments.export-pdf', $payment->id) }}" class="btn btn-sm btn-success" target="_blank" title="Export PDF">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                @if($payment->status == 'pending')
                                    <form action="{{ route('payments.confirm', $payment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" title="Confirm Payment">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-payment-form-{{ $payment->id }}')" title="Delete Payment">
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
                            <td colspan="7" class="text-center">No individual payments found</td>
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
