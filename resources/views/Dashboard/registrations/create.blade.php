@extends('Dashboard.layout')

@section('title', 'ການລົງທະບຽນໃໝ່')

@section('page-title', 'ການລົງທະບຽນໃໝ່')

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

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('registrations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="student_id" class="form-label">ນັກສຶກສາ</label>
                    <select class="form-select select2" id="student_id" name="student_id" required>
                        <option value="">ເລືອກນັກສຶກສາ</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->id }} - {{ $student->name }} {{ $student->sername }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">ວັນທີລົງທະບຽນ</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" required value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pro" class="form-label">ສ່ວນຫຼຸດ (%)</label>
                    <input type="number" class="form-control" id="pro" name="pro" min="0" max="100" value="0" step="0.01" required>
                </div>
                <div class="col-md-6">
                    <p class="form-text text-muted mt-4">
                        <i class="fas fa-info-circle"></i> ການລົງທະບຽນຈະຖືກບັນທຶກພາຍໃຕ້ບັນຊີພະນັກງານຂອງທ່ານ.
                    </p>
                </div>
            </div>

            <!-- Major Filters Section -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">ຕົວກອງສາຂາ</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="year_filter" class="form-label">ປີການສຶກສາ</label>
                            <select class="form-select select2" id="year_filter">
                                <option value="">ທຸກປີ</option>
                                @foreach(App\Models\Year::all() as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="term_filter" class="form-label">ເທີມ</label>
                            <select class="form-select select2" id="term_filter">
                                <option value="">ທຸກເທີມ</option>
                                @foreach(App\Models\Term::all() as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="semester_filter" class="form-label">ພາກຮຽນ</label>
                            <select class="form-select select2" id="semester_filter">
                                <option value="">ທຸກພາກຮຽນ</option>
                                @foreach(App\Models\Semester::all() as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="major_selector" class="form-label">ເພີ່ມສາຂາ</label>
                <div class="input-group">
                    <select class="form-select select2" id="major_selector">
                        <option value="">ເລືອກສາຂາ</option>
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
                    <button type="button" class="btn btn-primary" id="add-major-btn">
                        <i class="fas fa-plus"></i> ເພີ່ມສາຂາ
                    </button>
                </div>
                <small class="form-text text-muted">ເລືອກສາຂາທີ່ຈະລົງທະບຽນ, ແລ້ວຄິກປຸ່ມ "ເພີ່ມສາຂາ"</small>
            </div>

            <div class="mb-3">
                <label class="form-label">ສາຂາທີ່ເລືອກ</label>
                <div class="table-responsive">
                    <table class="table table-striped" id="selected-majors-table">
                        <thead>
                            <tr>
                                <th>ສາຂາ</th>
                                <th>ພາກຮຽນ</th>
                                <th>ເທີມ</th>
                                <th>ປີ</th>
                                <th>ລາຄາ</th>
                                <th>ການກະທຳ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="no-majors-row">
                                <td colspan="6" class="text-center">ຍັງບໍ່ມີສາຂາທີ່ເລືອກ</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">ລວມ:</th>
                                <th id="majors-total">0.00</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">ສ່ວນຫຼຸດ:</th>
                                <th id="discount-amount">0.00</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">ລວມທັງໝົດ:</th>
                                <th id="final-total">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- Hidden input to store selected major IDs -->
                <input type="hidden" name="major_ids" id="major_ids" value="">
            </div>

            <!-- Add payment proof upload field -->
            <div class="mb-3">
                <label for="payment_proof" class="form-label">ຫຼັກຖານການຈ່າຍເງິນ (ຕາມຄວາມຕ້ອງການ)</label>
                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                <div class="form-text text-muted">
                    ອັບໂຫລດໃບຮັບຫຼືຮູບພາບຂອງການຈ່າຍເງິນ (ສູງສຸດ 2MB).
                </div>
                <div class="payment-proof-preview mt-2" style="display: none;"></div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>ລົງທະບຽນນັກສຶກສາ</button>
                <a href="{{ route('registrations.index') }}" class="btn btn-secondary">ຍົກເລີກ</a>
            </div>
        </form>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Format number with commas
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    document.addEventListener('DOMContentLoaded', function() { // Define the variables for dropdowns

        let allMajors = @json($majors);
        const allYears = @json($years);
        const allTerms = @json($terms);
        const allSemesters = @json($semesters);


        initializeDropdowns();

        function initializeDropdowns() {

            // Clear any previous data
            resetAllDropdowns();

            // Populate years dropdown with data from controller
            populateYears();
        }

        // Function to populate years dropdown with data from controller
        function populateYears() {
            const yearSelect = document.getElementById('year_filter');
            if (!yearSelect) {
                console.error('Year select element not found');
                return;
            }

            yearSelect.innerHTML = '<option value="" selected disabled>All Years</option>';

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
                resetDependentDropdowns('term_filter');
                return;
            }

            // Populate terms with all terms data
            populateTerms();
            const termSelect = document.getElementById('term_filter');
            if (termSelect) {
                termSelect.disabled = false;
            }
            resetDependentDropdowns('term_filter');
        }

        // Function to populate terms dropdown - works with embedded data
        function populateTerms() {
            const termSelect = document.getElementById('term_filter');
            if (!termSelect) {
                console.error('Term select element not found');
                return;
            }

            termSelect.innerHTML = '<option value="" selected disabled>All Terms</option>';

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
            const yearSelect = document.getElementById('year_filter');
            const yearId = yearSelect ? yearSelect.value : null;

            if (!termId || !yearId) {
                resetDependentDropdowns('semester_filter');
                return;
            }

            // Populate semesters with all semesters data
            populateSemesters();
            const semesterSelect = document.getElementById('semester_filter');
            if (semesterSelect) {
                semesterSelect.disabled = false;
            }
            resetDependentDropdowns('semester_filter');
        }

        // Function to populate semesters dropdown - works with embedded data
        function populateSemesters() {
            const semesterSelect = document.getElementById('semester_filter');
            if (!semesterSelect) {
                console.error('Semester select element not found');
                return;
            }

            semesterSelect.innerHTML = '<option value="" selected disabled>All Semesters</option>';

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
            const termSelect = document.getElementById('term_filter');
            const termId = termSelect ? termSelect.value : null;
            const yearSelect = document.getElementById('year_filter');
            const yearId = yearSelect ? yearSelect.value : null;
            const addButton = document.getElementById('add-major-btn');

            // Disable add button by default
            if (addButton) {
                addButton.disabled = true;
            }

            if (!semesterId || !termId || !yearId) {
                resetDependentDropdowns('major_selector');
                return;
            }

            // Filter majors based on selected year, term, and semester
            populateFilteredMajors(yearId, termId, semesterId);
            const majorSelect = document.getElementById('major_selector');
            if (majorSelect) {
                majorSelect.disabled = false;
            }
        }

        // Function to populate majors dropdown with filtered data
        function populateFilteredMajors(yearId, termId, semesterId) {

            const majorSelect = document.getElementById('major_selector');
            if (!majorSelect) {
                console.error('Major select element not found');
                return;
            }

            majorSelect.innerHTML = '<option value="" selected disabled>Select Major</option>';

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
                    addButton.disabled = true;
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
                const semesterSelect = document.getElementById('semester_filter');
                const majorSelect = document.getElementById('major_selector');

                if (semesterSelect) {
                    semesterSelect.innerHTML = '<option value="" selected disabled>All Semesters</option>';
                    semesterSelect.disabled = true;
                }
                if (majorSelect) {
                    majorSelect.innerHTML = '<option value="" selected disabled>Select Major</option>';
                    majorSelect.disabled = true;
                }
                if (addButton) {
                    addButton.disabled = true;
                }
            } else if (startFrom === 'semester_filter') {
                const majorSelect = document.getElementById('major_selector');

                if (majorSelect) {
                    majorSelect.innerHTML = '<option value="" selected disabled>Select Major</option>';
                    majorSelect.disabled = true;
                }
                if (addButton) {
                    addButton.disabled = true;
                }
            }
        }

        // Reset all dropdowns
        function resetAllDropdowns() {
            const yearSelect = document.getElementById('year_filter');
            const termSelect = document.getElementById('term_filter');
            const semesterSelect = document.getElementById('semester_filter');
            const majorSelect = document.getElementById('major_selector');
            const addButton = document.getElementById('add-major-btn');

            if (yearSelect) {
                yearSelect.innerHTML = '<option value="" selected disabled>All Years</option>';
            }
            if (termSelect) {
                termSelect.innerHTML = '<option value="" selected disabled>All Terms</option>';
                termSelect.disabled = true;
            }
            if (semesterSelect) {
                semesterSelect.innerHTML = '<option value="" selected disabled>All Semesters</option>';
                semesterSelect.disabled = true;
            }
            if (majorSelect) {
                majorSelect.innerHTML = '<option value="" selected disabled>Select Major</option>';
                majorSelect.disabled = true;
            }
            if (addButton) {
                addButton.disabled = true;
            }
        }

    });


    // Store selected majors
    let selectedMajors = [];
    let selectedMajorsData = {};
    let totalMajorPrice = 0;

    // Update selected majors table
    function updateSelectedMajorsTable() {
       
        const tableBody = $('#selected-majors-table tbody');
        const majorCount = $('#selected-major-count');
        const majorTotalPrice = $('#major-total-price');

        // Clear table
        tableBody.empty();

        // Update count
        majorCount.text(selectedMajors.length);

        // Display no majors message if empty
        if (selectedMajors.length === 0) {
            tableBody.html(`
            <tr id="no-majors-row">
                <td colspan="7" class="text-center py-3">ຍັງບໍ່ມີສາຂາທີ່ຖືກເລືອກ</td>
            </tr>
        `);
            majorTotalPrice.text('0 ກີບ');
            
        }

        // Reset total price
        totalMajorPrice = 0;

    
        // Add each major to table
        selectedMajors.forEach((majorId, index) => {


            const mjid = majorId['id'];
            const majorData = selectedMajorsData[mjid];

            totalMajorPrice += majorData.price;

            const row = `
            <tr>
                <td>${index + 1}</td>
                <td><span class="badge bg-info">${majorData.name}</span></td>
                <td>${majorData.semester}</td>
                <td>${majorData.term}</td>
                <td>${majorData.year}</td>
                <td class="text-end">${formatNumber(majorData.price)} ກີບ</td>
                <td class="text-center">
                   <button type="button" class="btn btn-sm btn-danger " onclick="removeMajor('${mjid}')">
                          X
                    </button>
                </td>
            </tr>
        `;
            tableBody.append(row);
        });

       
        // Update major IDs input
        const majorIdsInput = document.getElementById('major_ids');
        console
    if (majorIdsInput) {
        // Join the selectedMajors array directly since it already contains the IDs
        
        const majorIds = selectedMajors.map(major => major.id);
        majorIdsInput.value = majorIds.join(',');
        console.log("Selected Major IDs:", majorIdsInput.value);
    

    }

        // Update discount based on number of majors
        // if (selectedMajors.length >= 2) {
        //     $('#pro').val(30).prop('disabled', true);
        // } else {
        //     $('#pro').val(0).prop('disabled', false);
        // }

        if (selectedMajors.length >= 1) {
            $('#submit-btn').prop('disabled', false);
        } else {
            $('#submit-btn').prop('disabled', true);
        }

        // Update total price display
        majorTotalPrice.text(formatNumber(totalMajorPrice) + ' ກີບ');

        // Update totals
        updateTotals();


    }

    function removeMajor(majorId) {
     
            console.log("majorId: ", majorId);
            selectedMajors = selectedMajors.filter(major => major.id !== majorId);
            console.log("selectedMajors: ", selectedMajors);
            delete selectedMajorsData[majorId];

            updateSelectedMajorsTable();

            // Remove hidden inputs
            const hiddenInputs = document.querySelectorAll(`input[name="major_ids"][value="${majorId}"]`);
            hiddenInputs.forEach(input => input.remove());
            
    }

    $(document).ready(function() {
       

        $('#add-major-btn').on('click', function() {
            console.log("Add Major button clicked");
            const majorSelect = $('#major_selector');
            const selectedOption = majorSelect.find('option:selected');
            const majorId = selectedOption.val();

            if (!majorId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Major Selected',
                    text: 'Please select a major to add'
                });
                return;
            }

            // Check for duplicate
            if (selectedMajors.some(m => m.id === majorId)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Major',
                    text: 'This major has already been added'
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

            console.log("Major Data: ", majorData);

            // Add to selected majors array
            selectedMajorsData[majorId] = majorData;
            selectedMajors.push(majorData);
            console.log("selectedMajorsData : ", selectedMajorsData[majorId]);
            console.log("selectedMajors Data: ", selectedMajors);

            // Update the table
            updateSelectedMajorsTable();

            // Reset select
            majorSelect.val('');
        });

        // Update totals when discount changes
        $('#pro').on('change input', updateTotals);

        // File input preview
        $('#payment_proof').on('change', function() {
            const file = this.files[0];
            const preview = $('.payment-proof-preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.html(`<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-height: 150px;">`);
                    preview.show();
                };

                reader.readAsDataURL(file);
            } else {
                preview.empty().hide();
            }
        });
    });


    // // Function to update price totals
    function updateTotals() {
        const totalElement = $('#majors-total');
        const discountElement = $('#discount-amount');
        const finalTotalElement = $('#final-total');
        const discountPercent = parseFloat($('#pro').val()) || 0;

        // Calculate base total
        const total = selectedMajors.reduce((sum, major) => sum + major.price, 0);

        console.log("Total: ", total);

        // Calculate discount
        const discountAmount = (total * discountPercent / 100);
        const finalTotal = total - discountAmount;

        // Update displays
        totalElement.text(total.toFixed(2));
        discountElement.text(discountAmount.toFixed(2));
        finalTotalElement.text(finalTotal.toFixed(2));
    }
</script>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $('.select2').select2();

    });
</script>
@endsection