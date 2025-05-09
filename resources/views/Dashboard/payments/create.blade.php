@extends('Dashboard.layout')

@section('title', 'ເພີ່ມການຊຳລະເງິນໃໝ່')

@section('page-title', 'ເພີ່ມການຊຳລະເງິນໃໝ່')

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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">ນັກສຶກສາ</label>
                    
                    <!-- Inline-styled dropdown to ensure it works -->
                    <div style="position: relative; width: 100%; margin-bottom: 1rem;">
                        <!-- Display button -->
                        <button type="button" onclick="toggleStudentList()" 
                                style="width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; padding: 8px 12px; text-align: left; cursor: pointer; display: flex; justify-content: space-between; align-items: center;">
                            <span id="selectedStudentText">ເລືອກນັກສຶກສາ</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <!-- Hidden dropdown content -->
                        <div id="studentListContainer" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; background: white; border: 1px solid #ced4da; border-radius: 4px; margin-top: 2px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); z-index: 9999; max-height: 300px; overflow: hidden;">
                            <!-- Search input -->
                            <div style="padding: 8px; border-bottom: 1px solid #eee;">
                                <input type="text" id="studentSearchInput" placeholder="ຄົ້ນຫານັກສຶກສາ..." 
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
                                ບໍ່ພົບນັກສຶກສາທີ່ຄົ້ນຫາ
                            </div>
                        </div>
                        
                        <input type="hidden" name="student_id" id="student_id" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="date" class="form-label">ວັນທີຊຳລະເງິນ</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" required value="{{ now()->format('Y-m-d\TH:i') }}">
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
                            <label for="year_filter" class="form-label">ສົກຮຽນ</label>
                            <select class="form-select select2" id="year_filter">
                                <option value="">ທຸກສົກຮຽນ</option>
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

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="major_id" class="form-label">ສາຂາ</label>
                    <select class="form-select select2" id="major_id" name="major_id" required>
                        <option value="">ເລືອກສາຂາ</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" data-price="{{ $major->tuition->price }}">
                                {{ $major->name }} | {{ $major->semester->name }} | {{ $major->term->name }} | {{ $major->year->name }} | ຄ່າທຳນຽມ: {{ number_format($major->tuition->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="detail_price" class="form-label">ລາຄາພື້ນຖານ</label>
                    <input type="number" class="form-control" id="detail_price" name="detail_price" min="0" step="0.01" required readonly>
                </div>
                <div class="col-md-6">
                    <label for="pro" class="form-label">ສ່ວນຫຼຸດ (%)</label>
                    <input type="number" class="form-control" id="pro" name="pro" min="0" max="100" value="0" step="0.01" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="total_price_display" class="form-label">ລາຄາສຸດທ້າຍ</label>
                    <input type="text" class="form-control" id="total_price_display" readonly>
                </div>
                <div class="col-md-6">
                    <label for="payment_proof" class="form-label">ຫຼັກຖານການຊຳລະເງິນ (ຖ້າມີ)</label>
                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="submit-btn">ສ້າງການຊຳລະເງິນ</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">ຍົກເລີກ</a>
            </div>
        </form>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    console.log("Payment create page loaded");
    
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
    
    $(document).ready(function() {
        // Initialize Select2 for other dropdowns
        // $('.select2').select2();
        
        // Filter change event handlers
        $('#year_filter, #term_filter, #semester_filter').on('change', function() {
            filterMajors();
        });
        
        function filterMajors() {
            const yearId = $('#year_filter').val();
            const termId = $('#term_filter').val();
            const semesterId = $('#semester_filter').val();
            
            // Show loading indicator
            $('#major_id').html('<option value="">ກຳລັງໂຫຼດສາຂາ...</option>');
            
            $.ajax({
                url: "{{ route('majors.filtered') }}",
                type: "GET",
                data: {
                    year_id: yearId,
                    term_id: termId,
                    semester_id: semesterId
                },
                success: function(response) {
                    $('#major_id').empty().append('<option value="">ເລືອກສາຂາ</option>');
                    
                    if (response.majors && response.majors.length > 0) {
                        response.majors.forEach(function(major) {
                            $('#major_id').append(
                                `<option value="${major.id}" data-price="${major.tuition.price}">
                                    ${major.name} | ${major.semester.name} | ${major.term.name} | ${major.year.name} | ຄ່າທຳນຽມ: ${parseFloat(major.tuition.price).toFixed(2)}
                                </option>`
                            );
                        });
                    } else {
                        $('#major_id').append('<option value="" disabled>ບໍ່ພົບສາຂາທີ່ຕົງກັບຕົວກອງທີ່ເລືອກ</option>');
                    }
                    
                    $('#major_id').trigger('change');
                },
                error: function(error) {
                    console.error("Error fetching filtered majors:", error);
                    $('#major_id').html('<option value="">ມີຂໍ້ຜິດພາດໃນການໂຫຼດສາຂາ. ກະລຸນາລອງໃໝ່.</option>');
                }
            });
        }
        
        // Update base price when major changes
        $('#major_id').on('change', function() {
            const option = $(this).find('option:selected');
            const price = option.data('price') || 0;
            $('#detail_price').val(price);
            calculateFinalPrice();
        });
        
        // Calculate final price when inputs change
        $('#pro').on('input', calculateFinalPrice);
        
        function calculateFinalPrice() {
            const basePrice = parseFloat($('#detail_price').val()) || 0;
            const discount = parseFloat($('#pro').val()) || 0;
            const discountAmount = (basePrice * discount / 100);
            const finalPrice = basePrice - discountAmount;
            
            $('#total_price_display').val(finalPrice.toFixed(2));
        }
        
        // Initial calculation
        calculateFinalPrice();
    });
</script>

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2(); });
</script>
@endsection
