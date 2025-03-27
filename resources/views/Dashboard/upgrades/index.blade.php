@extends('Dashboard.layout')

@section('title', 'Grade Upgrades')

@section('page-title', 'Grade Upgrades')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('upgrades.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Upgrade
        </a>
        <a href="{{ route('upgrades.export-all-pdf') }}" class="btn btn-success" target="_blank">
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
                        <th>Major</th>
                        <th>Subjects</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upgrades as $upgrade)
                        <tr>
                            <td>{{ $upgrade->id }}</td>
                            <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                            <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                            <td>{{ $upgrade->major->name }}</td>
                            <td>{{ $upgrade->upgradeDetails->count() }}</td>
                            <td>{{ number_format($upgrade->upgradeDetails->sum('total_price'), 2) }}</td>
                            <td>
                                @if($upgrade->payment_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-success">Success</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('upgrades.show', $upgrade->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                @if($upgrade->payment_status == 'pending')
                                    <form action="{{ route('upgrades.confirm-payment', $upgrade->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary" title="Confirm Payment">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-upgrade-form-{{ $upgrade->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-upgrade-form-{{ $upgrade->id }}" action="{{ route('upgrades.destroy', $upgrade->id) }}" method="POST" class="d-none">
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
