@extends('Dashboard.layout')

@section('title', 'Subject Details')

@section('page-title', 'Subject Details')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນຫາລາຍການ
        </a>
        <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('subjects.export-pdf', $subject->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກ PDF
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Subject Information</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">ID</th>
                    <td>{{ $subject->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $subject->name }}</td>
                </tr>
                <tr>
                    <th>Credits</th>
                    <td>{{ $subject->credit->qty }}</td>
                </tr>
                <tr>
                    <th>Credit Price</th>
                    <td>{{ number_format($subject->credit->price, 2) }}</td>
                </tr>
                <tr>
                    <th>Total Price</th>
                    <td>{{ number_format($subject->credit->qty * $subject->credit->price, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
