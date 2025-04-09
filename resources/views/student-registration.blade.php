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

                    <!-- Form - IMPORTANT CHANGE: Add onSubmit handler -->
                    <form id="studentRegistrationForm" action="{{ route('registrations.student') }}" method="POST" enctype="multipart/form-data" onsubmit="return handleFormSubmit(event)">
                        @csrf
                        <input type="hidden" name="form_submission_token" value="{{ uniqid() }}">
                        
                        <!-- Add this section to display validation errors from Laravel -->
                        @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

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

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="score" class="form-label">ເອກະສານຄະແນນການສຶກສາ <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="score" name="score" accept="image/*" required>
                                    <div class="form-text">ອັບໂຫຼດຮູບຖ່າຍໃບຄະແນນ ຫຼື ໃບຢັ້ງຢືນການສຶກສາ (ໄຟລ໌ຕ້ອງບໍ່ເກີນ 2MB)</div>
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
                                                    @foreach(App\Models\Year::all() as $year)
                                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກສົກຮຽນ</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="term_id" class="form-label">ເທີມ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="term_id" name="term_id" required>
                                                    <option value="" selected disabled>ເລືອກເທີມ</option>
                                                    @foreach(App\Models\Term::all() as $term)
                                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກເທີມ</div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="semester_id" class="form-label">ພາກຮຽນ <span class="text-danger">*</span></label>
                                                <select class="form-select" id="semester_id" name="semester_id" required>
                                                    <option value="" selected disabled>ເລືອກພາກຮຽນ</option>
                                                    @foreach(App\Models\Semester::all() as $semester)
                                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກພາກຮຽນ</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="major_id" class="form-label">ສາຂາທີ່ຕ້ອງການຮຽນ <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <select class="form-select" id="major_id" name="major_id">
                                                        <option value="" selected disabled>ເລືອກສາຂາ</option>
                                                        @foreach($majors as $major)
                                                        <option value="{{ $major->id }}"
                                                            data-id="{{ $major->id }}"
                                                            data-name="{{ $major->name }}"
                                                            data-semester="{{ $major->semester->name }}"
                                                            data-term="{{ $major->term->name }}"
                                                            data-year="{{ $major->year->name }}"
                                                            data-price="{{ $major->tuition->price }}"
                                                            data-semester-id="{{ $major->semester_id }}"
                                                            data-term-id="{{ $major->term_id }}"
                                                            data-year-id="{{ $major->year_id }}">
                                                            {{ $major->name }} | {{ $major->semester->name }} | {{ $major->term->name }} | {{ $major->year->name }} | Fee: {{ number_format($major->tuition->price, 2) }}
                                                        </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="invalid-feedback">ກະລຸນາເລືອກສາຂາ</div>
                                            </div>


                                        </div>

                                        <div class=" mt-3 mx-1 row ">
                                            <button type="button" class="btn btn-primary" id="add-major-btn">
                                                <i class="bi bi-plus"></i> ເພີ່ມ
                                            </button>
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
                                        <input type="hidden" name="major_ids" id="major_ids" value="" >
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
                                                    {{-- <tr>
                                                        <td>ຄ່າລົງທະບຽນ:</td>
                                                        <td class="text-end">500,000 ກີບ</td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td>ຄ່າຮຽນ (ຕໍ່ພາກຮຽນ):</td>
                                                        <td class="text-end"><span id="tuition_fee">1,500,000</span> ກີບ</td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td>ຄ່າຊຸດນັກສຶກສາ:</td>
                                                        <td class="text-end">250,000 ກີບ</td>
                                                    </tr> --}}
                                                    {{-- <tr class="dormitory-fee" style="display: none;">
                                                        <td>ຄ່າຫໍພັກ (ຕໍ່ເດືອນ):</td>
                                                        <td class="text-end">400,000 ກີບ</td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td colspan="2">
                                                            <hr>
                                                        </td>
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

                            <div class="row mb-4" style="display: none;">
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

                            <!-- Replace the submit button -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(3)"><i class="bi bi-arrow-left me-2"></i> ກັບຄືນ</button>
                                <button type="submit" class="btn btn-success px-5" id="submit-registration-btn"><i class="bi bi-check-circle me-2"></i> ສົ່ງຂໍ້ມູນລົງທະບຽນ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add data for JavaScript -->
<!-- Add this before your closing </body> tag but before your scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        // If moving to payment step, update payment details with selected majors
        if (step === 2) {
            updatePaymentDetails();
        }

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
                Swal.fire({
                    icon: 'warning',
                    title: 'ບໍ່ໄດ້ເລືອກສາຂາ',
                    text: 'ກະລຸນາເລືອກຢ່າງໜ້ອຍ 1 ສາຂາ'
                });
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

    // New function to update payment details based on selected majors
    function updatePaymentDetails() {
        const registrationFee = 500000; // Fixed registration fee
        const uniformFee = 250000;     // Fixed uniform fee
        
        // Use the total price from selected majors
        const tuitionFee = totalMajorPrice;
        
        // Update displayed tuition fee
        document.getElementById('tuition_fee').textContent = formatNumber(tuitionFee);
        
        // Calculate total
        // let total = registrationFee + tuitionFee + uniformFee;
        let total =  tuitionFee;
        
        // Apply discount if any
        // const discountPercent = parseFloat(document.getElementById('pro').value) || 0;
        const discountPercent = 0;
        const discountAmount = (total * discountPercent / 100);
        const finalTotal = total - discountAmount;
        
        // Update displayed total
        document.getElementById('total_amount').textContent = formatNumber(finalTotal) + ' ກີບ';
        
        // Update hidden fields
        document.getElementById('detail_price').value = total;
        document.getElementById('total_price').value = finalTotal;
    }

    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Calculate total amount based on selections
    function updateTotal() {
        // This function now just calls updatePaymentDetails
        updatePaymentDetails();
    }

    document.addEventListener('DOMContentLoaded', function() {

        // Initialize dropdowns with data from controller
        initializeDropdowns();

        const majorSelect = document.getElementById('major_id');
        const dormitoryYesRadio = document.getElementById('dormitory_yes');
        const dormitoryNoRadio = document.getElementById('dormitory_no');
        const proInput = document.getElementById('pro');

        // Add change event listeners to update payment details
        proInput.addEventListener('input', updatePaymentDetails);
        
        // Remove listeners that reference dormitory or education level if they don't exist
        if (dormitoryYesRadio && dormitoryNoRadio) {
            dormitoryYesRadio.addEventListener('change', updatePaymentDetails);
            dormitoryNoRadio.addEventListener('change', updatePaymentDetails);
        }

        // Initialize province-district selector
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');

        // Check if elements exist
        if (!provinceSelect || !districtSelect) {
            console.warn('Province or district select elements not found');
            return;
        }

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



        function initializeDropdowns() {
            console.log("initializeDropdowns");
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
            const addButton = document.getElementById('add-major-btn');

            // Disable add button by default
            if (addButton) {
                addButton.disabled = true;
            }

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
                    // Add all necessary data attributes
                    option.setAttribute('data-id', major.id);
                    option.setAttribute('data-name', major.name);
                    option.setAttribute('data-semester', major.semester.name);
                    option.setAttribute('data-term', major.term.name);
                    option.setAttribute('data-year', major.year.name);
                    option.setAttribute('data-price', major.tuition.price);
                    option.setAttribute('data-semester-id', major.semester_id);
                    option.setAttribute('data-term-id', major.term_id);
                    option.setAttribute('data-year-id', major.year_id);

                    // Format the display text
                    option.textContent = `${major.name} | ${major.semester.name} | ${major.term.name} | ${major.year.name} | ${formatNumber(major.tuition.price)} ກີບ`;

                    majorSelect.appendChild(option);
                });

                // Enable major select and add button when majors are available
                majorSelect.disabled = false;
                const addButton = document.getElementById('add-major-btn');
                if (addButton) {
                    // Keep button disabled until a major is selected
                    addButton.disabled = !this.value;
                    majorSelect.addEventListener('change', function() {
                        addButton.disabled = !this.value;
                    });
                }
            } else {
                console.warn('No majors available for the selected combination');
                // If no majors found, display a message in the dropdown
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "ບໍ່ມີສາຂາສຳລັບການເລືອກນີ້";
                option.disabled = true;
                majorSelect.appendChild(option);
                majorSelect.disabled = true;

                const addButton = document.getElementById('add-major-btn');
                if (addButton) {
                    addButton.disabled = true;
                }
            }
        }



        // Then modify the resetDependentDropdowns function:
        function resetDependentDropdowns(startFrom) {
            const addButton = document.getElementById('add-major-btn');

            if (startFrom === 'term_id') {
                const semesterSelect = document.getElementById('semester_id');
                const majorSelect = document.getElementById('major_id');

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


        // ...existing code for add-major-btn event handler etc...
    });

    // Store selected majors
    let selectedMajors = [];
    let selectedMajorsData = {};
    let totalMajorPrice = 0;

    // Update selected majors table
    function updateSelectedMajorsTable() {
        const tableBody = document.getElementById('selected-majors-table');
        const majorCount = document.getElementById('selected-major-count');
        const majorTotalPrice = document.getElementById('major-total-price');

        // Clear table
        tableBody.innerHTML = '';

        // Update count
        majorCount.textContent = selectedMajors.length;

        // Display no majors message if empty
        if (selectedMajors.length === 0) {
            const noMajorsRow = document.createElement('tr');
            noMajorsRow.id = 'no-majors-row';
            noMajorsRow.innerHTML = `
            <td colspan="7" class="text-center py-3">ຍັງບໍ່ມີສາຂາທີ່ຖືກເລືອກ</td>
        `;
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
         

            
        });
          // Correctly set the major_ids value
    const majorIdsInput = document.getElementById('major_ids');
    if (majorIdsInput) {
        // Join the selectedMajors array directly since it already contains the IDs
        majorIdsInput.value = selectedMajors.join(',');
        

    }

    if(selectedMajors.length >= 2){
        document.getElementById('pro').value = 30;
        document.getElementById('pro').disabled=true;
    }else{
        document.getElementById('pro').value = 0;
        document.getElementById('pro').disabled=false;
    }
        // Update total price
        const totalMajorPriceElement = document.getElementById('major_total_price');
        if (totalMajorPriceElement) {
            totalMajorPriceElement.textContent = formatNumber(totalMajorPrice) + ' ກີບ';
    }
        // Update total price
        majorTotalPrice.textContent = formatNumber(totalMajorPrice) + ' ກີບ';
    }


    // Remove major from selection
    function removeMajor(majorId) {
        selectedMajors = selectedMajors.filter(id => id !== majorId);
        delete selectedMajorsData[majorId];
     
        updateSelectedMajorsTable();

        // Remove hidden inputs
        const hiddenInputs = document.querySelectorAll(`input[name="major_ids"][value="${majorId}"]`);
        hiddenInputs.forEach(input => input.remove());
    }

    // Add major button click handler
    $('#add-major-btn').on('click', function() {
        // Use the correct selector for major select element
        const majorSelect = $('#major_id'); // Changed from major_selector to major_id
        const selectedOption = majorSelect.find('option:selected');
        const majorId = selectedOption.val();

        if (!majorId) {
            Swal.fire({
                icon: 'warning',
                title: 'ບໍ່ໄດ້ເລືອກສາຂາ',
                text: 'ກະລຸນາເລືອກສາຂາທີ່ຕ້ອງການກ່ອນ'
            });
            return;
        }

        // Check for duplicate
        if (selectedMajors.includes(majorId)) {
            Swal.fire({
                icon: 'error',
                title: 'ສາຂາຊ້ຳກັນ',
                text: 'ສາຂານີ້ຖືກເລືອກແລ້ວ'
            });
            return;
        }

        // Get major data from data attributes
        const majorData = {
            id: majorId,
            name: selectedOption.data('name'),
            semester: selectedOption.data('semester'),
            term: selectedOption.data('term'),
            year: selectedOption.data('year'),
            price: parseFloat(selectedOption.data('price'))
        };

        // Store major data
        selectedMajorsData[majorId] = majorData;
        selectedMajors.push(majorId);

        // Update the table
        updateSelectedMajorsTable();

        // Reset select
        majorSelect.val('');

    });
</script>

<script>
        @if(session('sweet_alert'))
            // Only show SweetAlert for fresh page loads, not when navigating back
            let alreadyShown = sessionStorage.getItem('alert_shown_{{ session()->getId() }}');
            
            if (!alreadyShown) {
                Swal.fire({
                    icon: '{{ session('sweet_alert.type') }}',
                    title: '{{ session('sweet_alert.title') }}',
                    text: '{{ session('sweet_alert.text') }}',
                    timer: 3000,
                    timerProgressBar: true
                });
                
                // Mark this alert as shown in this session
                sessionStorage.setItem('alert_shown_{{ session()->getId() }}', 'true');
            }
            
            // Clear the session storage item when leaving the page
            window.addEventListener('beforeunload', function() {
                sessionStorage.removeItem('alert_shown_{{ session()->getId() }}');
            });
        @endif
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