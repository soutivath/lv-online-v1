@extends('Admin.Layout')
@section('title', 'ລົງທະບຽນນັກສຶກສາ')
@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0">ຟອມລົງທະບຽນນັກສຶກສາ</h4>
                </div>
                <div class="card-body p-4">
                    <form id="studentRegistrationForm" action="{{ route('student.register') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Display validation errors -->
                        @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Step 1: Personal Information + Major Selection (moved from step 2) -->
                        <div class="step" id="step1">
                            <h5 class="border-bottom pb-2 mb-3">ຂໍ້ມູນສ່ວນຕົວ ແລະ ສາຂາຮຽນ</h5>
                            
                            <!-- Personal Information Fields -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">ຊື່ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sername" class="form-label">ນາມສະກຸນ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sername" name="sername" value="{{ old('sername') }}" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">ເພດ <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="" selected disabled>ເລືອກເພດ</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ຊາຍ</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>ຍິງ</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dob" class="form-label">ວັນເດືອນປີເກີດ <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                                    <!-- Add hidden birthday field that maps to dob -->
                                    <input type="hidden" name="birthday" id="birthday" value="{{ old('birthday') }}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nationality" class="form-label">ສັນຊາດ <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality', 'ລາວ') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">ທີ່ຢູ່ <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address') }}</textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="tell" class="form-label">ເບີໂທ <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="tell" name="tell" value="{{ old('tell') }}" 
                                           pattern="[0-9]+" inputmode="numeric" required>
                                    <div class="form-text">ປ້ອນຕົວເລກເທົ່ານັ້ນ</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="document_score" class="form-label">ຄະແນນສະເລ່ຍ</label>
                                    <input type="file" class="form-control" id="document_score" name="document_score" accept="image/*,application/pdf">
                                    <div class="form-text">ອັບໂຫລດເອກະສານໃບຄະແນນ (ຖ້າມີ)</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="img" class="form-label">ຮູບຖ່າຍ</label>
                                    <input type="file" class="form-control" id="img" name="img" accept="image/*">
                                    <div class="form-text">ຮູບຖ່າຍຂະໜາດ 3x4 (ຖ້າມີ)</div>
                                </div>
                            </div>
                            
                            <!-- Major Selection (moved from step 2) -->
                            <div class="mt-4">
                                <h5 class="border-bottom pb-2 mb-3">ເລືອກສາຂາຮຽນ</h5>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="major_selector" class="form-label">ສາຂາທີ່ຕ້ອງການລົງທະບຽນ <span class="text-danger">*</span></label>
                                        <div style="position: relative; width: 100%;">
                                            <!-- Display button -->
                                            <button type="button" onclick="toggleMajorList()" 
                                                    style="width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; padding: 8px 12px; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                                                <span id="selectedMajorText">ເລືອກສາຂາ</span>
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
                                                    <div onclick="chooseMajor('{{ $major->id }}', '{{ $major->name }}', '{{ $major->semester->name }}', '{{ $major->term->name }}', '{{ $major->year->name }}')"
                                                         data-id="{{ $major->id }}"
                                                         data-name="{{ $major->name }}"
                                                         data-semester="{{ $major->semester->name }}"
                                                         data-term="{{ $major->term->name }}"
                                                         data-year="{{ $major->year->name }}"
                                                         class="major-option"
                                                         style="padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #f5f5f5;">
                                                        <i class="bi bi-book me-2"></i>
                                                        {{ $major->name }} | {{ $major->semester->name }} | {{ $major->term->name }} | {{ $major->year->name }}
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
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">ສາຂາທີ່ເລືອກ</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="selected-majors-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ລຳດັບ</th>
                                                    <th>ສາຂາຮຽນ</th>
                                                    <th>ພາກຮຽນ</th>
                                                    <th>ເທີມ</th>
                                                    <th>ສົກຮຽນ</th>
                                                    <th>ຄຳສັ່ງ</th>
                                                </tr>
                                            </thead>
                                            <tbody id="selected-majors-body">
                                                <tr id="no-majors-row">
                                                    <td colspan="6" class="text-center py-3">ຍັງບໍ່ໄດ້ເລືອກສາຂາຮຽນ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Hidden input to store selected major IDs -->
                                    <input type="hidden" name="major_ids" id="major_ids" value="">
                                </div>
                            </div>
                            
                       
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <i class="bi bi-check-circle me-2"></i> ລົງທະບຽນ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Store selected majors
    let selectedMajors = [];
    
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
    // Phone number validation - only allow numbers
    document.addEventListener('DOMContentLoaded', function() {
        const tellField = document.getElementById('tell');
        if (tellField) {
            tellField.addEventListener('input', function(e) {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
            });
            
            tellField.addEventListener('keypress', function(e) {
                // Allow only numeric input (0-9)
                const charCode = (e.which) ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    e.preventDefault();
                    return false;
                }
                return true;
            });
        }
        
        // ...existing code...
    });
    
    // Function to update selected majors table
    function updateSelectedMajorsTable() {
        const tableBody = document.getElementById('selected-majors-body');
        const noMajorsRow = document.getElementById('no-majors-row');
        
        // Clear table body
        tableBody.innerHTML = '';
        
        // Display no majors message if empty
        if (selectedMajors.length === 0) {
            tableBody.innerHTML = `
                <tr id="no-majors-row">
                    <td colspan="6" class="text-center py-3">ຍັງບໍ່ໄດ້ເລືອກສາຂາຮຽນ</td>
                </tr>
            `;
            
            document.getElementById('submit-btn').disabled = true;
        } else {
            // Enable submit button if we have majors
            document.getElementById('submit-btn').disabled = false;
            
            // Add each major to table
            selectedMajors.forEach((major, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${major.name}</td>
                    <td>${major.semester}</td>
                    <td>${major.term}</td>
                    <td>${major.year}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeMajor('${major.id}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        
        // Update the hidden field with major IDs - This is the critical part!
        const majorIdsInput = document.getElementById('major_ids');
        const majorIds = selectedMajors.map(major => major.id).join(',');
        majorIdsInput.value = majorIds;
        
        // Add debugging to confirm IDs are correctly set
        console.log("Selected majors:", selectedMajors);
        console.log("Major IDs value:", majorIdsInput.value);
    }
    
    // Updated addMajor function with better handling for the selected major
    function addMajor() {
        console.log("addMajor function called");
        
        // Get the selected major ID directly from the hidden input
        const majorId = document.getElementById('selected_major_id').value;
        console.log("Current selected_major_id value:", majorId);
        
        if (!majorId || majorId === '') {
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
            console.log("All major-option elements:", document.querySelectorAll('.major-option').length);
            alert('ເກີດຂໍ້ຜິດພາດໃນການເພີ່ມສາຂາ');
            return;
        }
        
        // Get data from the selected element
        const majorData = {
            id: majorId,
            name: selectedMajorElement.getAttribute('data-name'),
            semester: selectedMajorElement.getAttribute('data-semester'),
            term: selectedMajorElement.getAttribute('data-term'),
            year: selectedMajorElement.getAttribute('data-year')
        };
        
        console.log("Major data collected:", majorData);
        
        // Add to selected majors array
        selectedMajors.push(majorData);
        
        // Update the table
        updateSelectedMajorsTable();
        
        // Reset select
        document.getElementById('selected_major_id').value = '';
        document.getElementById('selectedMajorText').textContent = 'ເລືອກສາຂາ';
        
        console.log("Major added, selectedMajors now:", selectedMajors);
    }
    
    // Function to remove a major from selected list
    function removeMajor(majorId) {
        selectedMajors = selectedMajors.filter(major => major.id !== majorId);
        updateSelectedMajorsTable();
    }
    
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM fully loaded");
        
        // Ensure form inputs are not disabled
        const formInputs = document.querySelectorAll('#studentRegistrationForm input, #studentRegistrationForm select, #studentRegistrationForm textarea');
        formInputs.forEach(input => {
            input.disabled = false;
        });
        
        // PROPERLY set up a single event handler for the add major button
        const addMajorBtn = document.getElementById('add-major-btn');
        if (addMajorBtn) {
            console.log("Found add-major-btn, setting up click handler");
            
            // Remove any existing onclick attribute
            addMajorBtn.removeAttribute('onclick');
            
            // Clear any existing event listeners by cloning and replacing
            const newBtn = addMajorBtn.cloneNode(true);
            addMajorBtn.parentNode.replaceChild(newBtn, addMajorBtn);
            
            // Add a single, clean event listener
            newBtn.addEventListener('click', function(e) {
                console.log("Add button clicked - single event");
                e.preventDefault();
                e.stopPropagation(); // Stop event propagation to prevent multiple triggers
                addMajor();
            });
        } else {
            console.error("Could not find add-major-btn element");
        }
        
        // Make sure submit button is working correctly
        const form = document.getElementById('studentRegistrationForm');
        if (form) {
            form.onsubmit = function(e) {
                // Basic validation
                const requiredFields = form.querySelectorAll('[required]');
                let valid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value) {
                        valid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    alert('ກະລຸນາປ້ອນຂໍ້ມູນທີ່ຕ້ອງການໃຫ້ຄົບຖ້ວນ');
                    return false;
                }
                
                if (selectedMajors.length === 0) {
                    e.preventDefault();
                    alert('ກະລຸນາເລືອກຢ່າງນ້ອຍໜຶ່ງສາຂາ');
                    return false;
                }
                
                // Log form data being sent
                console.log("Form submission - Selected Majors:", selectedMajors);
                console.log("Form submission - Major IDs value:", document.getElementById('major_ids').value);
                
                // Debug log of FormData
                const formData = new FormData(form);
                console.log("Form data entries:");
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }
                
                // Show processing modal
                const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                document.getElementById('modalSpinner').classList.remove('d-none');
                document.getElementById('modalSuccess').classList.add('d-none');
                document.getElementById('modalError').classList.add('d-none');
                resultModal.show();
                
                // Submit the form using fetch with redirect handling
                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    // Important: this allows fetch to follow redirects
                    redirect: 'follow'
                })
                .then(response => {
                    // Hide spinner
                    document.getElementById('modalSpinner').classList.add('d-none');
                    
                    // Check for session flash messages by fetching the page we were redirected to
                    if (response.redirected) {
                        // We need to fetch the page we were redirected to check for flash messages
                        return fetch(response.url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(redirectResponse => {
                            // Close the modal, we'll use SweetAlert for messages
                            resultModal.hide();
                            
                            // After redirection, check if there's a sweet_alert in the session
                            // This is handled by the SweetAlert initialization code that runs on page load
                            window.location.href = response.url;
                            
                            return null;
                        });
                    }
                    
                    if (response.ok) {
                        // Show success message
                        document.getElementById('modalSuccess').classList.remove('d-none');
                        document.getElementById('closeModalBtn').classList.add('d-none');
                    } else {
                        // Show error message
                        document.getElementById('modalError').classList.remove('d-none');
                        document.getElementById('errorMessage').textContent = 'ເກີດຂໍ້ຜິດພາດ: ' + response.status + ' ' + response.statusText;
                    }
                })
                .catch(error => {
                    // Hide spinner and show error
                    document.getElementById('modalSpinner').classList.add('d-none');
                    document.getElementById('modalError').classList.remove('d-none');
                    document.getElementById('errorMessage').textContent = 'ເກີດຂໍ້ຜິດພາດ: ' + error.message;
                });
                
                // Prevent default form submission
                return false;
            };
        }

        // Initial sync of birthday field with dob
        const dobField = document.getElementById('dob');
        const birthdayField = document.getElementById('birthday');
        
        if(dobField && birthdayField) {
            // Set initial value if dob has a value
            if(dobField.value) {
                birthdayField.value = dobField.value;
            }
            
            // Update birthday field whenever dob changes
            dobField.addEventListener('change', function() {
                birthdayField.value = this.value;
            });
        }
    });

    // Process SweetAlert messages from session
    document.addEventListener('DOMContentLoaded', function() {
        // Check for SweetAlert message in the session
        @if(session('sweet_alert'))
            // Check if we've already shown this alert in this browser session
            const alertKey = 'alert_shown_{{ md5(json_encode(session('sweet_alert'))) }}';
            const alreadyShown = sessionStorage.getItem(alertKey);
            
            if (!alreadyShown) {
                Swal.fire({
                    icon: '{{ session('sweet_alert.type') }}',
                    title: '{{ session('sweet_alert.title') }}',
                    text: '{{ session('sweet_alert.text') }}',
                    confirmButtonText: 'ຕົກລົງ'
                });
                
                // Mark this specific alert as shown
                sessionStorage.setItem(alertKey, 'true');
            }
            
            // Clear all shown alerts when leaving the page
            window.addEventListener('beforeunload', function() {
                // Don't clear on redirect to PDF
                if (!window.location.href.includes('export-pdf')) {
                    sessionStorage.clear();
                }
            });
        @endif
    });
    
    // Enhance form submit to show loading state
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('studentRegistrationForm');
        if (form) {
            const originalSubmit = form.onsubmit;
            
            form.onsubmit = function(e) {
                // Call the original validation function first
                if (originalSubmit && !originalSubmit(e)) {
                    return false;
                }
                
                // Show loading state on the submit button
                const submitBtn = document.getElementById('submit-btn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> ກຳລັງດຳເນີນການ...';
                }
                
                return true;
            };
        }
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
    
    // Completely rewritten chooseMajor function to ensure it properly sets the value
    function chooseMajor(id, name, semester, term, year) {
        console.log("chooseMajor called with ID:", id);
        
        // Get the hidden input element
        const hiddenInput = document.getElementById('selected_major_id');
        
        // Set the value directly
        hiddenInput.value = id;
        
        // Verify the value was set
        console.log("hidden input value after setting:", hiddenInput.value);
        
        // Update the display text
        document.getElementById('selectedMajorText').textContent = name + ' | ' + semester + ' | ' + term + ' | ' + year;
        
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
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection