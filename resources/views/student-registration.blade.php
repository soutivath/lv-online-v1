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
                                    <label for="email" class="form-label">ອີເມວ</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">ທີ່ຢູ່ປັດຈຸບັນ <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label">ສັນຊາດ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="ລາວ" required>
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

                            <div class="mb-3">
                                <label for="major" class="form-label">ສາຂາທີ່ຕ້ອງການຮຽນ <span class="text-danger">*</span></label>
                                <select class="form-select" id="major" name="major" required>
                                    <option value="" selected disabled>ເລືອກສາຂາ</option>
                                    <option value="computer_business">ສາຂາໄອທີ (Computer Business Technology)</option>
                                    <option value="finance_accounting">ສາຂາການເງິນ-ບັນຊີ (Finance-Accounting)</option>
                                    <option value="business_english">ສາຂາພາສາອັງກິດທຸລະກິດ (Business English)</option>
                                    <option value="business_chinese">ສາຂາພາສາຈີນທຸລະກິດ (Business Chinese)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="education_level" class="form-label">ລະດັບການສຶກສາ <span class="text-danger">*</span></label>
                                <select class="form-select" id="education_level" name="education_level" required>
                                    <option value="" selected disabled>ເລືອກລະດັບການສຶກສາ</option>
                                    <option value="diploma">ຊັ້ນກາງ (ອະນຸປະລິນຍາ)</option>
                                    <option value="bachelor">ຊັ້ນສູງ (ປະລິນຍາຕີ)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="study_time" class="form-label">ເວລາຮຽນ <span class="text-danger">*</span></label>
                                <select class="form-select" id="study_time" name="study_time" required>
                                    <option value="" selected disabled>ເລືອກເວລາຮຽນ</option>
                                    <option value="morning">ພາກເຊົ້າ (8:00 - 11:30)</option>
                                    <option value="afternoon">ພາກບ່າຍ (13:30 - 16:30)</option>
                                    <option value="evening">ພາກຄ່ຳ (17:30 - 19:30)</option>
                                    <option value="weekend">ພາກວັນເສົາ-ອາທິດ</option>
                                </select>
                            </div>

                            <div class="mb-4 border rounded p-3 bg-light">
                                <div class="mb-3">
                                    <label for="previous_school" class="form-label">ຊື່ໂຮງຮຽນທີ່ຮຽນຈົບມາ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="previous_school" name="previous_school" required>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="graduation_year" class="form-label">ປີທີ່ຮຽນຈົບ <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="graduation_year" name="graduation_year" min="2000" max="2030" required>
                                    </div>
                                    <div class="col-md-6">
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

                            <div class="mb-4">
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
                                <label for="payment_date" class="form-label">ວັນທີຊຳລະເງິນ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="payment_date" name="payment_date" required>
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
            // Validate registration fields
            const requiredFields = [
                'major', 'education_level', 'study_time', 
                'previous_school', 'graduation_year', 'score'
            ];
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if ((!input.value && input.type !== 'file') || 
                    (input.type === 'file' && input.files.length === 0)) {
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
        const majorSelect = document.getElementById('major');
        const educationLevelSelect = document.getElementById('education_level');
        const dormitoryYesRadio = document.getElementById('dormitory_yes');
        const dormitoryNoRadio = document.getElementById('dormitory_no');
        
        // Function to update total
        function updateTotal() {
            let baseFee = 500000; // Registration fee
            let tuitionFee = 1500000; // Default tuition
            const uniformFee = 250000;
            let dormitoryFee = 0;
            
            // Adjust tuition based on major and education level
            if (majorSelect.value === 'business_english' || majorSelect.value === 'business_chinese') {
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
            const total = baseFee + tuitionFee + uniformFee + dormitoryFee;
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
    });
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
