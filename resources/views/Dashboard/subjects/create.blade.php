@extends('Dashboard.layout')

@section('title', 'ສ້າງວິຊາຮຽນ')

@section('page-title', 'ສ້າງວິຊາຮຽນໃໝ່')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນລາຍການ
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>ສ້າງວິຊາຮຽນໃໝ່</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">ຊື່ວິຊາ<br/><span style="font-size: 0.8em;">Subject Name</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="20">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="credit_id" class="form-label">ໜ່ວຍກິດ<br/><span style="font-size: 0.8em;">Credit</span></label>
                <select class="form-select @error('credit_id') is-invalid @enderror" id="credit_id" name="credit_id" required>
                    <option value="">ເລືອກໜ່ວຍກິດ</option>
                    @foreach($credits as $credit)
                        <option value="{{ $credit->id }}" {{ old('credit_id') == $credit->id ? 'selected' : '' }}>
                            {{ $credit->qty }} ໜ່ວຍກິດ - ລາຄາ: {{ number_format($credit->price, 2) }}
                        </option>
                    @endforeach
                </select>
                @error('credit_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-grid gap-2 mt-3">
                <button type="submit" class="btn btn-primary">ສ້າງວິຊາຮຽນ</button>
                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">ຍົກເລີກ</a>
            </div>
        </form>
    </div>
</div>
@endsection
