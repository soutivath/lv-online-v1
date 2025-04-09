@extends('Dashboard.layout')

@section('title', 'Create Student')

@section('page-title', 'Create New Student')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <h4 class="mb-3">Personal Information</h4>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">First Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="sername" class="form-label">Last Name</label>
                        <input type="text" class="form-control @error('sername') is-invalid @enderror" id="sername" name="sername" value="{{ old('sername') }}" required>
                        @error('sername')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="ຊາຍ" {{ old('gender') == 'ຊາຍ' ? 'selected' : '' }}>ຊາຍ</option>
                            <option value="ຍິງ" {{ old('gender') == 'ຍິງ' ? 'selected' : '' }}>ຍິງ</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday') }}" required>
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', 'ລາວ') }}" required>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Contact Information and Account -->
                <div class="col-md-6">
                    <h4 class="mb-3">Contact & Account Information</h4>
                    
                    <div class="mb-3">
                        <label for="tell" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('tell') is-invalid @enderror" id="tell" name="tell" value="{{ old('tell') }}" required>
                        @error('tell')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        <small class="form-text text-muted">This will be used for login.</small>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        <small class="form-text text-muted">Minimum 6 characters.</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Document Uploads Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h4 class="mb-3">Profile Picture</h4>
                    <div class="mb-3">
                        <label for="picture" class="form-label">Student Photo</label>
                        <input type="file" class="form-control @error('picture') is-invalid @enderror" id="picture" name="picture" accept="image/*">
                        <small class="form-text text-muted">Optional. Max size: 2MB.</small>
                        @error('picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="picture-preview mb-3" style="display: none;">
                        <img id="preview" src="#" alt="Picture Preview" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4 class="mb-3">Academic Documents</h4>
                    <div class="mb-3">
                        <label for="score" class="form-label">Score Document</label>
                        <input type="file" class="form-control @error('score') is-invalid @enderror" id="score" name="score" accept="image/*">
                        <small class="form-text text-muted">Upload academic score as an image file only. Max size: 2MB.</small>
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="score-preview mb-3" style="display: none;">
                        <img id="score-preview" src="#" alt="Score Preview" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Create Student</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@section('scripts')
<script>
    // Image preview for profile picture
    document.getElementById('picture').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewDiv = document.querySelector('.picture-preview');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.style.display = 'block';
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            previewDiv.style.display = 'none';
        }
    });
    
    // Image preview for score document
    document.getElementById('score').addEventListener('change', function(e) {
        const preview = document.getElementById('score-preview');
        const previewDiv = document.querySelector('.score-preview');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result; // Set the image source to the file's data URL
                previewDiv.style.display = 'block'; // Show the preview container
            }
            
            reader.readAsDataURL(this.files[0]); // Read the file as a data URL
        } else {
            previewDiv.style.display = 'none'; // Hide the preview container if no file is selected
        }
    });
</script>
@endsection
