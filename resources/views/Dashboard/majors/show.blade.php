@extends('Dashboard.layout')

@section('title', 'Major Details')

@section('page-title', 'Major Details')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('majors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('majors.edit', $major->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('majors.export-pdf', $major->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Major Information</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">ID</th>
                    <td>{{ $major->id }}</td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>{{ $major->name }}</td>
                </tr>
                <tr>
                    <th>Semester</th>
                    <td>{{ $major->semester->name }}</td>
                </tr>
                <tr>
                    <th>Term</th>
                    <td>{{ $major->term->name }}</td>
                </tr>
                <tr>
                    <th>Year</th>
                    <td>{{ $major->year->name }}</td>
                </tr>
                <tr>
                    <th>Tuition Fee</th>
                    <td>{{ number_format($major->tuition->price, 2) }}</td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td>{{ $major->sokhn }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Display related subjects if any -->
<!-- Display registered students if any -->
@endsection
