@extends('Dashboard.layout')

@section('title', 'ພະນັກງານ')

@push('styles')
<style>
    body, h1, h2, h3, h4, h5, h6, p, span, div, button, input, select, textarea, label, a, th, td {
        font-family: 'Phetsarath OT', sans-serif !important;
    }
    .btn {
        font-family: 'Phetsarath OT', sans-serif !important;
    }
    ::placeholder {
        font-family: 'Phetsarath OT', sans-serif !important;
    }
</style>
@endpush

@section('page-title', 'ພະນັກງານ')

@section('page-actions')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-plus"></i> ເພີ່ມພະນັກງານ
        </button>
        <a href="{{ route('employees.export-all-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກທັງໝົດ
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
                        <th>ຊື່</th>
                        <th>ນາມສະກຸນ</th>
                        <th>ອີເມລ</th>
                        <th>ເພດ</th>
                        <th>ເບີໂທ</th>
                        <th>ວັນທີເຂົ້າຮ່ວມ</th>
                        <th>ບົດບາດ</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->sername }}</td>
                            <td>{{ $employee->user ? $employee->user->email : 'ບໍ່ມີບັນຊີ' }}</td>
                            <td>{{ $employee->gender }}</td>
                            <td>{{ $employee->tell }}</td>
                            <td>{{ \Carbon\Carbon::parse($employee->date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $employee->role == 'admin' ? 'bg-primary' : 'bg-info' }}">
                                    {{ $employee->role == 'admin' ? 'ຜູ້ບໍລິຫານ' : 'ອາຈານ' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info view-btn" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal{{ $employee->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('employees.export-pdf', $employee->id) }}" class="btn btn-sm btn-success" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editEmployeeModal{{ $employee->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-employee-form-{{ $employee->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-employee-form-{{ $employee->id }}" action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-none">
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

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">ເພີ່ມພະນັກງານໃໝ່</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3">ຂໍ້ມູນສ່ວນຕົວ</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">ຊື່</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label for="sername" class="form-label">ນາມສະກຸນ</label>
                            <input type="text" class="form-control" id="sername" name="sername" required maxlength="20">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="birthday" class="form-label">ວັນເດືອນປີເກີດ</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">ວັນທີເຂົ້າຮ່ວມ</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="gender" class="form-label">ເພດ</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="Male">ຊາຍ</option>
                                <option value="Female">ຍິງ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tell" class="form-label">ເບີໂທລະສັບ</label>
                            <input type="number" class="form-control" id="tell" name="tell" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">ທີ່ຢູ່</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required maxlength="50"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="picture" class="form-label">ຮູບພາບ</label>
                        <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                        <small class="text-muted file-name"></small>
                        <div class="image-preview" style="display: none;"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">ບົດບາດ</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">ຜູ້ບໍລິຫານ</option>
                            <option value="teacher">ອາຈານ</option>
                        </select>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">ບັນຊີເຂົ້າສູ່ລະບົບ</h6>
                    <div class="mb-3">
                        <label for="email" class="form-label">ທີ່ຢູ່ອີເມລ</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">ລະຫັດຜ່ານ</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">ຢືນຢັນລະຫັດຜ່ານ</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
                    <button type="submit" class="btn btn-primary">ບັນທຶກ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit & View Employee Modals -->
@foreach($employees as $employee)
    <!-- View Employee Modal -->
    <div class="modal fade" id="viewEmployeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="viewEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewEmployeeModalLabel{{ $employee->id }}">ເບິ່ງພະນັກງານ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 text-center mb-3">
                            @if($employee->picture)
                                <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="img-thumbnail" style="max-height: 200px">
                            @else
                                <div class="border p-3 text-center">
                                    <i class="fas fa-user-tie fa-5x text-secondary"></i>
                                    <p class="mt-2">ບໍ່ມີຮູບພາບ</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <h6 class="mb-3">ຂໍ້ມູນສ່ວນຕົວ</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p><strong>ID:</strong> {{ $employee->id }}</p>
                            <p><strong>ຊື່:</strong> {{ $employee->name }}</p>
                            <p><strong>ນາມສະກຸນ:</strong> {{ $employee->sername }}</p>
                            <p><strong>ວັນເດືອນປີເກີດ:</strong> {{ \Carbon\Carbon::parse($employee->birthday)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p><strong>ເພດ:</strong> {{ $employee->gender }}</p>
                            <p><strong>ວັນທີເຂົ້າຮ່ວມ:</strong> {{ \Carbon\Carbon::parse($employee->date)->format('d/m/Y') }}</p>
                            <p><strong>ເບີໂທ:</strong> {{ $employee->tell }}</p>
                            <p><strong>ທີ່ຢູ່:</strong> {{ $employee->address }}</p>
                            <p><strong>ບົດບາດ:</strong> {{ $employee->role == 'admin' ? 'ຜູ້ບໍລິຫານ' : 'ອາຈານ' }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    <h6 class="mb-3">ບັນຊີເຂົ້າສູ່ລະບົບ</h6>
                    <p><strong>ອີເມລ:</strong> {{ $employee->user ? $employee->user->email : 'ບໍ່ມີບັນຊີ' }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel{{ $employee->id }}">ແກ້ໄຂພະນັກງານ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="mb-3">ຂໍ້ມູນສ່ວນຕົວ</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_name{{ $employee->id }}" class="form-label">ຊື່</label>
                                <input type="text" class="form-control" id="edit_name{{ $employee->id }}" name="name" value="{{ $employee->name }}" required maxlength="20">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_sername{{ $employee->id }}" class="form-label">ນາມສະກຸນ</label>
                                <input type="text" class="form-control" id="edit_sername{{ $employee->id }}" name="sername" value="{{ $employee->sername }}" required maxlength="20">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_birthday{{ $employee->id }}" class="form-label">ວັນເດືອນປີເກີດ</label>
                                <input type="date" class="form-control" id="edit_birthday{{ $employee->id }}" name="birthday" value="{{ $employee->birthday }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_date{{ $employee->id }}" class="form-label">ວັນທີເຂົ້າຮ່ວມ</label>
                                <input type="date" class="form-control" id="edit_date{{ $employee->id }}" name="date" value="{{ $employee->date }}" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_gender{{ $employee->id }}" class="form-label">ເພດ</label>
                                <select class="form-select" id="edit_gender{{ $employee->id }}" name="gender" required>
                                    <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>ຊາຍ</option>
                                    <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>ຍິງ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_tell{{ $employee->id }}" class="form-label">ເບີໂທລະສັບ</label>
                                <input type="number" class="form-control" id="edit_tell{{ $employee->id }}" name="tell" value="{{ $employee->tell }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_address{{ $employee->id }}" class="form-label">ທີ່ຢູ່</label>
                            <textarea class="form-control" id="edit_address{{ $employee->id }}" name="address" rows="3" required maxlength="50">{{ $employee->address }}</textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_picture{{ $employee->id }}" class="form-label">ຮູບພາບ</label>
                            <input type="file" class="form-control" id="edit_picture{{ $employee->id }}" name="picture" accept="image/*">
                            <small class="text-muted file-name"></small>
                            <div class="image-preview" style="display: none;"></div>
                            @if($employee->picture)
                                <div class="mt-2 current-image">
                                    <small>ຮູບປັດຈຸບັນ:</small>
                                    <img src="{{ asset('storage/' . $employee->picture) }}" alt="Employee Picture" class="img-thumbnail mt-1" style="max-height: 100px">
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_role{{ $employee->id }}" class="form-label">ບົດບາດ</label>
                            <select class="form-select" id="edit_role{{ $employee->id }}" name="role" required>
                                <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>ຜູ້ບໍລິຫານ</option>
                                <option value="teacher" {{ $employee->role == 'teacher' ? 'selected' : '' }}>ອາຈານ</option>
                            </select>
                        </div>
                        
                        <hr>
                        <h6 class="mb-3">ບັນຊີເຂົ້າສູ່ລະບົບ</h6>
                        <div class="mb-3">
                            <label for="edit_email{{ $employee->id }}" class="form-label">ທີ່ຢູ່ອີເມລ</label>
                            <input type="email" class="form-control" id="edit_email{{ $employee->id }}" name="email" value="{{ $employee->user ? $employee->user->email : '' }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_password{{ $employee->id }}" class="form-label">ລະຫັດຜ່ານ {{ $employee->user ? '(ປະໄວ້ຫວ່າງເພື່ອຮັກສາຄ່າປັດຈຸບັນ)' : '' }}</label>
                                <input type="password" class="form-control" id="edit_password{{ $employee->id }}" name="password" {{ $employee->user ? '' : 'required' }} minlength="8">
                            </div>
                            <div class="col-md-6">
                                <label for="edit_password_confirmation{{ $employee->id }}" class="form-label">ຢືນຢັນລະຫັດຜ່ານ</label>
                                <input type="password" class="form-control" id="edit_password_confirmation{{ $employee->id }}" name="password_confirmation" {{ $employee->user ? '' : 'required' }} minlength="8">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
                        <button type="submit" class="btn btn-primary">ອັບເດດ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add form submission debugging
        $('form').on('submit', function(e) {
            console.log('Form submitted', $(this).attr('action'));
            
            // Password validation - at least 8 characters
            const passwordField = $(this).find('input[name="password"]');
            if (passwordField.length && passwordField.val() && passwordField.val().length < 8) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Password Too Short',
                    text: 'Password must be at least 8 characters long'
                });
                return false;
            }
            
            // Check for file size - existing code
            let isValid = true;
            
            $('input[type="file"]').each(function() {
                if (this.files.length > 0 && this.files[0].size > 2 * 1024 * 1024) { // 2MB limit
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'File size should not exceed 2MB'
                    });
                    isValid = false;
                    return false; // Break the loop
                }
            });
            
            return isValid;
        });

        // Reset form and clear previews when any modal is closed
        $('.modal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
            $(this).find('.image-preview').empty().hide();
            $(this).find('.file-name').text('');
        });

        // Handle file input changes for pictures
        $('input[type="file"][name="picture"]').change(function() {
            const file = this.files[0];
            const preview = $(this).closest('.mb-3').find('.image-preview');
            const fileName = $(this).closest('.mb-3').find('.file-name');
            
            if (file) {
                fileName.text(file.name);
                
                // Show preview only for image files
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.html(`<img src="${e.target.result}" class="img-thumbnail mt-2" style="height: 150px">`);
                        preview.show();
                    }
                    
                    reader.readAsDataURL(file);
                } else {
                    preview.empty().hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please select an image file (JPEG, PNG, etc.)'
                    });
                    $(this).val(''); // Clear the input
                    fileName.text('');
                }
            } else {
                fileName.text('');
                preview.empty().hide();
            }
        });

        // Add form submission validation for file size
        $('form').submit(function(e) {
            let isValid = true;
            
            $('input[type="file"]').each(function() {
                if (this.files.length > 0 && this.files[0].size > 2 * 1024 * 1024) { // 2MB limit
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'File size should not exceed 2MB'
                    });
                    isValid = false;
                    return false; // Break the loop
                }
            });
            
            return isValid;
        });
    });
</script>
@endsection
