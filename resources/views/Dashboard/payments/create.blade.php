@extends('Dashboard.layout')

@section('title', 'New Payment')

@section('page-title', 'New Payment')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="date" class="form-label">Payment Date</label>
                    <input type="datetime-local" class="form-control" id="date" name="date" required value="{{ now()->format('Y-m-d\TH:i') }}">
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
                <div class="col-md-12">
                    <label for="major_id" class="form-label">Major</label>
                    <select class="form-select select2" id="major_id" name="major_id" required>
                        <option value="">Select Major</option>
                        @foreach($majors as $major)
                            <option value="{{ $major->id }}" data-price="{{ $major->tuition->price }}">
                                {{ $major->name }} | {{ $major->semester->name }} | {{ $major->term->name }} | {{ $major->year->name }} | Fee: {{ number_format($major->tuition->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="detail_price" class="form-label">Base Price</label>
                    <input type="number" class="form-control" id="detail_price" name="detail_price" min="0" step="0.01" required readonly>
                </div>
                <div class="col-md-6">
                    <label for="pro" class="form-label">Discount (%)</label>
                    <input type="number" class="form-control" id="pro" name="pro" min="0" max="100" value="0" step="0.01" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="total_price_display" class="form-label">Final Price</label>
                    <input type="text" class="form-control" id="total_price_display" readonly>
                </div>
                <div class="col-md-6">
                    <label for="payment_proof" class="form-label">Payment Proof (Optional)</label>
                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="submit-btn">Create Payment</button>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
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
                success: function(response) {
                    $('#major_id').empty().append('<option value="">Select Major</option>');
                    
                    if (response.majors && response.majors.length > 0) {
                        response.majors.forEach(function(major) {
                            $('#major_id').append(
                                `<option value="${major.id}" data-price="${major.tuition.price}">
                                    ${major.name} | ${major.semester.name} | ${major.term.name} | ${major.year.name} | Fee: ${parseFloat(major.tuition.price).toFixed(2)}
                                </option>`
                            );
                        });
                    } else {
                        $('#major_id').append('<option value="" disabled>No majors match the selected filters</option>');
                    }
                    
                    $('#major_id').trigger('change');
                },
                error: function(error) {
                    console.error("Error fetching filtered majors:", error);
                    $('#major_id').html('<option value="">Error loading majors. Please try again.</option>');
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
@endsection
