@extends('Dashboard.layout')

@section('title', 'Create Major')

@section('page-title', 'Create New Major')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('majors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Major Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('majors.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Major Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="sokhn" class="form-label">Major Code</label>
                    <input type="text" class="form-control @error('sokhn') is-invalid @enderror" id="sokhn" name="sokhn" value="{{ old('sokhn') }}" required>
                    @error('sokhn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="semester_id" class="form-label">Semester</label>
                    <select class="form-select @error('semester_id') is-invalid @enderror" id="semester_id" name="semester_id" required>
                        <option value="">Select Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                {{ $semester->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="term_id" class="form-label">Term</label>
                    <select class="form-select @error('term_id') is-invalid @enderror" id="term_id" name="term_id" required>
                        <option value="">Select Term</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->id }}" {{ old('term_id') == $term->id ? 'selected' : '' }}>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('term_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="year_id" class="form-label">Academic Year</label>
                    <select class="form-select @error('year_id') is-invalid @enderror" id="year_id" name="year_id" required>
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('year_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tuition_id" class="form-label">Tuition Fee</label>
                    <select class="form-select @error('tuition_id') is-invalid @enderror" id="tuition_id" name="tuition_id" required>
                        <option value="">Select Tuition Fee</option>
                        @foreach($tuitions as $tuition)
                            <option value="{{ $tuition->id }}" {{ old('tuition_id') == $tuition->id ? 'selected' : '' }}>
                                {{ number_format($tuition->price, 2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('tuition_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Create Major</button>
                <a href="{{ route('majors.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
