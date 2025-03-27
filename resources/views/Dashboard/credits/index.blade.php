@extends('Dashboard.layout')

@section('title', 'Credits')

@section('page-title', 'Credits')

@section('page-actions')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCreditModal">
            <i class="fas fa-plus"></i> Add Credit
        </button>
        <a href="{{ route('credits.export-pdf') }}" class="btn btn-success" target="_blank">
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
                        <th>Credit Quantity</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($credits as $credit)
                        <tr>
                            <td>{{ $credit->id }}</td>
                            <td>{{ $credit->qty }}</td>
                            <td>{{ number_format($credit->price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editCreditModal{{ $credit->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-credit-form-{{ $credit->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-credit-form-{{ $credit->id }}" action="{{ route('credits.destroy', $credit->id) }}" method="POST" class="d-none">
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

<!-- Add Credit Modal -->
<div class="modal fade" id="addCreditModal" tabindex="-1" aria-labelledby="addCreditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('credits.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCreditModalLabel">Add New Credit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="qty" class="form-label">Credit Quantity</label>
                        <input type="text" class="form-control" id="qty" name="qty" required maxlength="5">
                    </div>
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

<!-- Edit Credit Modals -->
@foreach($credits as $credit)
    <div class="modal fade" id="editCreditModal{{ $credit->id }}" tabindex="-1" aria-labelledby="editCreditModalLabel{{ $credit->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('credits.update', $credit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCreditModalLabel{{ $credit->id }}">Edit Credit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_qty{{ $credit->id }}" class="form-label">Credit Quantity</label>
                            <input type="text" class="form-control" id="edit_qty{{ $credit->id }}" name="qty" value="{{ $credit->qty }}" required maxlength="5">
                        </div>
                        <div class="mb-3">
                            <label for="edit_price{{ $credit->id }}" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="edit_price{{ $credit->id }}" name="price" value="{{ $credit->price }}" required min="0">
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
