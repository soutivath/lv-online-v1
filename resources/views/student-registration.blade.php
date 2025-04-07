@extends('Admin.Layout')
@section('title', 'ລົງທະບຽນຮຽນ')
@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0">ລົງທະບຽນຮຽນ</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Step progress indicator -->
                    <div class="mb-5">
                        <div class="position-relative mb-5 pt-3 pb-5">
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" id="registration-progress"></div>
                            </div>
                            <div class="position-absolute top-0 start-0" style="transform: translateX(-10px) translateY(-10px);">
                                <button type="button" class="btn btn-primary btn-step rounded-circle active" disabled style="width: 3rem; height: 3rem;">1</button>
                                <div class="text-center mt-2 step-label" style="margin-left: -20px;">ຂໍ້ມູນນັກສຶກສາ</div>
                            </div>
                            <div class="position-absolute top-0" style="left: 50%; transform: translateX(-50%) translateY(-10px);">
                                <button type="button" class="btn btn-outline-primary btn-step rounded-circle" disabled style="width: 3rem; height: 3rem;">2</button>
                                <div class="text-center mt-2 step-label">ລົງທະບຽນ</div>
                            </div>
                            <div class="position-absolute top-0 end-0" style="transform: translateX(10px) translateY(-10px);">
                                <button type="button" class="btn btn-outline-primary btn-step rounded-circle" disabled style="width: 3rem; height: 3rem;">3</button>
                                <div class="text-center mt-2 step-label">ການຈ່າຍເງິນ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="studentRegistrationForm" action="{{ route('student.register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Step 1: Student Information -->
                        <div class="step" id="step1">
                            <h5 class="mb-4">ຂໍ້ມູນສ່ວນຕົວຂອງນັກສຶກສາ</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">ຊື່ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sername" class="form-label">ນາມສະກຸນ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sername" name="sername" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="birthday" class="form-label">ວັນເດືອນປີເກີດ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="birthday" name="birthday" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">ເພດ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="" selected disabled>ເລືອກເພດ</option>
                                        <option value="Male">ຊາຍ</option>
                                        <option value="Female">ຍິງ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tell" class="form-label">ເບີໂທລະສັບ <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="tell" name="tell" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">ອີເມວ <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">ທີ່ຢູ່ປັດຈຸບັນ <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required maxlength="50"></textarea>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label">ສັນຊາດ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="ລາວ" required maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label for="picture" class="form-label">ຮູບໂປຣໄຟລ໌ (3x4 ຊມ)</label>
                                    <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                                    <div class="form-text">ໄຟລ໌ຮູບຕ້ອງບໍ່ເກີນ 2MB</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary px-4" onclick="nextStep(1)">ຕໍ່ໄປ <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 2: Registration -->
                        <div class="step d-none" id="step2">
                            <h5 class="mb-4">ຂໍ້ມູນການລົງທະບຽນ</h5>
                            
                            <!-- Academic selections -->
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">ເລືອກສົກຮຽນ ແລະ ສາຂາ</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="academic_year_id" class="form-label">ສົກຮຽນ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="academic_year_id" name="academic_year_id" required>
                                                    <option value="" selected disabled>ເລືອກສົກຮຽນ</option>
                                                    <!-- Will be populated dynamically -->
                                                </select>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກສົກຮຽນ</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="term_id" class="form-label">ເທີມ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="term_id" name="term_id" disabled required>
                                                    <option value="" selected disabled>ເລືອກເທີມ</option>
                                                    <!-- Will be populated after year is selected -->
                                                </select>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກເທີມ</div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="semester_id" class="form-label">ພາກຮຽນ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="semester_id" name="semester_id" disabled required>
                                                    <option value="" selected disabled>ເລືອກພາກຮຽນ</option>
                                                    <!-- Will be populated after term is selected -->
                                                </select>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກພາກຮຽນ</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="major_id" class="form-label">ສາຂາທີ່ຕ້ອງການຮຽນ <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-select" id="major_id" name="major_id" disabled required>
                                                        <option value="" selected disabled>ເລືອກສາຂາ</option>
                                                        <!-- Will be populated after semester is selected -->
                                                    </select>
                                                    <button type="button" class="btn btn-primary" id="add-major-btn" disabled>
                                                        <i class="bi bi-plus"></i> ເພີ່ມ
                                                    </button>
                                                </div>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກສາຂາ</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Majors Table -->
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">ສາຂາທີ່ເລືອກ</h6>
                                        <span class="badge bg-primary" id="selected-major-count">0</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>ລຳດັບ</th>
                                                        <th>ສາຂາ</th>
                                                        <th>ພາກຮຽນ</th>
                                                        <th>ເທີມ</th>
                                                        <th>ສົກຮຽນ</th>
                                                        <th>ຄ່າຮຽນ</th>
                                                        <th>ຈັດການ</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="selected-majors-table">
                                                    <tr id="no-majors-row">
                                                        <td colspan="7" class="text-center py-3">ຍັງບໍ່ມີສາຂາທີ່ຖືກເລືອກ</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th colspan="5" class="text-end">ລວມ:</th>
                                                        <th id="major-total-price" class="text-end">0 ກີບ</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Program Type and Study Time -->
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">ຂໍ້ມູນການຮຽນ</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="education_level" class="form-label">ລະດັບການສຶກສາ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="education_level" name="education_level" required>
                                                    <option value="" selected>ເລືອກລະດັບການສຶກສາ</option>
                                                    <option value="diploma">ຊັ້ນກາງ (ອະນຸປະລິນຍາ)</option>
                                                    <option value="bachelor">ຊັ້ນສູງ (ປະລິນຍາຕີ)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="study_time" class="form-label">ເວລາຮຽນ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="study_time" name="study_time" required>
                                                    <option value="" selected disabled>ເລືອກເວລາຮຽນ</option>
                                                    <option value="morning">ພາກເຊົ້າ (8:00 - 11:30)</option>
                                                    <option value="afternoon">ພາກບ່າຍ (13:30 - 16:30)</option>
                                                    <option value="evening">ພາກຄ່ຳ (17:30 - 19:30)</option>
                                                    <option value="weekend">ພາກວັນເສົາ-ອາທິດ</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Previous Education -->
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">ຂໍ້ມູນການສຶກສາທີ່ຜ່ານມາ</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="previous_school" class="form-label">ຊື່ໂຮງຮຽນທີ່ຮຽນຈົບມາ <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="previous_school" name="previous_school" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="graduation_year" class="form-label">ປີທີ່ຮຽນຈົບ <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="graduation_year" name="graduation_year" min="2000" max="2030" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="gpa" class="form-label">ຄະແນນສະເລ່ຍ</label>
                                                <input type="number" class="form-control" id="gpa" name="gpa" min="0" max="10" step="0.01">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="score" class="form-label">ໃບປະກາສະນີຍະບັດ <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="score" name="score" accept="image/*" required>
                                            <div class="form-text">ອັບໂຫລດໃບປະກາສະນີຍະບັດ ຫຼື ໃບຢັ້ງຢືນການສຶກສາ (ຮູບພາບ)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dormitory Option -->
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">ຂໍ້ມູນທີ່ພັກ</h6>
                                    </div>
                                    <div class="card-body">
                                        <label for="dormitory" class="form-label">ຕ້ອງການພັກຫໍພັກຂອງວິທະຍາໄລບໍ່?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dormitory" id="dormitory_yes" value="yes">
                                            <label class="form-check-label" for="dormitory_yes">
                                                ຕ້ອງການ
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dormitory" id="dormitory_no" value="no" checked>
                                            <label class="form-check-label" for="dormitory_no">
                                                ບໍ່ຕ້ອງການ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(2)"><i class="bi bi-arrow-left me-2"></i> ກັບຄືນ</button>
                                <button type="button" class="btn btn-primary px-4" onclick="nextStep(2)">ຕໍ່ໄປ <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 3: Payment -->
                        <div class="step d-none" id="step3">
                            <h5 class="mb-4">ການຈ່າຍເງິນ</h5>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-primary mb-3 h-100">
                                        <div class="card-header bg-primary text-white">ລາຍການຄ່າໃຊ້ຈ່າຍ</div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td>ຄ່າລົງທະບຽນ:</td>
                                                        <td class="text-end">500,000 ກີບ</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ຄ່າຮຽນ (ຕໍ່ພາກຮຽນ):</td>
                                                        <td class="text-end"><span id="tuition_fee">1,500,000</span> ກີບ</td>
                                                    </tr>
                                                    <tr>
                                                        <td>ຄ່າຊຸດນັກສຶກສາ:</td>
                                                        <td class="text-end">250,000 ກີບ</td>
                                                    </tr>
                                                    <tr class="dormitory-fee" style="display: none;">
                                                        <td>ຄ່າຫໍພັກ (ຕໍ່ເດືອນ):</td>
                                                        <td class="text-end">400,000 ກີບ</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><hr></td>
                                                    </tr>
                                                    <tr>
                                                        <th>ລວມທັງໝົດ:</th>
                                                        <th class="text-end text-primary" id="total_amount">2,250,000 ກີບ</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-success mb-3 h-100">
                                        <div class="card-header bg-success text-white">ຊ່ອງທາງການຈ່າຍເງິນ</div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">ກະລຸນາສະແກນ QR Code ເພື່ອຊຳລະເງິນ</div>
                                            <img src="{{ asset('assets/img/payment_qr.svg') }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                                            <div class="bg-light p-2 rounded mb-3">
                                                <div class="small mb-1">ເລກບັນຊີ:</div>
                                                <div class="fw-bold">010-12-00-123456789-001</div>
                                                <div class="small mb-1 mt-2">ຊື່ບັນຊີ:</div>
                                                <div class="fw-bold">ວິທະຍາໄລ ລາວວຽງ</div>
                                                <div class="small mb-1 mt-2">ທະນາຄານ:</div>
                                                <div class="fw-bold">ທະນາຄານການຄ້າຕ່າງປະເທດລາວ (BCEL)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="payment_proof" class="form-label">ຫຼັກຖານການຈ່າຍເງິນ <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                <div class="form-text">ອັບໂຫຼດຮູບຖ່າຍໃບບິນ ຫຼື ຮູບຖ່າຍຫນ້າຈໍການໂອນເງິນ</div>
                            </div>

                            <div class="mb-4">
                                <label for="registration_date" class="form-label">ວັນທີຊຳລະເງິນ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="registration_date" name="registration_date" value="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="pro" class="form-label">ສ່ວນຫຼຸດ (%)</label>
                                    <input type="number" class="form-control" id="pro" name="pro" min="0" max="100" value="0" step="0.01">
                                    <div class="form-text">ປ້ອນຄ່າສ່ວນຫຼຸດຖ້າມີ (ຕົວຢ່າງ: 10 ສຳລັບສ່ວນຫຼຸດ 10%)</div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                                <label class="form-check-label" for="terms_agreement">
                                    ຂ້າພະເຈົ້າຮັບຮູ້ແລະຍອມຮັບເງື່ອນໄຂທັງໝົດຂອງການລົງທະບຽນແລະນະໂຍບາຍຂອງວິທະຍາໄລ
                                </label>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">ຕັ້ງລະຫັດຜ່ານເພື່ອເຂົ້າສູ່ລະບົບ <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">ຕັ້ງລະຫັດຜ່ານທີ່ປອດໄພສຳລັບບັນຊີຂອງທ່ານ</div>
                            </div>

                            <!-- Hidden input fields to store registration detail information -->
                            <input type="hidden" name="detail_price" id="detail_price">
                            <input type="hidden" name="total_price" id="total_price">

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(3)"><i class="bi bi-arrow-left me-2"></i> ກັບຄືນ</button>
                                <button type="submit" class="btn btn-success px-5"><i class="bi bi-check-circle me-2"></i> ສົ່ງຂໍ້ມູນລົງທະບຽນ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add data for JavaScript -->
