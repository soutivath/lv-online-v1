@extends('Dashboard.layout')

@section('title', 'Majors')

@section('page-title', 'Manage Majors')

@section('page-actions')
    {{-- <div class="btn-group" role="group">
        <a href="{{ route('majors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Major
        </a>
        <a href="{{ route('majors.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div> --}}
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Majors</h5>
        <div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createMajorModal">
                <i class="fas fa-plus"></i> Add New
            </button>
            <a href="{{ route('majors.export-all-pdf') }}" class="btn btn-success btn-sm" target="_blank">
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
                        <th>Semester</th>
                        <th>Term</th>
                        <th>Year</th>
                        <th>Tuition Fee</th>
                        <th>Sokhn</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($majors as $major)
                        <tr>
                            <td>{{ $major->id }}</td>
                            <td>{{ $major->name }}</td>
                            <td>{{ $major->semester->name }}</td>
                            <td>{{ $major->term->name }}</td>
                            <td>{{ $major->year->name }}</td>
                            <td>{{ number_format($major->tuition->price, 2) }}</td>
                            <td>{{ $major->sokhn }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('majors.show', $major->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editMajorModal{{ $major->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ route('majors.export-pdf', $major->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-major-form-{{ $major->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-major-form-{{ $major->id }}" action="{{ route('majors.destroy', $major->id) }}" method="POST" class="d-none">
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

<!-- Add Major Modal -->
<div class="modal fade" id="addMajorModal" tabindex="-1" aria-labelledby="addMajorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('majors.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMajorModalLabel">Add New Major</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="15">
                    </div>
                    <div class="mb-3">
                        <label for="semester_id" class="form-label">Semester</label>
                        <select class="form-select" id="semester_id" name="semester_id" required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="term_id" class="form-label">Term</label>
                        <select class="form-select" id="term_id" name="term_id" required>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="year_id" class="form-label">Year</label>
                        <select class="form-select" id="year_id" name="year_id" required>
                            <option value="">Select Year</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tuition_id" class="form-label">Tuition</label>
                        <select class="form-select" id="tuition_id" name="tuition_id" required>
                            <option value="">Select Tuition</option>
                            @foreach($tuitions as $tuition)
                                <option value="{{ $tuition->id }}">{{ number_format($tuition->price, 2) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sokhn" class="form-label">Sokhn</label>
                        <input type="text" class="form-control" id="sokhn" name="sokhn" required maxlength="12">
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

<!-- Create Major Modal -->
<div class="modal fade" id="createMajorModal" tabindex="-1" aria-labelledby="createMajorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMajorModalLabel">Create New Major</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('majors.store') }}" method="POST" id="createMajorForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Major Name</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="15">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="sokhn" class="form-label">Major Code</label>
                            <input type="text" class="form-control" id="sokhn" name="sokhn" required maxlength="12">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="semester_id" class="form-label">Semester</label>
                            <select class="form-select" id="semester_id" name="semester_id" required>
                                <option value="">Select Semester</option>
                                @foreach(\App\Models\Semester::all() as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="term_id" class="form-label">Term</label>
                            <select class="form-select" id="term_id" name="term_id" required>
                                <option value="">Select Term</option>
                                @foreach(\App\Models\Term::all() as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="year_id" class="form-label">Academic Year</label>
                            <select class="form-select" id="year_id" name="year_id" required>
                                <option value="">Select Year</option>
                                @foreach(\App\Models\Year::all() as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tuition_id" class="form-label">Tuition Fee</label>
                            <select class="form-select" id="tuition_id" name="tuition_id" required>
                                <option value="">Select Tuition Fee</option>
                                @foreach(\App\Models\Tuition::all() as $tuition)
                                    <option value="{{ $tuition->id }}">{{ number_format($tuition->price, 2) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitMajorBtn">Create Major</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Major Modals -->
@foreach($majors as $major)
    <div class="modal fade" id="editMajorModal{{ $major->id }}" tabindex="-1" aria-labelledby="editMajorModalLabel{{ $major->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('majors.update', $major->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMajorModalLabel{{ $major->id }}">Edit Major</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_name{{ $major->id }}" class="form-label">Major Name</label>
                                <input type="text" class="form-control" id="edit_name{{ $major->id }}" name="name" value="{{ $major->name }}" required maxlength="15">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="edit_sokhn{{ $major->id }}" class="form-label">Major Code</label>
                                <input type="text" class="form-control" id="edit_sokhn{{ $major->id }}" name="sokhn" value="{{ $major->sokhn }}" required maxlength="12">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_semester_id{{ $major->id }}" class="form-label">Semester</label>
                                <select class="form-select" id="edit_semester_id{{ $major->id }}" name="semester_id" required>
                                    @foreach($semesters as $semester)
                                        <option value="{{ $semester->id }}" {{ $major->semester_id == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="edit_term_id{{ $major->id }}" class="form-label">Term</label>
                                <select class="form-select" id="edit_term_id{{ $major->id }}" name="term_id" required>
                                    @foreach($terms as $term)
                                        <option value="{{ $term->id }}" {{ $major->term_id == $term->id ? 'selected' : '' }}>
                                            {{ $term->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="edit_year_id{{ $major->id }}" class="form-label">Academic Year</label>
                                <select class="form-select" id="edit_year_id{{ $major->id }}" name="year_id" required>
                                    @foreach($years as $year)
                                        <option value="{{ $year->id }}" {{ $major->year_id == $year->id ? 'selected' : '' }}>
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_tuition_id{{ $major->id }}" class="form-label">Tuition Fee</label>
                                <select class="form-select" id="edit_tuition_id{{ $major->id }}" name="tuition_id" required>
                                    @foreach($tuitions as $tuition)
                                        <option value="{{ $tuition->id }}" {{ $major->tuition_id == $tuition->id ? 'selected' : '' }}>
                                            {{ number_format($tuition->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
<!-- Ensure jQuery is loaded before Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function confirmDelete(formId) {
        if (confirm('Are you sure you want to delete this major?')) {
            document.getElementById(formId).submit();
        }
    }
    
    $(document).ready(function() {
        console.log('jQuery is ready');
        
        // Test that Bootstrap modals are properly initialized
        $('.modal').each(function() {
            console.log('Found modal with ID:', this.id);
        });
        
        // Test that we can capture clicks on modal triggers
        $('[data-bs-toggle="modal"]').on('click', function() {
            console.log('Modal trigger clicked, target:', $(this).data('bs-target'));
        });
        
        // Submit form when the Create Major button is clicked
        $('#submitMajorBtn').click(function() {
            $('#createMajorForm').submit();
        });
        
        // Show validation errors in the modal if any
        @if($errors->any())
            $('#createMajorModal').modal('show');
        @endif

        // Add direct Bootstrap event listeners for modal debugging
        document.addEventListener('show.bs.modal', function (event) {
            console.log('Modal is about to show:', event.target.id);
        });
        
        document.addEventListener('shown.bs.modal', function (event) {
            console.log('Modal has been shown:', event.target.id);
        });
        
        document.addEventListener('hide.bs.modal', function (event) {
            console.log('Modal is about to hide:', event.target.id);
        });
        
        document.addEventListener('hidden.bs.modal', function (event) {
            console.log('Modal has been hidden:', event.target.id);
        });
    });
</script>
@endpush
