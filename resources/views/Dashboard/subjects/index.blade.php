@extends('Dashboard.layout')

@section('title', 'Subjects')

@section('page-title', 'Subjects')

@section('page-actions')
    {{-- <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" id="headerAddSubjectBtn">
            <i class="fas fa-plus"></i> Add Subject
        </button>
        <a href="{{ route('subjects.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div> --}}
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Subjects</h5>
        <div>
            <button type="button" class="btn btn-primary btn-sm btn-add-subject">
                <i class="fas fa-plus"></i> Add New
            </button>
            <a href="{{ route('subjects.export-all-pdf') }}" class="btn btn-success btn-sm" target="_blank">
                <i class="fas fa-file-pdf"></i> Export All
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Credit</th>
                        <th>Credit Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr>
                            <td>{{ $subject->id }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->credit->qty }}</td>
                            <td>{{ number_format($subject->credit->price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-subject-form-{{ $subject->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-subject-form-{{ $subject->id }}" action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="d-none">
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

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="credit_id" class="form-label">Credit</label>
                        <select class="form-select" id="credit_id" name="credit_id" required>
                            <option value="">Select Credit</option>
                            @foreach($credits as $credit)
                                <option value="{{ $credit->id }}">{{ $credit->qty }} Credits - Price: {{ number_format($credit->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Subject Modals -->
@foreach($subjects as $subject)
    <div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-labelledby="editSubjectModalLabel{{ $subject->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubjectModalLabel{{ $subject->id }}">Edit Subject</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name{{ $subject->id }}" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="edit_name{{ $subject->id }}" name="name" value="{{ $subject->name }}" required maxlength="20">
                        </div>
                        <div class="mb-3">
                            <label for="edit_credit_id{{ $subject->id }}" class="form-label">Credit</label>
                            <select class="form-select" id="edit_credit_id{{ $subject->id }}" name="credit_id" required>
                                @foreach($credits as $credit)
                                    <option value="{{ $credit->id }}" {{ $subject->credit_id == $credit->id ? 'selected' : '' }}>
                                        {{ $credit->qty }} Credits - Price: {{ number_format($credit->price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Debug modal presence
        console.log('Modal element exists:', $('#addSubjectModal').length > 0);
        
        // Manual modal opener for Add New button and Add Subject button in header
        $('.btn-add-subject, #headerAddSubjectBtn').on('click', function() {
            console.log('Add subject button clicked');
            var modal = new bootstrap.Modal(document.getElementById('addSubjectModal'));
            modal.show();
        });
        
        // Initialize edit button events
        $('.edit-btn').on('click', function() {
            console.log('Edit button clicked');
            var targetId = $(this).data('bs-target');
            console.log('Target modal:', targetId);
        });
        
        // Show validation errors in modal if any
        @if($errors->any())
            var modal = new bootstrap.Modal(document.getElementById('addSubjectModal'));
            modal.show();
        @endif
    });
    
    // Additional confirmation function for subject deletion
    function confirmSubjectDelete(formId) {
        if (confirm('Are you sure you want to delete this subject?')) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endpush
