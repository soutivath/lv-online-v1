@extends('Dashboard.layout')

@section('title', 'ເທີມ')

@section('page-title', 'ເທີມ')

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

@section('page-actions')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTermModal">
            <i class="fas fa-plus"></i> ເພີ່ມເທີມ
        </button>
        <a href="{{ route('terms.export-pdf') }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກທັງໝົດ
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
                        <th>ຊື່</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($terms as $term)
                        <tr>
                            <td>{{ $term->id }}</td>
                            <td>{{ $term->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editTermModal{{ $term->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-term-form-{{ $term->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-term-form-{{ $term->id }}" action="{{ route('terms.destroy', $term->id) }}" method="POST" class="d-none">
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

<!-- Add Term Modal -->
<div class="modal fade" id="addTermModal" tabindex="-1" aria-labelledby="addTermModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('terms.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addTermModalLabel">ເພີ່ມເທີມໃໝ່</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">ຊື່</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="15">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
                    <button type="submit" class="btn btn-primary">ບັນທຶກ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Term Modals -->
@foreach($terms as $term)
    <div class="modal fade" id="editTermModal{{ $term->id }}" tabindex="-1" aria-labelledby="editTermModalLabel{{ $term->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('terms.update', $term->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTermModalLabel{{ $term->id }}">ແກ້ໄຂເທີມ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name{{ $term->id }}" class="form-label">ຊື່</label>
                            <input type="text" class="form-control" id="edit_name{{ $term->id }}" name="name" value="{{ $term->name }}" required maxlength="15">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ປິດ</button>
                        <button type="submit" class="btn btn-primary">ອັບເດດ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
