@extends('Dashboard.layout')

@section('title', 'Registrations')

@section('page-title', 'Student Registrations')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('registrations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Registration
        </a>
        <a href="{{ route('registrations.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export All
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Major(s)</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
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
                                                <span class="badge bg-secondary">+{{ $registration->registrationDetails->count() - 2 }} more</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="badge bg-secondary">None</span>
                                @endif
                            </td>
                            <td>{{ $registration->pro }}%</td>
                            <td>
                                {{ number_format($registration->registrationDetails->sum('total_price'), 2) }}
                            </td>
                            <td>
                                @if($registration->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-success">Success</span>
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
                                        <button type="submit" class="btn btn-sm btn-primary" title="Confirm Payment">
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
