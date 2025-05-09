@extends('Admin.Layout')
@section('title', 'ຊຳລະເງິນຄ່າຮຽນ')
@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-success text-white py-3">
                    <h4 class="card-title mb-0">ຊຳລະເງິນຄ່າຮຽນ</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Step progress indicator -->
                    <div class="mb-5">
                        <div class="position-relative mb-5 pt-3 pb-5">
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" id="payment-progress"></div>
                            </div>
                            <div class="position-absolute top-0 start-0" style="transform: translateX(-10px) translateY(-10px);">
                                <button type="button" class="btn btn-success btn-step rounded-circle active" disabled style="width: 3rem; height: 3rem;">1</button>
                                <div class="text-center mt-2 step-label" style="margin-left: -20px;">ເລືອກສາຂາ</div>
                            </div>
                            <div class="position-absolute top-0" style="left: 50%; transform: translateX(-50%) translateY(-10px);">
                                <button type="button" class="btn btn-outline-success btn-step rounded-circle" disabled style="width: 3rem; height: 3rem;">2</button>
                                <div class="text-center mt-2 step-label">ຢືນຢັນຂໍ້ມູນ</div>
                            </div>
                            <div class="position-absolute top-0 end-0" style="transform: translateX(10px) translateY(-10px);">
                                <button type="button" class="btn btn-outline-success btn-step rounded-circle" disabled style="width: 3rem; height: 3rem;">3</button>
                                <div class="text-center mt-2 step-label">ການຈ່າຍເງິນ</div>
                            </div>
                        </div>
                    </div>

                    <form id="studentPaymentForm" action="{{ route('student.payment.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="form_submission_token" value="{{ uniqid() }}">
                        
                        <!-- Display validation errors -->
                        @if ($errors->any())
                        <div class="alert alert-danger mb-4">y
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Step 1: Select Major -->
                        <div class="step" id="step1">
                            <h5 class="mb-4">ເລືອກສາຂາທີ່ທ່ານຕ້ອງການຊຳລະເງິນ</h5>
                            

                            <div class="col-md-6">
                                <label class="form-label">Student</label>
                                
                                <!-- Inline-styled dropdown to ensure it works -->
                                <div style="position: relative; width: 100%; margin-bottom: 1rem;">
                                    <!-- Display button -->
                                    <button type="button" onclick="toggleStudentList()" 
                                            style="width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; padding: 8px 12px; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                        <span id="selectedStudentText">Select Student</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    
                                    <!-- Hidden dropdown content -->
                                    <div id="studentListContainer" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; margin-top: 2px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 9999; max-height: 300px; overflow: hidden;">
                                        <!-- Search input -->
                                        <div style="padding: 8px; border-bottom: 1px solid #eee;">
                                            <input type="text" id="studentSearchInput" placeholder="Search students..." 
                                                   onkeyup="filterStudents()" 
                                                   style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                                        </div>
                                        
                                        <!-- Student list -->
                                        <div style="max-height: 250px; overflow-y: auto;">
                                            @foreach($students as $student)
                                            <div onclick="chooseStudent('{{ $student->id }}', '{{ $student->id }} - {{ $student->name }} {{ $student->sername }}')"
                                                 style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f5f5f5;">
                                                <i class="fas fa-user-graduate" style="margin-right: 8px;"></i>
                                                {{ $student->id }} - {{ $student->name }} {{ $student->sername }}
                                            </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- No results message -->
                                        <div id="noStudentsMsg" style="display: none; padding: 12px; text-align: center; font-style: italic; color: #6c757d;">
                                            No matching students found
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="student_id" id="student_id" required>
                                </div>
                            </div>


                            <div class="mb-4">
                                <label class="form-label">ເລືອກສາຂາຮຽນ <span class="text-danger">*</span></label>
                                <div style="position: relative; width: 100%;">
                                    <!-- Display button -->
                                    <button type="button" onclick="toggleMajorList()" 
                                            style="width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; padding: 8px 12px; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                        <span id="selectedMajorText">ເລືອກສາຂາທີ່ທ່ານລົງທະບຽນແລ້ວ</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                    
                                    <!-- Hidden dropdown content -->
                                    <div id="majorListContainer" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; margin-top: 2px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 9999; max-height: 300px; overflow: hidden;">
                                        <!-- Search input -->
                                        <div style="padding: 8px; border-bottom: 1px solid #eee;">
                                            <input type="text" id="majorSearchInput" placeholder="ຄົ້ນຫາສາຂາ..." 
                                                   onkeyup="filterMajors()" 
                                                   style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                                        </div>
                                        
                                        <!-- Major list -->
                                        <div style="max-height: 250px; overflow-y: auto;" id="majorOptionsContainer">
                                            @foreach($majors as $major)
                                            <div onclick="chooseMajor('{{ $major->id }}', '{{ $major->name }}', '{{ $major->semester->name }}', '{{ $major->term->name }}', '{{ $major->year->name }}', '{{ $major->tuition->price }}')"
                                                 data-id="{{ $major->id }}"
                                                 data-name="{{ $major->name }}"
                                                 data-semester="{{ $major->semester->name }}"
                                                 data-term="{{ $major->term->name }}"
                                                 data-year="{{ $major->year->name }}"
                                                 data-price="{{ $major->tuition->price }}"
                                                 class="major-option"
                                                 style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f5f5f5;">
                                                <i class="bi bi-book me-2"></i>
                                                {{ $major->name }} | {{ $major->semester->name }} | {{ $major->term->name }} | {{ $major->year->name }} | ຄ່າຮຽນ: {{ number_format($major->tuition->price, 2) }} ກີບ
                                            </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- No results message -->
                                        <div id="noMajorsMsg" style="display: none; padding: 12px; text-align: center; font-style: italic; color: #6c757d;">
                                            ບໍ່ພົບສາຂາທີ່ຄົ້ນຫາ
                                        </div>
                                    </div>
                                    <input type="hidden" id="selected_major_id" value="">
                                </div>
                                <div class="input-group mt-2">
                                    <button type="button" class="btn btn-primary" id="add-major-btn">
                                        <i class="bi bi-plus-circle me-1"></i> ເພີ່ມ
                                    </button>
                                </div>
                                <div class="form-text text-muted">
                                    ສະແດງສະເພາະສາຂາທີ່ທ່ານໄດ້ລົງທະບຽນແລ້ວແຕ່ຍັງບໍ່ທັນຊຳລະເງິນ. ທ່ານສາມາດເລືອກຫຼາຍສາຂາເພື່ອຊຳລະພ້ອມກັນໄດ້.
                                </div>
                                
                                @if(count($majors) == 0)
                                <div class="alert alert-info mt-2">
                                    <i class="bi bi-info-circle me-2"></i> ທ່ານບໍ່ມີສາຂາທີ່ລົງທະບຽນແລ້ວຍັງບໍ່ໄດ້ຊຳລະເງິນ
                                </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label">ລາຍການສາຂາທີ່ເລືອກ</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="selected-majors-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ສາຂາ</th>
                                                <th>ພາກຮຽນ/ເທີມ/ສົກຮຽນ</th>
                                                <th>ລາຄາ (ກີບ)</th>
                                                <th>ຄຳສັ່ງ</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selected-majors-body">
                                            <tr id="no-majors-row">
                                                <td colspan="4" class="text-center py-3">ຍັງບໍ່ໄດ້ເລືອກສາຂາຮຽນ</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" class="text-end">ລາຄາລວມ:</th>
                                                <th colspan="2" id="total-price">0 ກີບ</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- Hidden input to store selected major IDs -->
                                <input type="hidden" name="major_ids" id="major_ids">
                            </div>
                            
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-success px-5" id="next-step-btn" disabled>ຕໍ່ໄປ <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                        
                        <!-- Step 2: Confirm Information -->
                        <div class="step" id="step2" style="display: none;">
                            <h5 class="mb-4">ຢືນຢັນຂໍ້ມູນການຊຳລະເງິນ</h5>
                            
                            <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">ຂໍ້ມູນນັກສຶກສາ</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>ຊື່-ນາມສະກຸນ:</strong> <span id="student-name">{{ $student->name }} {{ $student->sername }}</span></p>
                                            <p><strong>ອີເມວ:</strong> <span id="student-email">{{ $student->user->email }}</span></p>
                                            <p><strong>ເບີໂທລະສັບ:</strong> <span id="student-phone">{{ $student->tell }}</span></p>
                                        </div>
                                    </div>
                                </div> --}}
                                
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">ຂໍ້ມູນການຊຳລະເງິນ</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>ຈຳນວນສາຂາທີ່ເລືອກ:</strong> <span id="confirm-major-count">0</span> ສາຂາ</p>
                                            <p><strong>ວັນທີຊຳລະເງິນ:</strong> <span id="confirm-payment-date">{{ date('d/m/Y') }}</span></p>
                                            <p><strong>ລາຄາລວມທັງໝົດ:</strong> <span id="confirm-total-amount">0</span> ກີບ</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">ລາຍການສາຂາທີ່ຊຳລະເງິນ</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="confirm-majors-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>ສາຂາ</th>
                                                    <th>ພາກຮຽນ</th>
                                                    <th>ເທີມ</th>
                                                    <th>ສົກຮຽນ</th>
                                                    <th>ລາຄາ (ກີບ)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="confirm-majors-body">
                                                <!-- Will be filled dynamically -->
                                                <tr>
                                                    <td colspan="6" class="text-center">ບໍ່ມີສາຂາທີ່ຖືກເລືອກ</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-end">ລາຄາລວມ:</th>
                                                    <th id="confirm-total-price">0 ກີບ</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(2)"><i class="bi bi-arrow-left me-2"></i> ກັບຄືນ</button>
                                <button type="button" class="btn btn-success px-5" onclick="nextStep(2)">ຕໍ່ໄປ <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Payment -->
                        <div class="step" id="step3" style="display: none;">
                            <h5 class="mb-4">ຊຳລະເງິນ</h5>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card border-success mb-3 h-100">
                                        <div class="card-header bg-success text-white">ຂໍ້ມູນການຊຳລະເງິນ</div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>ວັນທີຊຳລະເງິນ:</strong></td>
                                                    <td>
                                                        <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>ຈຳນວນເງິນທີ່ຕ້ອງຊຳລະ:</strong></td>
                                                    <td class="fs-5 fw-bold text-success" id="payment-amount"></td>
                                                </tr>
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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                                    <label class="form-check-label" for="terms_agreement">
                                        ຂ້າພະເຈົ້າຮັບຮູ້ແລະຍອມຮັບເງື່ອນໄຂທັງໝົດຂອງການລົງທະບຽນແລະນະໂຍບາຍຂອງວິທະຍາໄລ
                                    </label>
                                </div>
                            </div>

                            <!-- Store hidden values for price calculation -->
                            <input type="hidden" name="detail_price" id="detail_price">
                            <input type="hidden" name="total_price" id="total_price">
                            <input type="hidden" name="bill_number" id="bill_number" value="{{ uniqid('BIL-') }}">

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep(3)"><i class="bi bi-arrow-left me-2"></i> ກັບຄືນ</button>
                                <button type="button" class="btn btn-success px-5" id="submit-payment-btn" onclick="simpleSubmit()">
                                    <i class="bi bi-check-circle me-2"></i> ຢືນຢັນການຊຳລະເງິນ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="statusModalLabel">ກຳລັງດຳເນີນການ...</h5>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <div id="loadingSpinner" class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <div id="successIcon" class="text-success fs-1 d-none">
            <i class="bi bi-check-circle-fill"></i>
          </div>
          <div id="errorIcon" class="text-danger fs-1 d-none">
            <i class="bi bi-x-circle-fill"></i>
          </div>
        </div>
        <p id="statusMessage" class="text-center">ກຳລັງສົ່ງຂໍ້ມູນການຊຳລະເງິນ...</p>
        <div id="debugInfo" class="small text-muted mt-3 d-none">
          <hr>
          <p class="mb-1 fw-bold" hidden>ຂໍ້ມູນການດີບັກ:</p>
          <pre id="debugOutput" class="small bg-light p-2 rounded" hidden></pre>
        </div>
      </div>
      <div class="modal-footer d-none" id="modalFooter">
        <a href="{{ route('main') }}" class="btn btn-primary">ກັບຄືນໜ້າຫຼັກ</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
      </div>
    </div>
  </div>
