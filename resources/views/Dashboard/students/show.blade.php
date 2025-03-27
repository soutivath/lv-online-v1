@extends('Dashboard.layout')

@section('title', 'Student Details')

@section('page-title', 'Student Details')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('students.export-pdf', $student->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Profile Picture</h5>
            </div>
            <div class="card-body text-center">
                @if($student->picture)
                    <img src="{{ asset('storage/' . $student->picture) }}" alt="{{ $student->name }}" class="img-fluid img-thumbnail" style="max-height: 300px;">
                @else
                    <div class="border p-3">
                        <i class="fas fa-user-circle fa-6x text-secondary"></i>
                        <p class="mt-3">No picture available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">Student ID</th>
                            <td>{{ $student->id }}</td>
                        </tr>
                        <tr>
                            <th>First Name</th>
                            <td>{{ $student->name }}</td>
                        </tr>
                        <tr>
                            <th>Last Name</th>
                            <td>{{ $student->sername }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $student->gender }}</td>
                        </tr>
                        <tr>
                            <th>Birthday</th>
                            <td>{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Age</th>
                            <td>{{ \Carbon\Carbon::parse($student->birthday)->age }} years</td>
                        </tr>
                        <tr>
                            <th>Nationality</th>
                            <td>{{ $student->nationality }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $student->tell }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $student->address }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $student->user ? $student->user->email : 'No email address' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Academic Documents Section -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Academic Documents</h5>
            </div>
            <div class="card-body">
                @if($student->score)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">Score Document</div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/' . $student->score) }}" class="img-fluid img-thumbnail" alt="Score Document">
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No score document uploaded yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add tabs for registrations, payments, and upgrades if needed -->
@endsection
