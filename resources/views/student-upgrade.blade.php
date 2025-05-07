@extends('Admin.Layout')
@section('title', 'ລົງທະບຽນວິຊາເສີມ')
@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0">ລົງທະບຽນອັບເກດ</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Step progress indicator -->
                    <div class="mb-5">
                        <div class="position-relative mb-5 pt-3 pb-5">
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" id="upgrade-progress"></div>
                            </div>
                            <div class="position-absolute top-0 start-0" style="transform: translateX(-10px) translateY(-10px);">
                                <button type="button" class="btn btn-primary btn-step rounded-circle active" disabled style="width: 3rem; height: 3rem;">1</button>
                                <div class="text-center mt-2 step-label">ເລືອກວິຊາ</div>
                            </div>
                            <div class="position-absolute top-0 end-0" style="transform: translateX(10px) translateY(-10px);">
                                <button type="button" class="btn btn-outline-primary btn-step rounded-circle" disabled style="width: 3rem; height: 3rem;">2</button>
                                <div class="text-center mt-2 step-label">ການຊຳລະເງິນ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form id="studentUpgradeForm" action="{{ route('student.upgrade.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Step 1: Subject Selection -->
                        <div class="step" id="step1">
                            <h5 class="mb-4">ເລືອກວິຊາທີ່ຕ້ອງການລົງທະບຽນ</h5>
                            
                            <!-- Display error messages if any -->
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

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
                                <label for="major_id" class="form-label">ສາຂາທີ່ທ່ານກຳລັງຮຽນ <span class="text-danger">*</span></label>
                                <select class="form-select" id="major_id" name="major_id" required>
                                    <option value="" selected disabled>ເລືອກສາຂາ</option>
                                    @foreach($majors as $major)
                                    <option value="{{ $major->id }}">{{ $major->name }} - {{ $major->term->name ?? '' }} {{ $major->semester->name ?? '' }} {{ $major->year->name ?? '' }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">ກະລຸນາເລືອກສາຂາຮຽນ</div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <div class="row">
                                            <div class="col-md-1 text-center">#</div>
                                            <div class="col-md-5">ຊື່ວິຊາ</div>
                                            <div class="col-md-2 text-center">ໜ່ວຍກິດ</div>
                                            <div class="col-md-2 text-end">ລາຄາ</div>
                                            <div class="col-md-2 text-center">ເລືອກ</div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="subject-list" class="list-group list-group-flush">
                                            <div class="list-group-item text-center py-5">
                                                <p class="text-muted mb-0">ກະລຸນາເລືອກສາຂາກ່ອນ</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div>
                                    <p class="mb-0">ວິຊາທີ່ເລືອກ: <span id="subject-count" class="fw-bold">0</span></p>
                                    <p class="mb-0">ລາຄາລວມ: <span id="total-price" class="fw-bold">0</span> ກີບ</p>
                                </div>
                                <button type="button" id="next-btn" class="btn btn-primary px-4" onclick="nextStep()" disabled>ຕໍ່ໄປ <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>

                        <!-- Step 2: Payment -->
                        <div class="step d-none" id="step2">
                            <h5 class="mb-4">ການຊຳລະເງິນ</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-header bg-primary text-white">ສະຫຼຸບລາຍການ</div>
                                        <div class="card-body p-0">
                                            <table class="table mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>ລາຍການ</th>
                                                        <th class="text-end">ລາຄາ</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="summary-table">
                                                    <!-- Will be populated dynamically -->
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <th>ລວມທັງໝົດ</th>
                                                        <th class="text-end" id="summary-total">0 ກີບ</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card border-success mb-4">
                                        <div class="card-header bg-success text-white">ຂໍ້ມູນການຊຳລະເງິນ</div>
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
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">ວັນທີຊຳລະເງິນ <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="payment_proof" class="form-label">ຫຼັກຖານການຈ່າຍເງິນ <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                <div class="form-text">ອັບໂຫຼດຮູບຖ່າຍໃບບິນ ຫຼື ຮູບຖ່າຍຫນ້າຈໍການໂອນເງິນ (ຂະໜາດບໍ່ເກີນ 2MB)</div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" required>
                                <label class="form-check-label" for="terms_agreement">
                                    ຂ້າພະເຈົ້າຢືນຢັນວ່າຂໍ້ມູນທີ່ໃຫ້ມາແມ່ນຖືກຕ້ອງ ແລະ ຍອມຮັບເງື່ອນໄຂຂອງການລົງທະບຽນອັບເກດ
                                </label>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="prevStep()"><i class="bi bi-arrow-left me-2"></i> ກັບຄືນ</button>
                                <button type="submit" class="btn btn-success px-5"><i class="bi bi-check-circle me-2"></i> ສົ່ງຂໍ້ມູນລົງທະບຽນ</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .step-label {
        width: 120px;
        margin-left: -35px;
        font-weight: bold;
        white-space: nowrap;
    }
    
    .subject-item:hover {
        background-color: #f8f9fa;
    }
    
    .hover-card {
        transition: all 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Current step tracker
    let currentStep = 1;
    const totalSteps = 2;
    
    // Store selected subjects
    let selectedSubjects = [];
    let subjectPrices = {};
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Navigate to next step
    function nextStep() {
        if (selectedSubjects.length === 0) {
            alert('ກະລຸນາເລືອກຢ່າງໜ້ອຍ 1 ວິຊາ');
            return;
        }
        
        // Hide current step
        document.getElementById('step' + currentStep).classList.add('d-none');
        
        // Update summary table
        updateSummary();
        
        // Show next step
        currentStep++;
        document.getElementById('step' + currentStep).classList.remove('d-none');
        
        // Update progress bar
        updateProgress();
        
        // Scroll to top
        window.scrollTo(0, 0);
    }
    
    // Navigate to previous step
    function prevStep() {
        // Hide current step
        document.getElementById('step' + currentStep).classList.add('d-none');
        
        // Show previous step
        currentStep--;
        document.getElementById('step' + currentStep).classList.remove('d-none');
        
        // Update progress bar
        updateProgress();
        
        // Scroll to top
        window.scrollTo(0, 0);
    }
    
    // Update progress bar and step indicators
    function updateProgress() {
        // Update progress bar width
        const progressPercentage = ((currentStep - 1) / (totalSteps - 1)) * 100;
        document.getElementById('upgrade-progress').style.width = progressPercentage + '%';
        
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
    
    // Update the summary table in step 2
    function updateSummary() {
        const summaryTable = document.getElementById('summary-table');
        summaryTable.innerHTML = '';
        
        let totalPrice = 0;
        
        selectedSubjects.forEach((subjectId) => {
            // Ensure the price is treated as a number
            const price = +subjectPrices[subjectId].price;
            const name = subjectPrices[subjectId].name;
            
            totalPrice += price;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${name}</td>
                <td class="text-end">${formatNumber(price)} ກີບ</td>
            `;
            summaryTable.appendChild(row);

            // Add hidden input for subjects
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'subjects[]';
            hiddenInput.value = subjectId;
            document.getElementById('studentUpgradeForm').appendChild(hiddenInput);
        });
        
        document.getElementById('summary-total').textContent = `${formatNumber(totalPrice)} ກີບ`;
    }
    
    // Toggle subject selection
    function toggleSubject(checkboxElem, subjectId) {
        if (checkboxElem.checked) {
            selectedSubjects.push(subjectId);
        } else {
            selectedSubjects = selectedSubjects.filter(id => id != subjectId);
        }
        
        // Update count and total
        updateSelectionSummary();
        
        // Enable/disable next button
        document.getElementById('next-btn').disabled = selectedSubjects.length === 0;
    }
    
    // Update the subject count and total price display
    function updateSelectionSummary() {
        document.getElementById('subject-count').textContent = selectedSubjects.length;
        
        let totalPrice = 0;
        selectedSubjects.forEach((subjectId) => {
            // Ensure the price is treated as a number using the unary plus operator
            totalPrice += +subjectPrices[subjectId].price;
        });
        
        document.getElementById('total-price').textContent = formatNumber(totalPrice);
    }

    // Function to fetch subjects for a major
    function fetchSubjectsForMajor(majorId) {
        // Show loading indicator
        const subjectList = document.getElementById('subject-list');
        subjectList.innerHTML = `
            <div class="list-group-item text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">ກຳລັງໂຫຼດຂໍ້ມູນ...</p>
            </div>
        `;
        
        // Reset selections
        selectedSubjects = [];
        subjectPrices = {};
        updateSelectionSummary();
        document.getElementById('next-btn').disabled = true;
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Fetch subjects for this major
        fetch(`/api/subjects-by-major/${majorId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            
            if (!data || data.length === 0) {
                subjectList.innerHTML = `
                    <div class="list-group-item text-center py-5">
                        <p class="text-muted mb-0">ບໍ່ພົບວິຊາສຳລັບສາຂານີ້</p>
                    </div>
                `;
                return;
            }
            
            subjectList.innerHTML = '';
            
            data.forEach((subject, index) => {
                // Check if subject has credit property
                if (!subject.credit) {
                    console.error('Subject missing credit data:', subject);
                    return;
                }
                
                // Store price for later calculation - ensure it's stored as a number
                subjectPrices[subject.id] = {
                    price: +subject.credit.price, // Convert to number using unary plus
                    name: subject.name
                };
                
                const subjectItem = document.createElement('div');
                subjectItem.className = 'list-group-item subject-item';
                
                subjectItem.innerHTML = `
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">${index + 1}</div>
                        <div class="col-md-5">${subject.name}</div>
                        <div class="col-md-2 text-center">${subject.credit.qty}</div>
                        <div class="col-md-2 text-end">${formatNumber(subject.credit.price)} ກີບ</div>
                        <div class="col-md-2 text-center">
                            <div class="form-check d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" value="${subject.id}" 
                                    id="subject${subject.id}" onchange="toggleSubject(this, ${subject.id})">
                            </div>
                        </div>
                    </div>
                `;
                
                subjectList.appendChild(subjectItem);
            });
        })
        .catch(error => {
            console.error('Error fetching subjects:', error);
            subjectList.innerHTML = `
                <div class="list-group-item text-center py-5">
                    <p class="text-danger mb-0">ເກີດຂໍ້ຜິດພາດໃນການໂຫຼດຂໍ້ມູນ: ${error.message}</p>
                    <button class="btn btn-sm btn-outline-primary mt-2" onclick="retryFetchSubjects()">ລອງໃໝ່ອີກຄັ້ງ</button>
                </div>
            `;
        });
    }
    
    // Document ready
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-select major ID 1 and load its subjects when the page loads
        const majorSelect = document.getElementById('major_id');
        if (majorSelect) {
            // Set the selected value to 1
            majorSelect.value = '1';
            
            // If the option with value 1 doesn't exist, create it
            if (!majorSelect.querySelector('option[value="1"]')) {
                console.warn("Major with ID 1 not found in dropdown. The server might return an error.");
            }
            
            // Trigger the fetch immediately with major ID 1
            fetchSubjectsForMajor(1);
        }
        
        // Major select change handler (keep existing functionality)
        majorSelect.addEventListener('change', function() {
            const majorId = this.value;
            if (!majorId) return;
            fetchSubjectsForMajor(majorId);
        });
        
        // Form submission validation (existing code)
        document.getElementById('studentUpgradeForm').addEventListener('submit', function(e) {
            // Ensure subjects are selected
            if (selectedSubjects.length === 0) {
                alert('ກະລຸນາເລືອກຢ່າງໜ້ອຍ 1 ວິຊາ');
                prevStep();  // Go back to subject selection
                return false;
            }
            
            // Validate payment proof
            const paymentProof = document.getElementById('payment_proof');
            if (paymentProof.files.length === 0) {
                paymentProof.classList.add('is-invalid');
                return false;
            }
            
            // Check terms agreement
            const termsAgreement = document.getElementById('terms_agreement');
            if (!termsAgreement.checked) {
                termsAgreement.classList.add('is-invalid');
                return false;
            }
            
            // Submit the form if all validations pass
            this.submit();
        });
    });

    // Function to retry loading subjects
    function retryFetchSubjects() {
        const majorId = document.getElementById('major_id').value || '1'; // Default to 1 if not set
        fetchSubjectsForMajor(majorId);
    }

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

    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('studentListContainer');
        var button = document.querySelector('button[onclick="toggleStudentList()"]');
        
        if (dropdown && button && dropdown.style.display === 'block' && 
            !dropdown.contains(event.target) && event.target !== button) {
            dropdown.style.display = 'none';
        }
    });
    
    function chooseStudent(id, name) {
        // Update hidden input value
        document.getElementById('student_id').value = id;
        
        // Update the display text
        document.getElementById('selectedStudentText').textContent = name;
        
        // Hide dropdown
        document.getElementById('studentListContainer').style.display = 'none';
    }
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
@endsection