</div>

<script>
    let currentStep = 1;
    let selectedMajors = []; // Array to store selected majors
    let totalPrice = 0;
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Function to update price calculation
    function updatePriceCalculation() {
        // Calculate total price from all selected majors
        totalPrice = selectedMajors.reduce((sum, major) => sum + parseFloat(major.price), 0);
        
        // Update UI
        document.getElementById('total-price').textContent = formatNumber(totalPrice) + ' ກີບ';
        
        // Store values in hidden fields
        document.getElementById('detail_price').value = totalPrice;
        document.getElementById('total_price').value = totalPrice;
        
        // Store major IDs in a hidden input
        document.getElementById('major_ids').value = selectedMajors.map(major => major.id).join(',');
        
        // Enable/disable next button based on selections
        document.getElementById('next-step-btn').disabled = selectedMajors.length === 0;
    }
    
    // Function to add a major to the selected list
    function addMajor() {
        const majorId = document.getElementById('selected_major_id').value;
        console.log("Selected major ID:", majorId);
        
        if (!majorId) {
            alert('ກະລຸນາເລືອກສາຂາກ່ອນເພີ່ມ');
            return;
        }
        
        // Check if already selected
        if (selectedMajors.some(major => major.id === majorId)) {
            alert('ສາຂານີ້ໄດ້ຖືກເລືອກແລ້ວ');
            return;
        }
        
        // Find the selected major element to get its data
        const selectedMajorElement = document.querySelector(`.major-option[data-id="${majorId}"]`);
        
        if (!selectedMajorElement) {
            console.error("Could not find selected major element for ID:", majorId);
            alert('ເກີດຂໍ້ຜິດພາດໃນການເພີ່ມສາຂາ');
            return;
        }
        
        // Get data from the selected element
        const majorData = {
            id: majorId,
            name: selectedMajorElement.getAttribute('data-name'),
            semester: selectedMajorElement.getAttribute('data-semester'),
            term: selectedMajorElement.getAttribute('data-term'),
            year: selectedMajorElement.getAttribute('data-year'),
            price: parseFloat(selectedMajorElement.getAttribute('data-price'))
        };
        
        console.log("Major data collected:", majorData);
        
        // Add to selected majors array
        selectedMajors.push(majorData);
        
        // Update the table
        updateSelectedMajorsTable();
        
        // Reset select
        document.getElementById('selected_major_id').value = '';
        document.getElementById('selectedMajorText').textContent = 'ເລືອກສາຂາທີ່ທ່ານລົງທະບຽນແລ້ວ';
        
        console.log("Major added, selectedMajors now:", selectedMajors);
    }

    // Function to remove a major from the selected list
    function removeMajor(majorId) {
        selectedMajors = selectedMajors.filter(major => major.id !== majorId);
        updateSelectedMajorsTable();
    }
    
    // Function to update the selected majors table
    function updateSelectedMajorsTable() {
        const tableBody = document.getElementById('selected-majors-body');
        const noMajorsRow = document.getElementById('no-majors-row');
        
        // Clear table
        tableBody.innerHTML = '';
        
        // Display no majors message if empty
        if (selectedMajors.length === 0) {
            tableBody.innerHTML = `
                <tr id="no-majors-row">
                    <td colspan="4" class="text-center py-3">ຍັງບໍ່ໄດ້ເລືອກສາຂາຮຽນ</td>
                </tr>
            `;
        } else {
            // Add each major to table
            selectedMajors.forEach(major => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><span class="badge bg-info">${major.name}</span></td>
                    <td>${major.semester} / ${major.term} / ${major.year}</td>
                    <td class="text-end">${formatNumber(major.price)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeMajor('${major.id}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        
        // Update price calculation
        updatePriceCalculation();
    }
    
    // Function to update confirmation screen
    function updateConfirmationScreen() {
        console.log("Updating confirmation screen with majors:", selectedMajors);
        
        const tableBody = document.getElementById('confirm-majors-body');
        const totalElement = document.getElementById('confirm-total-price');
        const paymentAmount = document.getElementById('payment-amount');
        const majorCountElement = document.getElementById('confirm-major-count');
        const totalAmountElement = document.getElementById('confirm-total-amount');
        
        // Clear table
        tableBody.innerHTML = '';
        
        // Show message if no majors
        if (selectedMajors.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center">ບໍ່ມີສາຂາທີ່ຖືກເລືອກ</td>
                </tr>
            `;
        } else {
            // Add each major to table
            selectedMajors.forEach((major, index) => {
                console.log("Adding major to confirmation table:", major);
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><span class="badge bg-info">${major.name}</span></td>
                    <td>${major.semester}</td>
                    <td>${major.term}</td>
                    <td>${major.year}</td>
                    <td class="text-end">${formatNumber(major.price)}</td>
                `;
                tableBody.appendChild(row);
            });
        }
        
        // Update counts and totals
        majorCountElement.textContent = selectedMajors.length;
        totalAmountElement.textContent = formatNumber(totalPrice);
        
        // Update total price in the table footer
        totalElement.textContent = formatNumber(totalPrice) + ' ກີບ';
        
        // Update payment amount in step 3
        if (paymentAmount) {
            paymentAmount.textContent = formatNumber(totalPrice) + ' ກີບ';
        }
    }
    
    // Function to move to the next step
    function nextStep(step) {
        if (step === 1) {
            // Validate step 1
            if (selectedMajors.length === 0) {
                alert('ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາທີ່ຕ້ອງການຊຳລະເງິນ');
                return;
            }
            
            // Update confirmation screen
            updateConfirmationScreen();
        }
        
        // Hide current step
        document.getElementById('step' + step).style.display = 'none';
        
        // Show next step
        document.getElementById('step' + (step + 1)).style.display = 'block';
        
        // Update current step
        currentStep = step + 1;
        
        // Update progress bar
        updateProgressBar();
        
        // Update step indicators
        updateStepIndicators();
    }
    
    // Function to go back to the previous step
    function prevStep(step) {
        // Hide current step
        document.getElementById('step' + step).style.display = 'none';
        
        // Show previous step
        document.getElementById('step' + (step - 1)).style.display = 'block';
        
        // Update current step
        currentStep = step - 1;
        
        // Update progress bar
        updateProgressBar();
        
        // Update step indicators
        updateStepIndicators();
    }
    
    // Function to update the progress bar
    function updateProgressBar() {
        const progressBar = document.getElementById('payment-progress');
        const progress = ((currentStep - 1) / 2) * 100;
        progressBar.style.width = progress + '%';
    }
    
    // Function to update step indicators
    function updateStepIndicators() {
        const stepButtons = document.querySelectorAll('.btn-step');
        
        stepButtons.forEach((button, index) => {
            if (index + 1 < currentStep) {
                // Completed steps
                button.classList.remove('btn-outline-success');
                button.classList.add('btn-success');
                button.innerHTML = '<i class="bi bi-check"></i>';
            } else if (index + 1 === currentStep) {
                // Current step
                button.classList.remove('btn-outline-success');
                button.classList.add('btn-success');
                button.textContent = index + 1;
            } else {
                // Future steps
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-success');
                button.textContent = index + 1;
            }
        });
    }
    
    // Replace the complex form validation with a simpler function
    function validateAndSubmitForm() {
        // Make sure all required fields are filled
        const paymentProof = document.getElementById('payment_proof');
        const termsAgreement = document.getElementById('terms_agreement');
        
        if (!paymentProof.files.length) {
            alert('ກະລຸນາອັບໂຫຼດຫຼັກຖານການຈ່າຍເງິນ');
            return;
        }
        
        if (!termsAgreement.checked) {
            alert('ກະລຸນາຍອມຮັບເງື່ອນໄຂແລະນະໂຍບາຍຂອງວິທະຍາໄລ');
            return;
        }
        
        // Check if we have any majors selected
        if (selectedMajors.length === 0) {
            alert('ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາທີ່ຕ້ອງການຊຳລະເງິນ');
            return;
        }
        
        // Show loading state on button
        const submitBtn = document.getElementById('submit-payment-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> ກຳລັງດຳເນີນການ...';
        
        // Submit the form directly
        document.getElementById('studentPaymentForm').submit();
    }
    
    // Remove the form submit event listener that might be causing issues
    document.addEventListener('DOMContentLoaded', function() {
        // Add major button
        document.getElementById('add-major-btn').addEventListener('click', addMajor);
        
        // Next step button
        document.getElementById('next-step-btn').addEventListener('click', function() {
            nextStep(1);
        });
        
        // Remove any existing event listeners from the form
        const form = document.getElementById('studentPaymentForm');
        const newForm = form.cloneNode(true);
        form.parentNode.replaceChild(newForm, form);
        
        // Re-add the core event listeners
        document.getElementById('add-major-btn').addEventListener('click', addMajor);
        document.getElementById('next-step-btn').addEventListener('click', function() {
            nextStep(1);
        });
    });

    // Modified submission function that directly triggers form submission after validation
    function submitPaymentForm() {
        // Make sure all required fields are filled
        const paymentProof = document.getElementById('payment_proof');
        const termsAgreement = document.getElementById('terms_agreement');
        
        if (!paymentProof.files.length) {
            alert('ກະລຸນາອັບໂຫຼດຫຼັກຖານການຈ່າຍເງິນ');
            return;
        }
        
        if (!termsAgreement.checked) {
            alert('ກະລຸນາຍອມຮັບເງື່ອນໄຂແລະນະໂຍບາຍຂອງວິທະຍາໄລ');
            return;
        }
        
        // Check if we have any majors selected
        if (selectedMajors.length === 0) {
            alert('ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາທີ່ຕ້ອງການຊຳລະເງິນ');
            return;
        }
        
        // Show loading state on button
        const submitBtn = document.getElementById('submit-payment-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> ກຳລັງດຳເນີນການ...';
        
        // Important: Use a timeout to ensure the UI updates before submitting
        setTimeout(function() {
            // Get the form and submit it directly
            const form = document.getElementById('studentPaymentForm');
            
            // Create and append a submission event tracker
            const formSubmitted = document.createElement('input');
            formSubmitted.type = 'hidden';
            formSubmitted.name = 'form_submitted';
            formSubmitted.value = 'true';
            form.appendChild(formSubmitted);
            
            // Submit the form without any event handlers that might interfere
            form.submit();
        }, 100);
    }
    
    // Initialize event listeners without removing form submission capability
    document.addEventListener('DOMContentLoaded', function() {
        // Add major button
        document.getElementById('add-major-btn').addEventListener('click', addMajor);
        
        // Next step button
        document.getElementById('next-step-btn').addEventListener('click', function() {
            nextStep(1);
        });
        
        // No need to clone the form - this was removing event listeners
    });

    // New function to force save with a completely different approach
    function forceSavePayment() {
        // Make sure all required fields are filled
        const paymentProof = document.getElementById('payment_proof');
        const termsAgreement = document.getElementById('terms_agreement');
        
        if (!paymentProof.files.length) {
            alert('ກະລຸນາອັບໂຫຼດຫຼັກຖານການຈ່າຍເງິນ');
            return;
        }
        
        if (!termsAgreement.checked) {
            alert('ກະລຸນາຍອມຮັບເງື່ອນໄຂແລະນະໂຍບາຍຂອງວິທະຍາໄລ');
            return;
        }
        
        // Check if we have any majors selected
        if (selectedMajors.length === 0) {
            alert('ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາທີ່ຕ້ອງການຊຳລະເງິນ');
            return;
        }
        
        try {
            // Show loading state on button
            const submitBtn = document.getElementById('submit-payment-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> ກຳລັງດຳເນີນການ...';
            
            console.log("Force save initiated");
            
            // Fill the backup form with values
            document.getElementById('backup_major_ids').value = selectedMajors.map(major => major.id).join(',');
            document.getElementById('backup_date').value = document.getElementById('date').value;
            document.getElementById('backup_detail_price').value = totalPrice;
            document.getElementById('backup_total_price').value = totalPrice;
            document.getElementById('backup_bill_number').value = document.getElementById('bill_number').value;
            
            // Add debugging info
            document.getElementById('debug_info').value = JSON.stringify({
                'selectedMajors': selectedMajors,
                'totalPrice': totalPrice,
                'step': currentStep,
                'hasFile': paymentProof.files.length > 0
            });
            
            // Handle file upload - clone the file input
            const fileContainer = document.getElementById('backup_file_container');
            fileContainer.innerHTML = ''; // Clear previous
            
            // Clone the original file input to preserve the file
            const clonedFile = paymentProof.cloneNode(true);
            clonedFile.id = 'backup_payment_proof';
            clonedFile.name = 'payment_proof';
            fileContainer.appendChild(clonedFile);
            
            console.log("Backup form populated, submitting now");
            
            // Submit the backup form directly
            document.getElementById('backupForm').submit();
            console.log("Backup form submitted");
            
            return false; // Prevent default behavior
        } catch (error) {
            
            console.error("Error in forceSavePayment:", error);
            alert("ເກີດຂໍ້ຜິດພາດ: " + error.message);
            
            // Re-enable the button
            const submitBtn = document.getElementById('submit-payment-btn');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> ຢືນຢັນການຊຳລະເງິນ';
        }
    }
    
    // Initialize event listeners without removing form submission capability
    document.addEventListener('DOMContentLoaded', function() {
        // Add major button
        document.getElementById('add-major-btn').addEventListener('click', addMajor);
        
        // Next step button
        document.getElementById('next-step-btn').addEventListener('click', function() {
            nextStep(1);
        });
        
        console.log("DOM content loaded, initializing event listeners");
    });

    // Simple submit function that shows a modal and makes a direct submission
    function simpleSubmit() {
        // Validate first
        const paymentProof = document.getElementById('payment_proof');
        const termsAgreement = document.getElementById('terms_agreement');
        
        // Validate form data
        if (!paymentProof.files.length) {
            alert('ກະລຸນາອັບໂຫຼດຫຼັກຖານການຈ່າຍເງິນ');
            return;
        }
        
        if (!termsAgreement.checked) {
            alert('ກະລຸນາຍອມຮັບເງື່ອນໄຂແລະນະໂຍບາຍຂອງວິທະຍາໄລ');
            return;
        }
        
        if (selectedMajors.length === 0) {
            alert('ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາທີ່ຕ້ອງການຊຳລະເງິນ');
            return;
        }
        
        // Show the submission status modal
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        statusModal.show();
        
        // Update debug info
        const debugOutput = document.getElementById('debugOutput');
        document.getElementById('debugInfo').classList.remove('d-none');
        
        // Show what we're submitting
        const formData = {
            major_ids: selectedMajors.map(major => major.id).join(','),
            total_price: totalPrice,
            date: document.getElementById('date').value,
            hasFile: paymentProof.files.length > 0,
            termsChecked: termsAgreement.checked
        };
        
        debugOutput.textContent = JSON.stringify(formData, null, 2);
        
        // Disable the submit button
        document.getElementById('submit-payment-btn').disabled = true;
        
        // Create a normal form submission
        try {
            // Get the form and disable all inputs to preserve their values
            const form = document.getElementById('studentPaymentForm');
            
            // Add a flag to help debug on the server 
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'simple_submit';
            hiddenField.value = 'true';
            form.appendChild(hiddenField);
            
            // Submit the form directly
            setTimeout(() => {
                try {
                    document.getElementById('statusMessage').textContent = 'ກຳລັງສົ່ງຂໍ້ມູນແລະສ້າງໃບບິນ...';
                    
                    form.submit();
                    selectedMajors = []; // Clear selected majors after submission
                    
                    // For debugging, make the modal dismissable after 15 seconds even if no response
                    // (but browser should navigate away to PDF download)
                    setTimeout(() => {
                        document.getElementById('loadingSpinner').classList.add('d-none');
                        document.getElementById('errorIcon').classList.remove('d-none');
                        document.getElementById('statusMessage').textContent = 'ບໍ່ໄດ້ຮັບການຕອບກັບຈາກເຊີບເວີ, ກະລຸນາລອງອີກຄັ້ງ';
                        document.getElementById('modalFooter').classList.remove('d-none');
                    }, 15000);
                    
                } catch (submitError) {
                    console.error('Error during form submission:', submitError);
                    document.getElementById('loadingSpinner').classList.add('d-none');
                    document.getElementById('errorIcon').classList.remove('d-none');
                    document.getElementById('statusMessage').textContent = 'ເກີດຂໍ້ຜິດພາດໃນການສົ່ງແບບຟອມ: ' + submitError.message;
                    document.getElementById('modalFooter').classList.remove('d-none');
                }
            }, 1000);
            
        } catch (error) {
            console.error('Error preparing form submission:', error);
            document.getElementById('loadingSpinner').classList.add('d-none');
            document.getElementById('errorIcon').classList.remove('d-none');
            document.getElementById('statusMessage').textContent = 'ເກີດຂໍ້ຜິດພາດ: ' + error.message;
            document.getElementById('modalFooter').classList.remove('d-none');
        }
    }

    // Initialize event listeners without breaking anything
    document.addEventListener('DOMContentLoaded', function() {
        // Add major button
        document.getElementById('add-major-btn').addEventListener('click', addMajor);
        
        // Next step button
        document.getElementById('next-step-btn').addEventListener('click', function() {
            nextStep(1);
        });
        
        console.log("DOM fully loaded, all event listeners initialized");
    });

    // Process SweetAlert messages from session
    document.addEventListener('DOMContentLoaded', function() {
        // Check for SweetAlert message in the session
        @if(session('sweet_alert'))
            console.log("Found sweet_alert in session, displaying it");
            Swal.fire({
                icon: '{{ session('sweet_alert.type') }}',
                title: '{{ session('sweet_alert.title') }}',
                text: '{{ session('sweet_alert.text') }}',
                confirmButtonText: 'ຕົກລົງ'
            });
        @endif
        
        // For debugging - log any errors or messages
        @if($errors->any())
            console.log("Validation errors found:");
            @foreach($errors->all() as $error)
                console.log("- {{ $error }}");
            @endforeach
        @endif
    });

    // Custom major dropdown functions
    function toggleMajorList() {
        var dropdown = document.getElementById('majorListContainer');
        if (dropdown.style.display === 'none') {
            dropdown.style.display = 'block';
            document.getElementById('majorSearchInput').focus();
        } else {
            dropdown.style.display = 'none';
        }
    }
    
    function filterMajors() {
        var input = document.getElementById('majorSearchInput');
        var filter = input.value.toUpperCase();
        var options = document.getElementsByClassName('major-option');
        var noResultsMsg = document.getElementById('noMajorsMsg');
        var found = false;
        
        for (var i = 0; i < options.length; i++) {
            var txtValue = options[i].textContent || options[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                options[i].style.display = "";
                found = true;
            } else {
                options[i].style.display = "none";
            }
        }
        
        // Show/hide no results message
        noResultsMsg.style.display = found ? 'none' : 'block';
    }
    
    function chooseMajor(id, name, semester, term, year, price) {
        console.log("Major selected: ID:", id, "Name:", name);
        
        // Get the hidden input element
        const hiddenInput = document.getElementById('selected_major_id');
        
        // Set the value directly
        hiddenInput.value = id;
        
        // Update the display text
        document.getElementById('selectedMajorText').textContent = name + ' | ' + semester + ' | ' + term + ' | ' + year;
        
        // Store price for easy access
        hiddenInput.setAttribute('data-price', price);
        
        // Hide dropdown
        document.getElementById('majorListContainer').style.display = 'none';
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('majorListContainer');
        var button = document.querySelector('button[onclick="toggleMajorList()"]');
        
        if (dropdown && button && dropdown.style.display === 'block' && 
            !dropdown.contains(event.target) && event.target !== button) {
            dropdown.style.display = 'none';
        }
    });
    
    // Updated addMajor function to work with the new dropdown
    function addMajor() {
        console.log("addMajor function called");
        
        const majorId = document.getElementById('selected_major_id').value;
        console.log("Selected major ID:", majorId);
        
        if (!majorId) {
            alert('ກະລຸນາເລືອກສາຂາກ່ອນເພີ່ມ');
            return;
        }
        
        // Check if already selected
        if (selectedMajors.some(major => major.id === majorId)) {
            alert('ສາຂານີ້ໄດ້ຖືກເລືອກແລ້ວ');
            return;
        }
        
        // Find the selected major element to get its data
        const selectedMajorElement = document.querySelector(`.major-option[data-id="${majorId}"]`);
        
        if (!selectedMajorElement) {
            console.error("Could not find selected major element for ID:", majorId);
            alert('ເກີດຂໍ້ຜິດພາດໃນການເພີ່ມສາຂາ');
            return;
        }
        
        // Get data from the selected element
        const majorData = {
            id: majorId,
            name: selectedMajorElement.getAttribute('data-name'),
            semester: selectedMajorElement.getAttribute('data-semester'),
            term: selectedMajorElement.getAttribute('data-term'),
            year: selectedMajorElement.getAttribute('data-year'),
            price: parseFloat(selectedMajorElement.getAttribute('data-price'))
        };
        
        console.log("Major data collected:", majorData);
        
        // Add to selected majors array
        selectedMajors.push(majorData);
        
        // Update the table
        updateSelectedMajorsTable();
        
        // Reset select
        document.getElementById('selected_major_id').value = '';
        document.getElementById('selectedMajorText').textContent = 'ເລືອກສາຂາທີ່ທ່ານລົງທະບຽນແລ້ວ';
        
        console.log("Major added, selectedMajors now:", selectedMajors);
    }
    
    // Make sure the button uses the new function
    document.addEventListener('DOMContentLoaded', function() {
        const addMajorBtn = document.getElementById('add-major-btn');
        if (addMajorBtn) {
            // Remove any existing event listeners
            const newBtn = addMajorBtn.cloneNode(true);
            addMajorBtn.parentNode.replaceChild(newBtn, addMajorBtn);
            
            // Add a clean event listener
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                addMajor();
            });
        }
    });


    // Custom student dropdown functions
    function toggleStudentList() {
        var dropdown = document.getElementById('studentListContainer');
        if (dropdown.style.display === 'none') {
            dropdown.style.display = 'block';
            document.getElementById('studentSearchInput').focus();
        } else {
            dropdown.style.display = 'none';
        }
    }
    
    function filterStudents() {
        var input = document.getElementById('studentSearchInput');
        var filter = input.value.toUpperCase();
        
        // Get only the student items, not including the search container
        var studentItems = document.querySelectorAll('#studentListContainer > div:nth-child(2) > div');
        var noResultsMsg = document.getElementById('noStudentsMsg');
        var found = false;
        
        for (var i = 0; i < studentItems.length; i++) {
            // Skip the no results message
            if (studentItems[i].id === 'noStudentsMsg') continue;
            
            var txtValue = studentItems[i].textContent || studentItems[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                studentItems[i].style.display = "";
                found = true;
            } else {
                studentItems[i].style.display = "none";
            }
        }
        
        // Show/hide no results message
        noResultsMsg.style.display = found ? 'none' : 'block';
    }
    
    function chooseStudent(id, name) {
        // Update hidden input value
        document.getElementById('student_id').value = id;
        
        // Update the display text
        document.getElementById('selectedStudentText').textContent = name;
        
        // Hide dropdown
        document.getElementById('studentListContainer').style.display = 'none';
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('studentListContainer');
        var button = document.querySelector('button[onclick="toggleStudentList()"]');
        
        if (dropdown && button && dropdown.style.display === 'block' && 
            !dropdown.contains(event.target) && event.target !== button) {
            dropdown.style.display = 'none';
        }
    });

    // Make an AJAX request to handle the form submission
    fetch('{{ route('student.payment.submit') }}', {
        method: 'POST',
        body: formDataObj,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('loadingSpinner').classList.add('d-none');
        document.getElementById('successIcon').classList.remove('d-none');
        document.getElementById('statusMessage').textContent = 'ການຊຳລະເງິນຂອງທ່ານສຳເລັດແລ້ວ';
        
        // Show modal footer
        document.getElementById('modalFooter').classList.remove('d-none');
        
        // Reset form completely
        resetPaymentForm();
        
        // Open the PDF in a new tab with proper window features
        if (data.pdf_url) {
            // Force new tab with window features specification
            const newWindow = window.open(
                data.pdf_url,
                '_blank',
                'noopener,noreferrer,resizable=yes,status=yes,toolbar=yes,menubar=yes,scrollbars=yes'
            );
            
            if (!newWindow || newWindow.closed || typeof newWindow.closed === 'undefined') {
                // Popup was blocked
                console.warn('PDF popup was blocked. Adding direct link instead.');
                document.getElementById('statusMessage').textContent = 'ການຊຳລະເງິນຂອງທ່ານສຳເລັດແລ້ວ. ກະລຸນາກົດປຸ່ມລິ້ງໄປຫາ PDF.';
                
                // Add a direct link to the PDF in the modal
                const pdfLink = document.createElement('a');
                pdfLink.href = data.pdf_url;
                pdfLink.target = '_blank';
                pdfLink.rel = 'noopener noreferrer';
                pdfLink.className = 'btn btn-info mt-3';
                pdfLink.innerHTML = '<i class="bi bi-file-pdf me-2"></i> ເປີດໃບບິນ PDF';
                
                // Add the link to the modal body
                document.querySelector('.modal-body').appendChild(pdfLink);
            }
        }
    })
    .catch(error => {
        // ...existing error handling code...
    });

</script>

<!-- SweetAlert2 Library (only add if not already included in the layout) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

<style>
    .step-label {
        font-weight: bold;
        white-space: nowrap;
        width: 120px;
        margin-left: -35px;
    }
</style>
@endsection
