@extends('Dashboard.layout')

@section('title', 'Students')

@section('page-title')
    @if(isset($majorFilter))
        Students in Major: {{ $majorFilter }}
    @else
        Students
    @endif
@endsection

@section('page-actions')
    <div class="btn-group" role="group">
        @if(isset($majorFilter))
            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Clear Filter
            </a>
        @endif
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-plus"></i> Add Student
        </button>
        <a href="{{ route('students.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export All
        </a>
    </div>
@endsection

@section('content')
<!-- Search Box -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('students.index') }}" method="GET">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by ID, name, surname or email..." name="search" value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Birthday</th>
                        <th>Gender</th>
                        <th>Nationality</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->sername }}</td>
                            <td>{{ $student->user ? $student->user->email : 'No account' }}</td>
                            <td>{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</td>
                            <td>{{ $student->gender }}</td>
                            <td>{{ $student->nationality }}</td>
                            <td>{{ $student->tell }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-btn" data-bs-toggle="modal" data-bs-target="#viewStudentModal{{ $student->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('students.export-pdf', $student->id) }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-student-form-{{ $student->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-student-form-{{ $student->id }}" action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-none">
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

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3">Personal Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label for="sername" class="form-label">Surname</label>
                            <input type="text" class="form-control" id="sername" name="sername" required maxlength="20">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="birthday" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" required>
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality" required maxlength="10">
                        </div>
                        <div class="col-md-6">
                            <label for="tell" class="form-label">Phone Number</label>
                            <input type="number" class="form-control" id="tell" name="tell" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required maxlength="50"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="add_picture" name="picture" accept="image/*">
                            <div class="file-name mt-1 text-muted small"></div>
                            <div class="picture-preview mt-2" style="display: none;"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_scope_document" class="form-label">Score Document</label>
                            <input type="file" class="form-control" id="add_scope_document" name="score" accept="image/*">
                            <div class="file-name mt-1 text-muted small"></div>
                            <div class="document-preview mt-2" style="display: none;"></div>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Login Account</h6>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
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

<!-- Edit & View Student Modals -->
@foreach($students as $student)
    <!-- View Student Modal -->
    <div class="modal fade" id="viewStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="viewStudentModalLabel{{ $student->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStudentModalLabel{{ $student->id }}">View Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6 text-center mb-3">
                            @if($student->picture)
                                <img src="{{ asset('storage/' . $student->picture) }}" alt="Student Picture" class="img-thumbnail" style="max-height: 200px">
                            @else
                                <div class="border p-3 text-center">
                                    <i class="fas fa-user fa-5x text-secondary"></i>
                                    <p class="mt-2">No picture available</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-center mb-3">
                            @if($student->score)
                                <img src="{{ asset('storage/' . $student->score) }}" alt="Student Score" class="img-thumbnail" style="max-height: 200px">
                            @else
                                <div class="border p-3 text-center">
                                    <i class="fas fa-file fa-5x text-secondary"></i>
                                    <p class="mt-2">No score document available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Personal Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p><strong>ID:</strong> {{ $student->id }}</p>
                            <p><strong>Name:</strong> {{ $student->name }}</p>
                            <p><strong>Surname:</strong> {{ $student->sername }}</p>
                            <p><strong>Birthday:</strong> {{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p><strong>Gender:</strong> {{ $student->gender }}</p>
                            <p><strong>Nationality:</strong> {{ $student->nationality }}</p>
                            <p><strong>Phone:</strong> {{ $student->tell }}</p>
                            <p><strong>Address:</strong> {{ $student->address }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">Login Account</h6>
                    <p><strong>Email:</strong> {{ $student->user ? $student->user->email : 'No account' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" aria-labelledby="editStudentModalLabel{{ $student->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStudentModalLabel{{ $student->id }}">Edit Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">Personal Information</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_name{{ $student->id }}" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_name{{ $student->id }}" name="name" value="{{ $student->name }}" required maxlength="20">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_sername{{ $student->id }}" class="form-label">Surname</label>
                                <input type="text" class="form-control" id="edit_sername{{ $student->id }}" name="sername" value="{{ $student->sername }}" required maxlength="20">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_birthday{{ $student->id }}" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="edit_birthday{{ $student->id }}" name="birthday" value="{{ $student->birthday }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_gender{{ $student->id }}" class="form-label">Gender</label>
                                <select class="form-select" id="edit_gender{{ $student->id }}" name="gender" required>
                                    <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_nationality{{ $student->id }}" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="edit_nationality{{ $student->id }}" name="nationality" value="{{ $student->nationality }}" required maxlength="10">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_tell{{ $student->id }}" class="form-label">Phone Number</label>
                                <input type="number" class="form-control" id="edit_tell{{ $student->id }}" name="tell" value="{{ $student->tell }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_address{{ $student->id }}" class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address{{ $student->id }}" name="address" rows="3" required maxlength="50">{{ $student->address }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_picture{{ $student->id }}" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="edit_picture{{ $student->id }}" name="picture" accept="image/*">
                                <div class="file-name mt-1 text-muted small"></div>
                                <div class="picture-preview mt-2" style="display: none;"></div>
                                @if($student->picture)
                                    <div class="mt-2 current-image">
                                        <img src="{{ asset('storage/' . $student->picture) }}" alt="Current Picture" class="img-thumbnail" style="max-height: 100px;">
                                        <span class="text-muted small d-block">Current picture</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_scope_document{{ $student->id }}" class="form-label">Score Document</label>
                                <input type="file" class="form-control" id="edit_scope_document{{ $student->id }}" name="score" accept="image/*">
                                <div class="file-name mt-1 text-muted small"></div>
                            <div class="document-preview mt-2" style="display: none;"></div>
                                @if($student->score)
                                <div class="mt-2 current-score-document">
                                    <img src="{{ asset('storage/' . $student->score) }}" alt="Current score image" class="img-thumbnail" style="max-height: 100px;">
                                    <span class="text-muted small d-block">Current score image</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <h6 class="mb-3">Login Account</h6>
                        <div class="mb-3">
                            <label for="edit_email{{ $student->id }}" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="edit_email{{ $student->id }}" name="email" value="{{ $student->user ? $student->user->email : '' }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_password{{ $student->id }}" class="form-label">Password {{ $student->user ? '(Leave blank to keep current)' : '' }}</label>
                                <input type="password" class="form-control" id="edit_password{{ $student->id }}" name="password" {{ $student->user ? '' : 'required' }}>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_password_confirmation{{ $student->id }}" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="edit_password_confirmation{{ $student->id }}" name="password_confirmation" {{ $student->user ? '' : 'required' }}>
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

<!-- Document Preview Modal -->
<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentPreviewModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Document Preview" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Reset form and clear previews when any modal is closed
        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
            $(this).find('.picture-preview, .document-preview').empty().hide();
            $(this).find('.file-name').text('');
        });

        // Handle file input changes for profile pictures
        $('input[type="file"][name="picture"]').change(function() {
            const file = this.files[0];
            const preview = $(this).closest('.mb-3').find('.picture-preview');
            const fileName = $(this).closest('.mb-3').find('.file-name');
            
            if (file) {
                fileName.text(file.name);
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.html('<img src="' + e.target.result + '" class="img-thumbnail mt-2" style="max-height: 150px;">');
                        preview.show();
                    }
                    
                    reader.readAsDataURL(file);
                }
            } else {
                preview.empty().hide();
                fileName.text('');
            }
        });

        // Handle file input changes for scope documents
        $('input[type="file"][name="score"]').change(function() {
            const file = this.files[0];
            const preview = $(this).closest('.mb-3').find('.document-preview');
            const fileName = $(this).closest('.mb-3').find('.file-name');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Check if the file is an image
                    if (file.type.startsWith('image/')) {
                        preview.html('<img src="' + e.target.result + '" class="img-thumbnail mt-2" style="max-height: 200px;">');
                    } else {
                        preview.html('<div class="alert alert-info mt-2">File selected: ' + file.name + '</div>');
                    }
                    preview.show();
                };

                reader.readAsDataURL(file); // Read the file as a data URL
                fileName.text(file.name);
            } else {
                preview.empty().hide();
                fileName.text('');
            }
        });

        // Add form submission validation for file size
        $('form').submit(function(e) {
            let isValid = true;
            
            // Check file size if a file is selected
            $(this).find('input[type="file"]').each(function() {
                if (this.files.length > 0 && this.files[0].size > 5 * 1024 * 1024) { // 5MB
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large',
                        text: 'Please select a file smaller than 5MB.'
                    });
                    isValid = false;
                    return false; // Break the loop
                }
            });
            
            return isValid;
        });

        // If there are any references to scope_document in DataTables or elsewhere,
        // they should be renamed to score
        
        // For example, if there's a column definition for displaying document status:
        /*
        {
            data: 'score',
            name: 'score',
            render: function(data) {
                if (data) {
                    return '<span class="badge bg-success">Uploaded</span>';
                } else {
                    return '<span class="badge bg-secondary">Not uploaded</span>';
                }
            }
        },
        */
    });

    // Add this to your existing JavaScript
    function previewDocument(url) {
        // Set the image source
        document.getElementById('documentPreviewImage').src = url;
        
        // Show the modal
        const previewModal = new bootstrap.Modal(document.getElementById('documentPreviewModal'));
        previewModal.show();
    }
    
    // For each "View" button in the student table
    document.querySelectorAll('.view-document-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const documentUrl = this.getAttribute('data-document-url');
            previewDocument(documentUrl);
        });
    });
    
    // Modify your DataTables columns to include the view button with data attribute
    $(document).ready(function() {
        // ...existing DataTables initialization...
        
        // If you have a column that renders document links, modify it like this:
        /*
        {
            data: 'score',
            name: 'score',
            render: function(data, type, row) {
                if (data) {
                    return '<button class="btn btn-sm btn-info view-document-btn" data-document-url="' + 
                           '{{ asset("storage") }}/' + data + '"><i class="fas fa-eye"></i> View</button>';
                } else {
                    return '<span class="badge bg-secondary">No document</span>';
                }
            }
        }
        */
    });
</script>

@endsection