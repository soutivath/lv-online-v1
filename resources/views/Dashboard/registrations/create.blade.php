@extends('Dashboard.layout')

@section('title', 'New Registration')

@section('page-title', 'New Registration')

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
                    <label for="student_id" class="form-label">Student</label>
                    <select class="form-select select2" id="student_id" name="student_id" required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->id }} - {{ $student->name }} {{ $student->sername }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">Registration Date</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" required value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pro" class="form-label">Discount (%)</label>
                    <input type="number" class="form-control" id="pro" name="pro" min="0" max="100" value="0" step="0.01" required>
                </div>
                <div class="col-md-6">
                    <p class="form-text text-muted mt-4">
                        <i class="fas fa-info-circle"></i> Registration will be recorded under your employee account.
                    </p>
                </div>
            </div>
            
            <!-- Major Filters Section -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Filter Majors</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="year_filter" class="form-label">Academic Year</label>
                            <select class="form-select select2" id="year_filter">
                                <option value="">All Years</option>
                                @foreach(App\Models\Year::all() as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="term_filter" class="form-label">Term</label>
                            <select class="form-select select2" id="term_filter">
                                <option value="">All Terms</option>
                                @foreach(App\Models\Term::all() as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="semester_filter" class="form-label">Semester</label>
                            <select class="form-select select2" id="semester_filter">
                                <option value="">All Semesters</option>
                                @foreach(App\Models\Semester::all() as $semester)
                                    <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="major_selector" class="form-label">Add Majors</label>
                <div class="input-group">
                    <select class="form-select select2" id="major_selector">
                        <option value="">Select Major</option>
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
                        <i class="fas fa-plus"></i> Add Major
                    </button>
                </div>
                <small class="form-text text-muted">Select majors to register, then click "Add Major" button</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Selected Majors</label>
                <div class="table-responsive">
                    <table class="table table-striped" id="selected-majors-table">
                        <thead>
                            <tr>
                                <th>Major</th>
                                <th>Semester</th>
                                <th>Term</th>
                                <th>Year</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="no-majors-row">
                                <td colspan="6" class="text-center">No majors selected</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total:</th>
                                <th id="majors-total">0.00</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Discount:</th>
                                <th id="discount-amount">0.00</th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-end">Final Total:</th>
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
                <label for="payment_proof" class="form-label">Payment Proof (Optional)</label>
                <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                <div class="form-text text-muted">
                    Upload a receipt or screenshot of your payment (max 2MB).
                </div>
                <div class="payment-proof-preview mt-2" style="display: none;"></div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Register Student</button>
                <a href="{{ route('registrations.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        
        let selectedMajors = [];
        let allMajors = @json($majors);
        
        // Filter change event handlers
        $('#year_filter, #term_filter, #semester_filter').on('change', function() {
            filterMajors();
        });
        
        function filterMajors() {
            const yearId = $('#year_filter').val();
            const termId = $('#term_filter').val();
            const semesterId = $('#semester_filter').val();
            
            // Show loading indicator
            $('#major_selector').html('<option value="">Loading majors...</option>');
            
            // Fetch filtered majors from server
            $.ajax({
                url: "{{ route('majors.filtered') }}",
                type: "GET",
                data: {
                    year_id: yearId,
                    term_id: termId,
                    semester_id: semesterId
                },
                dataType: 'json',
                success: function(response) {
                    // Clear current options and add default option
                    $('#major_selector').empty().append('<option value="">Select Major</option>');
                    
                    // Add filtered majors
                    if (response.majors && response.majors.length > 0) {
                        response.majors.forEach(function(major) {
                            // Check if this major is already selected
                            if (!selectedMajors.some(m => m.id === major.id.toString())) {
                                $('#major_selector').append(
                                    `<option value="${major.id}" 
                                        data-id="${major.id}"
                                        data-name="${major.name}"
                                        data-semester="${major.semester.name}"
                                        data-term="${major.term.name}"
                                        data-year="${major.year.name}"
                                        data-price="${major.tuition.price}"
                                        data-semester-id="${major.semester_id}"
                                        data-term-id="${major.term_id}"
                                        data-year-id="${major.year_id}">
                                        ${major.name} | ${major.semester.name} | ${major.term.name} | ${major.year.name} | Fee: ${parseFloat(major.tuition.price).toFixed(2)}
                                    </option>`
                                );
                            }
                        });
                    } else {
                        $('#major_selector').append('<option value="" disabled>No majors match the selected filters</option>');
                    }
                    
                    // Refresh Select2
                    $('#major_selector').trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching filtered majors:", error);
                    console.log("Response:", xhr.responseText);
                    $('#major_selector').html('<option value="">Error loading majors. Please try again.</option>');
                }
            });
        }
        
        // Add major button click handler
        $('#add-major-btn').on('click', function() {
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
            
            // Add to selected majors array
            selectedMajors.push(majorData);
            
            // Update the table
            updateMajorsTable();
            
            // Reset select
            majorSelect.val('').trigger('change');
        });
        
        // Function to update majors table
        function updateMajorsTable() {
            const tbody = $('#selected-majors-table tbody');
            const noMajorsRow = $('#no-majors-row');
            const submitBtn = $('#submit-btn');
            
            // Clear current rows
            tbody.empty();
            
            if (selectedMajors.length === 0) {
                tbody.append(noMajorsRow);
                submitBtn.prop('disabled', true);
                updateTotals();
                return;
            }
            
            // Enable submit button
            submitBtn.prop('disabled', false);
            
            // Add rows for each selected major
            selectedMajors.forEach((major, index) => {
                const row = `
                    <tr data-major-id="${major.id}">
                        <td>${major.name}</td>
                        <td>${major.semester}</td>
                        <td>${major.term}</td>
                        <td>${major.year}</td>
                        <td>${major.price.toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-major" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
            
            // Update major IDs hidden input
            $('#major_ids').val(selectedMajors.map(m => m.id).join(','));
            
            // Attach remove handlers
            $('.remove-major').on('click', function() {
                const index = $(this).data('index');
                selectedMajors.splice(index, 1);
                updateMajorsTable();
            });
            
            // Update totals
            updateTotals();
        }
        
        // Function to update price totals
        function updateTotals() {
            const totalElement = $('#majors-total');
            const discountElement = $('#discount-amount');
            const finalTotalElement = $('#final-total');
            const discountPercent = parseFloat($('#pro').val()) || 0;
            
            // Calculate base total
            const total = selectedMajors.reduce((sum, major) => sum + major.price, 0);
            
            // Calculate discount
            const discountAmount = (total * discountPercent / 100);
            const finalTotal = total - discountAmount;
            
            // Update displays
            totalElement.text(total.toFixed(2));
            discountElement.text(discountAmount.toFixed(2));
            finalTotalElement.text(finalTotal.toFixed(2));
        }
        
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
</script>
@endsection
