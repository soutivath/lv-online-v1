@extends('Dashboard.layout')

@section('title', 'New Grade Upgrade')

@section('page-title', 'New Grade Upgrade')


@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('upgrades.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
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
                <div class="col-md-6">
                    <label for="date" class="form-label">Upgrade Date</label>
                    <input type="date" class="form-control" id="date" name="date" required value="{{ now()->format('Y-m-d') }}">
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

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="major_id" class="form-label">Major</label>
                    <select class="form-select select2" id="major_id" name="major_id" required>
                        <option value="">Select Major</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}">
                                {{ $major->name }} | {{ $major->semester->name }} | {{ $major->term->name }} | {{ $major->year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <p class="form-text text-muted mt-4">
                        <i class="fas fa-info-circle"></i> Upgrade will be recorded under your employee account.
                    </p>
                </div>
            </div>

            <div class="mb-3">
                <label for="subjects" class="form-label">Select Subjects</label>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @foreach($subjects as $subject)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input subject-checkbox" type="checkbox" value="{{ $subject->id }}" 
                                        id="subject_{{ $subject->id }}" name="subjects[]" 
                                        data-name="{{ $subject->name }}" 
                                        data-credits="{{ $subject->credit->qty }}" 
                                        data-price="{{ $subject->credit->price }}">
                                    <label class="form-check-label" for="subject_{{ $subject->id }}">
                                        {{ $subject->name }} ({{ $subject->credit->qty }} credits - {{ number_format($subject->credit->price, 2) }})
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Selected Subjects</label>
                <div class="table-responsive">
                    <table class="table table-striped" id="selected-subjects-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Credits</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="no-subjects-row">
                                <td colspan="3" class="text-center">No subjects selected</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Total:</th>
                                <th id="total-amount">0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
                <button type="submit" class="btn btn-primary">Create Upgrade</button>
                <a href="{{ route('upgrades.index') }}" class="btn btn-secondary">Cancel</a>
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
    
    document.addEventListener('DOMContentLoaded', function() {
        // Filter change event handlers
        $('#year_filter, #term_filter, #semester_filter').on('change', function() {
            filterMajors();
        });
        
        function filterMajors() {
            const yearId = $('#year_filter').val();
            const termId = $('#term_filter').val();
            const semesterId = $('#semester_filter').val();
            
            // Show loading indicator
            $('#major_id').html('<option value="">Loading majors...</option>');
            
            $.ajax({
                url: "{{ route('majors.filtered') }}",
                type: "GET",
                data: {
                    year_id: yearId,
                    term_id: termId,
                    semester_id: semesterId
                },
                dataType: 'json',
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Clear current options and add default option
                    $('#major_id').empty().append('<option value="">Select Major</option>');
                    
                    // Add filtered majors
                    if (response.majors && response.majors.length > 0) {
                        response.majors.forEach(function(major) {
                            $('#major_id').append(
                                `<option value="${major.id}">
                                    ${major.name} | ${major.semester.name} | ${major.term.name} | ${major.year.name}
                                </option>`
                            );
                        });
                    } else {
                        $('#major_id').append('<option value="" disabled>No majors match the selected filters</option>');
                    }
                    
                    // Refresh Select2
                    $('#major_id').trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching filtered majors:", error);
                    console.log("Response:", xhr.responseText);
                    $('#major_id').html('<option value="">Error loading majors. Please try again.</option>');
                }
            });
        }
        
        // Handle subject checkbox changes
        $('.subject-checkbox').on('change', function() {
            updateSelectedSubjectsTable();
        });
        
        function updateSelectedSubjectsTable() {
            let selectedCheckboxes = $('.subject-checkbox:checked');
            let tableBody = $('#selected-subjects-table tbody');
            let totalAmount = 0;
            
            tableBody.empty();
            
            if (selectedCheckboxes.length === 0) {
                tableBody.html('<tr id="no-subjects-row"><td colspan="3" class="text-center">No subjects selected</td></tr>');
            } else {
                selectedCheckboxes.each(function() {
                    let checkbox = $(this);
                    let subjectName = checkbox.data('name');
                    let credits = checkbox.data('credits');
                    let price = parseFloat(checkbox.data('price'));
                    
                    if (isNaN(price)) price = 0;
                    totalAmount += price;
                    
                    tableBody.append(`
                        <tr>
                            <td>${subjectName}</td>
                            <td>${credits}</td>
                            <td>${price.toFixed(2)}</td>
                        </tr>
                    `);
                });
            }
            
            $('#total-amount').text(totalAmount.toFixed(2));
        }
        
        // Initialize the table on page load
        updateSelectedSubjectsTable();
        
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
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection
