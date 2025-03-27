@extends('Dashboard.layout')

@section('title', 'Create Subject')

@section('page-title', 'Create New Subject')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create New Subject</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Subject Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="20">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="credit_id" class="form-label">Credit</label>
                <select class="form-select @error('credit_id') is-invalid @enderror" id="credit_id" name="credit_id" required>
                    <option value="">Select Credit</option>
                    @foreach($credits as $credit)
                        <option value="{{ $credit->id }}" {{ old('credit_id') == $credit->id ? 'selected' : '' }}>
                            {{ $credit->qty }} Credits - Price: {{ number_format($credit->price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('credit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">Create Subject</button>
                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
