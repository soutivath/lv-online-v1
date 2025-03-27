@extends('Dashboard.layout')

@section('title', 'Tuition Fees')

@section('page-title', 'Tuition Fees')

@section('page-actions')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTuitionModal">
            <i class="fas fa-plus"></i> Add Tuition Fee
        </button>
        <a href="{{ route('tuitions.export-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> Export All
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tuitions as $tuition)
                        <tr>
                            <td>{{ $tuition->id }}</td>
                            <td>{{ number_format($tuition->price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editTuitionModal{{ $tuition->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-tuition-form-{{ $tuition->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-tuition-form-{{ $tuition->id }}" action="{{ route('tuitions.destroy', $tuition->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Tuition Modal -->
<div class="modal fade" id="addTuitionModal" tabindex="-1" aria-labelledby="addTuitionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tuitions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addTuitionModalLabel">Add New Tuition Fee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Tuition Modals -->
@foreach($tuitions as $tuition)
    <div class="modal fade" id="editTuitionModal{{ $tuition->id }}" tabindex="-1" aria-labelledby="editTuitionModalLabel{{ $tuition->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tuitions.update', $tuition->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTuitionModalLabel{{ $tuition->id }}">Edit Tuition Fee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_price{{ $tuition->id }}" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price{{ $tuition->id }}" name="price" value="{{ $tuition->price }}" required min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
