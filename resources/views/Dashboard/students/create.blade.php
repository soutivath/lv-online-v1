@extends('Dashboard.layout')

@section('title', 'ສ້າງນັກສຶກສາ')

@section('page-title', 'ສ້າງນັກສຶກສາໃໝ່')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <h4 class="mb-3">ຂໍ້ມູນສ່ວນຕົວ<br/><span style="font-size: 0.8em;">Personal Information</span></h4>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">ຊື່<br/><span style="font-size: 0.8em;">First Name</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="sername" class="form-label">ນາມສະກຸນ<br/><span style="font-size: 0.8em;">Last Name</span></label>
                        <input type="text" class="form-control @error('sername') is-invalid @enderror" id="sername" name="sername" value="{{ old('sername') }}" required>
                        @error('sername')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">ເພດ<br/><span style="font-size: 0.8em;">Gender</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">ເລືອກເພດ</option>
                            <option value="ຊາຍ" {{ old('gender') == 'ຊາຍ' ? 'selected' : '' }}>ຊາຍ</option>
                            <option value="ຍິງ" {{ old('gender') == 'ຍິງ' ? 'selected' : '' }}>ຍິງ</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="birthday" class="form-label">ວັນເດືອນປີເກີດ<br/><span style="font-size: 0.8em;">Birthday</span></label>
                        <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday') }}" required>
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nationality" class="form-label">ສັນຊາດ<br/><span style="font-size: 0.8em;">Nationality</span></label>
                        <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', 'ລາວ') }}" required>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Contact Information and Account -->
                <div class="col-md-6">
                    <h4 class="mb-3">ຂໍ້ມູນຕິດຕໍ່ & ບັນຊີ<br/><span style="font-size: 0.8em;">Contact & Account Information</span></h4>
                    
                    <div class="mb-3">
                        <label for="tell" class="form-label">ເບີໂທລະສັບ<br/><span style="font-size: 0.8em;">Phone Number</span></label>
                        <input type="text" class="form-control @error('tell') is-invalid @enderror" id="tell" name="tell" value="{{ old('tell') }}" required>
                        @error('tell')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">ທີ່ຢູ່<br/><span style="font-size: 0.8em;">Address</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">ອີເມວ<br/><span style="font-size: 0.8em;">Email Address</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        <small class="form-text text-muted">ຈະໃຊ້ສຳລັບການເຂົ້າສູ່ລະບົບ</small>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">ລະຫັດຜ່ານ<br/><span style="font-size: 0.8em;">Password</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        <small class="form-text text-muted">ຢ່າງນ້ອຍ 6 ຕົວອັກສອນ</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Document Uploads Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h4 class="mb-3">ຮູບພາບ<br/><span style="font-size: 0.8em;">Profile Picture</span></h4>
                    <div class="mb-3">
                        <label for="picture" class="form-label">ຮູບນັກສຶກສາ<br/><span style="font-size: 0.8em;">Student Photo</span></label>
                        <input type="file" class="form-control @error('picture') is-invalid @enderror" id="picture" name="picture" accept="image/*">
                        <small class="form-text text-muted">ບໍ່ຈຳເປັນ. ຂະໜາດສູງສຸດ: 2MB</small>
                        @error('picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="picture-preview mb-3" style="display: none;">
                        <img id="preview" src="#" alt="ຕົວຢ່າງຮູບພາບ" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4 class="mb-3">ເອກະສານການສຶກສາ<br/><span style="font-size: 0.8em;">Academic Documents</span></h4>
                    <div class="mb-3">
                        <label for="score" class="form-label">ເອກະສານຄະແນນ<br/><span style="font-size: 0.8em;">Score Document</span></label>
                        <input type="file" class="form-control @error('score') is-invalid @enderror" id="score" name="score" accept="image/*">
                        <small class="form-text text-muted">ອັບໂຫລດເອກະສານຄະແນນເປັນໄຟລ໌ຮູບພາບເທົ່ານັ້ນ. ຂະໜາດສູງສຸດ: 2MB</small>
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="score-preview mb-3" style="display: none;">
                        <img id="score-preview" src="#" alt="ຕົວຢ່າງເອກະສານຄະແນນ" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">ສ້າງນັກສຶກສາ</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">ຍົກເລີກ</a>
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
