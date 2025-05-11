@extends('Dashboard.layout')

@section('title', 'ແກ້ໄຂຂໍ້ມູນນັກສຶກສາ')

@se                    <div class="mb-3">
                        <label for="address" class="form-label">ທີ່ຢູ່<br/><span style="font-size: 0.8em;">Address</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $student->address) }}</textarea>on('page-title', 'ແກ້ໄຂຂໍ້ມູນນັກສຶກສາ')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6">
                    <h4 class="mb-3">ຂໍ້ມູນສ່ວນຕົວ</h4>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">ຊື່<br/><span style="font-size: 0.8em;">First Name</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="sername" class="form-label">ນາມສະກຸນ<br/><span style="font-size: 0.8em;">Last Name</span></label>
                        <input type="text" class="form-control @error('sername') is-invalid @enderror" id="sername" name="sername" value="{{ old('sername', $student->sername) }}" required>
                        @error('sername')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="gender" class="form-label">ເພດ<br/><span style="font-size: 0.8em;">Gender</span></label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                            <option value="">ເລືອກເພດ</option>
                            <option value="ຊາຍ" {{ old('gender', $student->gender) == 'ຊາຍ' ? 'selected' : '' }}>ຊາຍ</option>
                            <option value="ຍິງ" {{ old('gender', $student->gender) == 'ຍິງ' ? 'selected' : '' }}>ຍິງ</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="birthday" class="form-label">ວັນເດືອນປີເກີດ<br/><span style="font-size: 0.8em;">Birthday</span></label>
                        <input type="date" class="form-control @error('birthday') is-invalid @enderror" id="birthday" name="birthday" value="{{ old('birthday', $student->birthday) }}" required>
                        @error('birthday')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nationality" class="form-label">ສັນຊາດ<br/><span style="font-size: 0.8em;">Nationality</span></label>
                        <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $student->nationality) }}" required>
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Contact Information and Account -->
                <div class="col-md-6">
                    <h4 class="mb-3">ຂໍ້ມູນການຕິດຕໍ່ & ບັນຊີ</h4>
                    
                    <div class="mb-3">
                        <label for="tell" class="form-label">ເບີໂທລະສັບ<br/><span style="font-size: 0.8em;">Phone Number</span></label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <img src="https://flagcdn.com/16x12/la.png" alt="Laos" style="margin-right: 5px;"> +85620
                            </span>
                            <input type="tel" class="form-control @error('tell') is-invalid @enderror" id="tell" name="tell" 
                                value="{{ old('tell', $student->tell ? substr($student->tell, 6) : '') }}"
                                pattern="[0-9]{8}" maxlength="8" inputmode="numeric" required>
                        </div>
                        <div class="form-text">ກະລຸນາປ້ອນເບີ Whatsapp</div>
                        @error('tell')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">ທີ່ຢູ່<br/><span style="font-size: 0.8em;">Address</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if($student->user)
                        <div class="mb-3">
                            <label for="email" class="form-label">ອີເມລ<br/><span style="font-size: 0.8em;">Email Address</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $student->user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">ລະຫັດຜ່ານ<br/><span style="font-size: 0.8em;">Password</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            <small class="form-text text-muted">ປະຫວ່າງໄວ້ຖ້າຕ້ອງການໃຊ້ລະຫັດຜ່ານເກົ່າ.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="mb-3">
                            <label for="email" class="form-label">ອີເມລ<br/><span style="font-size: 0.8em;">Email Address</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">ລະຫັດຜ່ານ<br/><span style="font-size: 0.8em;">Password</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            <small class="form-text text-muted">ຈຳເປັນສຳລັບການສ້າງບັນຊີຜູ້ໃຊ້ໃໝ່.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Document Uploads Section -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <h4 class="mb-3">ຮູບໂປຣຟາຍ</h4>
                    @if($student->picture)
                        <div class="mb-3">
                            <label class="form-label">ຮູບປັດຈຸບັນ<br/><span style="font-size: 0.8em;">Current Picture</span></label>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $student->picture) }}" alt="ຮູບປັດຈຸບັນ" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="picture" class="form-label">ອັບເດດຮູບພາບ<br/><span style="font-size: 0.8em;">Update Picture</span></label>
                        <input type="file" class="form-control @error('picture') is-invalid @enderror" id="picture" name="picture" accept="image/*">
                        <small class="form-text text-muted">ປະຫວ່າງໄວ້ຖ້າຕ້ອງການໃຊ້ຮູບປັດຈຸບັນ. ຂະໜາດສູງສຸດ: 2MB.</small>
                        @error('picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="picture-preview mb-3" style="display: none;">
                        <label class="form-label">ຕົວຢ່າງຮູບພາບໃໝ່<br/><span style="font-size: 0.8em;">New Picture Preview</span></label>
                        <div class="text-center">
                            <img id="preview" src="#" alt="Picture Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4 class="mb-3">ເອກະສານການສຶກສາ</h4>
                    @if($student->document_score)
                        <div class="mb-3">
                            <label class="form-label">ເອກະສານປັດຈຸບັນ<br/><span style="font-size: 0.8em;">Current Document</span></label>
                            @php
                                $extension = pathinfo(storage_path('app/public/' . $student->document_score), PATHINFO_EXTENSION);
                                $isPdf = in_array(strtolower($extension), ['pdf']);
                                $isDoc = in_array(strtolower($extension), ['doc', 'docx']);
                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                            @endphp
                            
                            <div class="text-center">
                                @if($isPdf)
                                    <a href="{{ asset('storage/' . $student->document_score) }}" target="_blank" class="btn btn-outline-danger">
                                        <i class="far fa-file-pdf me-2"></i> ເບິ່ງ PDF
                                    </a>
                                @elseif($isDoc)
                                    <a href="{{ asset('storage/' . $student->document_score) }}" class="btn btn-outline-primary">
                                        <i class="far fa-file-word me-2"></i> ດາວໂຫຼດເອກະສານ
                                    </a>
                                @elseif($isImage)
                                    <img src="{{ asset('storage/' . $student->document_score) }}" class="img-fluid img-thumbnail" alt="Document Score" style="max-height: 200px;">
                                @else
                                    <a href="{{ asset('storage/' . $student->document_score) }}" class="btn btn-outline-secondary">
                                        <i class="far fa-file-alt me-2"></i> ດາວໂຫຼດເອກະສານ
                                    </a>
                                @endif
                                <div class="mt-2 text-muted small">
                                    ເອກະສານປັດຈຸບັນ: {{ basename($student->document_score) }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i> ຍັງບໍ່ມີການອັບໂຫລດເອກະສານເທື່ອ.
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="document_score" class="form-label">ອັບເດດເອກະສານຄະແນນ<br/><span style="font-size: 0.8em;">Update Document Score</span></label>
                        <input type="file" class="form-control @error('document_score') is-invalid @enderror" id="document_score" name="document_score" accept="image/*">
                        <small class="form-text text-muted">ປະຫວ່າງໄວ້ຖ້າຕ້ອງການໃຊ້ເອກະສານປັດຈຸບັນ. ຮອງຮັບ: PDF, Word, ຮູບພາບ. ຂະໜາດສູງສຸດ: 5MB.</small>
                        @error('document_score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="document-preview" class="mb-3" style="display: none;">
                        <div class="alert alert-info">
                            <i class="fas fa-file-alt me-2"></i> ເອກະສານໃໝ່ທີ່ເລືອກ: <span id="document-name"></span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4 class="mb-3">ເອກະສານການສຶກສາ</h4>
                    @if($student->score)
                        <div class="mb-3">
                            <label class="form-label">ເອກະສານຄະແນນປັດຈຸບັນ</label>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $student->score) }}" class="img-fluid img-thumbnail" alt="ເອກະສານຄະແນນ" style="max-height: 200px;">
                                <div class="mt-2 text-muted small">
                                    ເອກະສານປັດຈຸບັນ: {{ basename($student->score) }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i> ຍັງບໍ່ມີການອັບໂຫລດເອກະສານຄະແນນເທື່ອ.
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="score" class="form-label">ອັບເດດເອກະສານຄະແນນ</label>
                        <input type="file" class="form-control @error('score') is-invalid @enderror" id="score" name="score" accept="image/*">
                        <small class="form-text text-muted">ປະຫວ່າງໄວ້ຖ້າຕ້ອງການໃຊ້ເອກະສານປັດຈຸບັນ. ອັບໂຫລດສະເພາະຮູບພາບເທົ່ານັ້ນ. ຂະໜາດສູງສຸດ: 2MB.</small>
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="score-preview" class="mb-3" style="display: none;">
                        <img id="score-image-preview" src="#" alt="ຕົວຢ່າງເອກະສານຄະແນນ" class="img-thumbnail" style="max-height: 200px;">
                        <div class="mt-2 text-muted">
                            ໄຟລ໌ທີ່ເລືອກ: <span id="score-name"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">ອັບເດດຂໍ້ມູນນັກສຶກສາ</button>
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary">ຍົກເລີກ</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Image preview
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
    
    // Document file preview
    document.getElementById('document_score').addEventListener('change', function(e) {
        const documentName = document.getElementById('document-name');
        const previewDiv = document.getElementById('document-preview');
        
        if (this.files && this.files[0]) {
            documentName.textContent = this.files[0].name;
            previewDiv.style.display = 'block';
        } else {
            previewDiv.style.display = 'none';
        }
    });

    // Score document preview
    document.getElementById('score').addEventListener('change', function(e) {
        const scoreName = document.getElementById('score-name');
        const scorePreview = document.getElementById('score-image-preview');
        const previewDiv = document.getElementById('score-preview');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            scoreName.textContent = this.files[0].name;
            
            reader.onload = function(e) {
                scorePreview.src = e.target.result;
                previewDiv.style.display = 'block';
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            previewDiv.style.display = 'none';
        }
    });
    
    // Phone number validation
    const phoneInput = document.getElementById('tell');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove any non-digit characters
            this.value = this.value.replace(/\D/g, '');
            
            // Limit to 8 characters
            if (this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
        });
    }
</script>
@endsection