<script>
    // Embed all data from controller as JavaScript variables
    const allYears = @json($years);
    const allTerms = @json($terms);
    const allSemesters = @json($semesters);
    const allMajors = @json($majors);
</script>

<script>
    // Current step tracker
    let currentStep = 1;
    const totalSteps = 3;
    
    // Navigate to next step
    function nextStep(step) {
        // Validate current step
        if (!validateStep(step)) {
            return false;
        }
        
        // Hide current step
        document.getElementById('step' + step).classList.add('d-none');
        
        // Show next step
        document.getElementById('step' + (step + 1)).classList.remove('d-none');
        
        // Update progress bar
        currentStep = step + 1;
        updateProgress();
        
        // Scroll to top
        window.scrollTo(0, 0);
    }
    
    // Navigate to previous step
    function prevStep(step) {
        // Hide current step
        document.getElementById('step' + step).classList.add('d-none');
        
        // Show previous step
        document.getElementById('step' + (step - 1)).classList.remove('d-none');
        
        // Update progress bar
        currentStep = step - 1;
        updateProgress();
        
        // Scroll to top
        window.scrollTo(0, 0);
    }
    
    // Update progress bar and step indicators
    function updateProgress() {
        // Update progress bar width
        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById('registration-progress').style.width = progressPercentage + '%';
        
        // Update step buttons
        const stepButtons = document.querySelectorAll('.btn-step');
        stepButtons.forEach((button, index) => {
            if (index + 1 < currentStep) {
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');
                button.innerHTML = '<i class="bi bi-check"></i>';
            } else if (index + 1 === currentStep) {
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');
            } else {
                button.classList.remove('btn-primary', 'btn-success');
                button.classList.add('btn-outline-primary');
                button.innerHTML = index + 1;
            }
        });
    }
    
    // Validate each step
    function validateStep(step) {
        let isValid = true;
        
        if (step === 1) {
            // Validate student information fields
            const requiredFields = [
                'name', 'sername', 'birthday', 'gender', 
                'tell', 'address', 'nationality'
            ];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        } else if (step === 2) {
            // Check if at least one major is selected
            if (selectedMajors.length === 0) {
                alert('ກະລຸນາເລືອກຢ່າງໜ້ອຍ 1 ສາຂາ');
                isValid = false;
                return isValid;
            }
            
            // Validate other required fields
            const requiredFields = [
                'education_level', 'study_time', 'previous_school', 
                'graduation_year', 'score'
            ];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input) return;
                
                if (input.type === 'file') {
                    if (input.files.length === 0) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                } else if (!input.value) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
        }
        
        return isValid;
    }
    
    // Calculate total amount based on selections
    document.addEventListener('DOMContentLoaded', function() {
        const majorSelect = document.getElementById('major_id');
        const educationLevelSelect = document.getElementById('education_level');
        const dormitoryYesRadio = document.getElementById('dormitory_yes');
        const dormitoryNoRadio = document.getElementById('dormitory_no');
        const proInput = document.getElementById('pro');
        
        // Function to update total
        function updateTotal() {
            let baseFee = 500000; // Registration fee
            let tuitionFee = 1500000; // Default tuition
            const uniformFee = 250000;
            let dormitoryFee = 0;
            
            // Adjust tuition based on major and education level
            if (majorSelect.value == '3' || majorSelect.value == '4') {
                tuitionFee = 1700000;
            }
            
            if (educationLevelSelect.value === 'bachelor') {
                tuitionFee += 300000;
            }
            
            // Update displayed tuition
            document.getElementById('tuition_fee').textContent = formatNumber(tuitionFee);
            
            // Check if dormitory is selected
            if (dormitoryYesRadio.checked) {
                dormitoryFee = 400000;
                document.querySelector('.dormitory-fee').style.display = 'table-row';
            } else {
                document.querySelector('.dormitory-fee').style.display = 'none';
            }
            
            // Calculate and update total
            let subtotal = baseFee + tuitionFee + uniformFee + dormitoryFee;
            
            // Apply discount if any
            let discount = 0;
            if (proInput.value) {
                discount = (parseFloat(proInput.value) / 100) * subtotal;
            }
            
            const total = subtotal - discount;
            
            // Update the hidden fields
            document.getElementById('detail_price').value = subtotal;
            document.getElementById('total_price').value = total;
            
            // Update displayed total
            document.getElementById('total_amount').textContent = formatNumber(total) + ' ກີບ';
        }
        
        // Format number with commas
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        
        // Add change event listeners
        majorSelect.addEventListener('change', updateTotal);
        educationLevelSelect.addEventListener('change', updateTotal);
        dormitoryYesRadio.addEventListener('change', updateTotal);
        dormitoryNoRadio.addEventListener('change', updateTotal);
        proInput.addEventListener('input', updateTotal);
        
        // Initialize province-district selector
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        
        // Sample district data by province
        const districtsByProvince = {
            'vientiane_capital': ['ໄຊເສດຖາ', 'ຈັນທະບູລີ', 'ສີໂຄດຕະບອງ', 'ສີສັດຕະນາກ', 'ນາຊາຍທອງ', 'ໄຊທານີ', 'ຫາດຊາຍຟອງ', 'ປາກງື່ມ', 'ສັງທອງ'],
            'vientiane_province': ['ໂພນໂຮງ', 'ທຸລະຄົມ', 'ແກ້ວອຸດົມ', 'ກາສີ', 'ວັງວຽງ', 'ເຟືອງ', 'ຊະນະຄາມ', 'ແມດ', 'ຫີນເຫີບ', 'ວຽງຄຳ'],
            'savannakhet': ['ເມືອງຄັງທະບູລີ', 'ເມືອງອຸທຸມພອນ', 'ເມືອງອາດສະພັງທອງ', 'ເມືອງພີນ', 'ເມືອງເຊໂປນ'],
            'champassak': ['ເມືອງປາກເຊ', 'ເມືອງຊະນະສົມບູນ', 'ເມືອງບາຈຽງຈະເລີນສຸກ', 'ເມືອງປະທຸມພອນ', 'ເມືອງໂພນທອງ']
            // Add other provinces and districts as needed
        };
        
        // Update districts when province changes
        provinceSelect.addEventListener('change', function() {
            const selectedProvince = this.value;
            const districts = districtsByProvince[selectedProvince] || [];
            
            // Clear current options
            districtSelect.innerHTML = '<option value="" selected disabled>ເລືອກເມືອງ</option>';
            
            // Add new options
            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        });

        // Initialize dropdowns with data from controller
        initializeDropdowns();
        
        function initializeDropdowns() {
            // Clear any previous data
            resetAllDropdowns();
            
            // Populate years dropdown with data from controller
            populateYears();
        }
        
        // Function to populate years dropdown with data from controller
        function populateYears() {
            const yearSelect = document.getElementById('academic_year_id');
            if (!yearSelect) {
                console.error('Year select element not found');
                return;
            }
            
            yearSelect.innerHTML = '<option value="" selected disabled>ເລືອກສົກຮຽນ</option>';
            
            if (allYears && allYears.length > 0) {
                allYears.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year.id;
                    option.textContent = year.name;
                    yearSelect.appendChild(option);
                });
                
                // Set up the year change event handler
                yearSelect.addEventListener('change', handleYearChange);
            } else {
                console.warn('No academic years data available');
                useHardcodedData();
            }
        }
        
        // Handle year selection - now works with embedded data
        function handleYearChange() {
            const yearId = this.value;
            if (!yearId) {
                resetDependentDropdowns('term_id');
                return;
            }
            
            // Populate terms with all terms data
            populateTerms();
            const termSelect = document.getElementById('term_id');
            if (termSelect) {
                termSelect.disabled = false;
            }
            resetDependentDropdowns('term_id');
        }
        
        // Function to populate terms dropdown - works with embedded data
        function populateTerms() {
            const termSelect = document.getElementById('term_id');
            if (!termSelect) {
                console.error('Term select element not found');
                return;
            }
            
            termSelect.innerHTML = '<option value="" selected disabled>ເລືອກເທີມ</option>';
            
            if (allTerms && allTerms.length > 0) {
                allTerms.forEach(term => {
                    const option = document.createElement('option');
                    option.value = term.id;
                    option.textContent = term.name;
                    termSelect.appendChild(option);
                });
                
                // Set up the term change event handler
                termSelect.addEventListener('change', handleTermChange);
            } else {
                console.warn('No terms data available');
            }
        }
        
        // Handle term selection - works with embedded data
        function handleTermChange() {
            const termId = this.value;
            const yearSelect = document.getElementById('academic_year_id');
            const yearId = yearSelect ? yearSelect.value : null;
            
            if (!termId || !yearId) {
                resetDependentDropdowns('semester_id');
                return;
            }
            
            // Populate semesters with all semesters data
            populateSemesters();
            const semesterSelect = document.getElementById('semester_id');
            if (semesterSelect) {
                semesterSelect.disabled = false;
            }
            resetDependentDropdowns('semester_id');
        }
        
        // Function to populate semesters dropdown - works with embedded data
        function populateSemesters() {
            const semesterSelect = document.getElementById('semester_id');
            if (!semesterSelect) {
                console.error('Semester select element not found');
                return;
            }
            
            semesterSelect.innerHTML = '<option value="" selected disabled>ເລືອກພາກຮຽນ</option>';
            
            if (allSemesters && allSemesters.length > 0) {
                allSemesters.forEach(semester => {
                    const option = document.createElement('option');
                    option.value = semester.id;
                    option.textContent = semester.name;
                    semesterSelect.appendChild(option);
                });
                
                // Set up the semester change event handler
                semesterSelect.addEventListener('change', handleSemesterChange);
            } else {
                console.warn('No semesters data available');
            }
        }
        
        // Handle semester selection - works with embedded data
        function handleSemesterChange() {
            const semesterId = this.value;
            const termSelect = document.getElementById('term_id');
            const termId = termSelect ? termSelect.value : null;
            const yearSelect = document.getElementById('academic_year_id');
            const yearId = yearSelect ? yearSelect.value : null;
            
            if (!semesterId || !termId || !yearId) {
                resetDependentDropdowns('major_id');
                return;
            }
            
            // Filter majors based on selected year, term, and semester
            populateFilteredMajors(yearId, termId, semesterId);
            const majorSelect = document.getElementById('major_id');
            if (majorSelect) {
                majorSelect.disabled = false;
            }
        }
        
        // Function to populate majors dropdown with filtered data
        function populateFilteredMajors(yearId, termId, semesterId) {
            const majorSelect = document.getElementById('major_id');
            if (!majorSelect) {
                console.error('Major select element not found');
                return;
            }
            
            majorSelect.innerHTML = '<option value="" selected disabled>ເລືອກສາຂາ</option>';
            
            // Filter the majors based on selected criteria
            const filteredMajors = allMajors.filter(major => 
                major.year_id == yearId && 
                major.term_id == termId && 
                major.semester_id == semesterId
            );
            
            if (filteredMajors && filteredMajors.length > 0) {
                filteredMajors.forEach(major => {
                    const option = document.createElement('option');
                    option.value = major.id;
                    option.textContent = major.name;
                    majorSelect.appendChild(option);
                });
                
                // Enable add button when major is selected
                majorSelect.addEventListener('change', function() {
                    const addButton = document.getElementById('add-major-btn');
                    if (addButton) {
                        addButton.disabled = !this.value;
                    }
                });
            } else {
                console.warn('No majors available for the selected combination');
                // If no majors found, display a message in the dropdown
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "ບໍ່ມີສາຂາສຳລັບການເລືອກນີ້";
                option.disabled = true;
                majorSelect.appendChild(option);
            }
        }
        
        // Reset dependent dropdowns when a parent dropdown changes
        function resetDependentDropdowns(startFrom) {
            if (startFrom === 'term_id') {
                const semesterSelect = document.getElementById('semester_id');
                const majorSelect = document.getElementById('major_id');
                const addButton = document.getElementById('add-major-btn');
                
                if (semesterSelect) {
                    semesterSelect.innerHTML = '<option value="" selected disabled>ເລືອກພາກຮຽນ</option>';
                    semesterSelect.disabled = true;
                }
                if (majorSelect) {
                    majorSelect.innerHTML = '<option value="" selected disabled>ເລືອກສາຂາ</option>';
                    majorSelect.disabled = true;
                }
                if (addButton) {
                    addButton.disabled = true;
                }
            } else if (startFrom === 'semester_id') {
                const majorSelect = document.getElementById('major_id');
                const addButton = document.getElementById('add-major-btn');
                
                if (majorSelect) {
                    majorSelect.innerHTML = '<option value="" selected disabled>ເລືອກສາຂາ</option>';
                    majorSelect.disabled = true;
                }
                if (addButton) {
                    addButton.disabled = true;
                }
            }
        }
        
        // Reset all dropdowns
        function resetAllDropdowns() {
            const yearSelect = document.getElementById('academic_year_id');
            const termSelect = document.getElementById('term_id');
            const semesterSelect = document.getElementById('semester_id');
            const majorSelect = document.getElementById('major_id');
            const addButton = document.getElementById('add-major-btn');
            
            if (yearSelect) {
                yearSelect.innerHTML = '<option value="" selected disabled>ເລືອກສົກຮຽນ</option>';
            }
            if (termSelect) {
                termSelect.innerHTML = '<option value="" selected disabled>ເລືອກເທີມ</option>';
                termSelect.disabled = true;
            }
            if (semesterSelect) {
                semesterSelect.innerHTML = '<option value="" selected disabled>ເລືອກພາກຮຽນ</option>';
                semesterSelect.disabled = true;
            }
            if (majorSelect) {
                majorSelect.innerHTML = '<option value="" selected disabled>ເລືອກສາຂາ</option>';
                majorSelect.disabled = true;
            }
            if (addButton) {
                addButton.disabled = true;
            }
        }
        
        // Fall back to hardcoded data if API calls fail
        function useHardcodedData() {
            console.log('Using hardcoded data as fallback');
            
            // Populate years with hardcoded data
            const yearSelect = document.getElementById('academic_year_id');
            yearSelect.innerHTML = '<option value="" selected disabled>ເລືອກສົກຮຽນ</option>';
            const years = [
                { id: 1, name: "2023-2024" },
                { id: 2, name: "2024-2025" },
                { id: 3, name: "2025-2026" }
            ];
            years.forEach(year => {
                const option = document.createElement('option');
                option.value = year.id;
                option.textContent = year.name;
                yearSelect.appendChild(option);
            });
            
            // Set up the year change event handler
            yearSelect.addEventListener('change', function() {
                document.getElementById('term_id').disabled = false;
                populateHardcodedTerms();
                resetDependentDropdowns('term_id');
            });
        }
        
        // Populate terms with hardcoded data
        function populateHardcodedTerms() {
            const termSelect = document.getElementById('term_id');
            termSelect.innerHTML = '<option value="" selected disabled>ເລືອກເທີມ</option>';
            const terms = [
                { id: 1, name: "ເທີມ 1" },
                { id: 2, name: "ເທີມ 2" }
            ];
            terms.forEach(term => {
                const option = document.createElement('option');
                option.value = term.id;
                option.textContent = term.name;
                termSelect.appendChild(option);
            });
            
            // Set up the term change event handler
            termSelect.addEventListener('change', function() {
                document.getElementById('semester_id').disabled = false;
                populateHardcodedSemesters();
                resetDependentDropdowns('semester_id');
            });
        }
        
        // Populate semesters with hardcoded data
        function populateHardcodedSemesters() {
            const semesterSelect = document.getElementById('semester_id');
            semesterSelect.innerHTML = '<option value="" selected disabled>ເລືອກພາກຮຽນ</option>';
            const semesters = [
                { id: 1, name: "ພາກຮຽນທີ 1" },
                { id: 2, name: "ພາກຮຽນທີ 2" }
            ];
            semesters.forEach(semester => {
                const option = document.createElement('option');
                option.value = semester.id;
                option.textContent = semester.name;
                semesterSelect.appendChild(option);
            });
            
            // Set up the semester change event handler
            semesterSelect.addEventListener('change', function() {
                document.getElementById('major_id').disabled = false;
                populateHardcodedMajors();
            });
        }
        
        // Populate majors with hardcoded data
        function populateHardcodedMajors() {
            const majorSelect = document.getElementById('major_id');
            majorSelect.innerHTML = '<option value="" selected disabled>ເລືອກສາຂາ</option>';
            const majors = [
                { id: 1, name: "ສາຂາໄອທີ (Computer Business Technology)" },
                { id: 2, name: "ສາຂາການເງິນ-ບັນຊີ (Finance-Accounting)" },
                { id: 3, name: "ສາຂາພາສາອັງກິດທຸລະກິດ (Business English)" },
                { id: 4, name: "ສາຂາພາສາຈີນທຸລະກິດ (Business Chinese)" }
            ];
            majors.forEach(major => {
                const option = document.createElement('option');
                option.value = major.id;
                option.textContent = major.name;
                majorSelect.appendChild(option);
            });
            
            document.getElementById('add-major-btn').disabled = true;
            majorSelect.addEventListener('change', function() {
                document.getElementById('add-major-btn').disabled = !this.value;
            });
        }
        
        // ...existing code for add-major-btn event handler etc...
    });
    
    // Store selected majors
    let selectedMajors = [];
    let selectedMajorsData = {};
    let totalMajorPrice = 0;
    
    // Update selected majors table
    function updateSelectedMajorsTable() {
        const tableBody = document.getElementById('selected-majors-table');
        const noMajorsRow = document.getElementById('no-majors-row');
        const majorCount = document.getElementById('selected-major-count');
        const majorTotalPrice = document.getElementById('major-total-price');
        
        // Clear table
        tableBody.innerHTML = '';
        
        // Update count
        majorCount.textContent = selectedMajors.length;
        
        // Display no majors message if empty
        if (selectedMajors.length === 0) {
            tableBody.appendChild(noMajorsRow);
            majorTotalPrice.textContent = '0 ກີບ';
            return;
        }
        
        // Reset total price
        totalMajorPrice = 0;
        
        // Add each major to table
        selectedMajors.forEach((majorId, index) => {
            const majorData = selectedMajorsData[majorId];
            totalMajorPrice += majorData.price;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td><span class="badge bg-info">${majorData.name}</span></td>
                <td>${majorData.semester}</td>
                <td>${majorData.term}</td>
                <td>${majorData.year}</td>
                <td class="text-end">${formatNumber(majorData.price)} ກີບ</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeMajor('${majorId}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
            
            // Add hidden input for major
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'selected_majors[]';
            hiddenInput.value = majorId;
            document.getElementById('studentRegistrationForm').appendChild(hiddenInput);
        });
        
        // Update total price
        majorTotalPrice.textContent = formatNumber(totalMajorPrice) + ' ກີບ';
    }
    
    // Remove major from selection
    function removeMajor(majorId) {
        selectedMajors = selectedMajors.filter(id => id !== majorId);
        delete selectedMajorsData[majorId];
        updateSelectedMajorsTable();
        
        // Remove hidden inputs
        const hiddenInputs = document.querySelectorAll(`input[name="selected_majors[]"][value="${majorId}"]`);
        hiddenInputs.forEach(input => input.remove());
    }
</script>

<style>
    .step-label {
        width: 120px;
        margin-left: -35px;
        font-weight: bold;
        white-space: nowrap;
    }
</style>
@endsection
