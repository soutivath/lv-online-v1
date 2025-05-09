@extends('Dashboard.layout')

@section('title', 'ສົກຮຽນ')

@section('page-title', 'ສົກຮຽນ')

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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addYearModal">
            <i class="fas fa-plus"></i> ເພີ່ມສົກຮຽນ
        </button>
        <a href="{{ route('years.export-pdf') }}" class="btn btn-success" target="_blank">
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
                        <th>ລະຫັດ</th>
                        <th>ຊື່</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($years as $year)
                        <tr>
                            <td>{{ $year->id }}</td>
                            <td>{{ $year->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-bs-toggle="modal" data-bs-target="#editYearModal{{ $year->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('delete-year-form-{{ $year->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-year-form-{{ $year->id }}" action="{{ route('years.destroy', $year->id) }}" method="POST" class="d-none">
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

<!-- Add Year Modal -->
<div class="modal fade" id="addYearModal" tabindex="-1" aria-labelledby="addYearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('years.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addYearModalLabel">ເພີ່ມສົກຮຽນໃໝ່</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">ຊື່</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="10" placeholder="ຕົວຢ່າງ 2023-2024">
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

<!-- Edit Year Modals -->
@foreach($years as $year)
    <div class="modal fade" id="editYearModal{{ $year->id }}" tabindex="-1" aria-labelledby="editYearModalLabel{{ $year->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('years.update', $year->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editYearModalLabel{{ $year->id }}">ແກ້ໄຂສົກຮຽນ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name{{ $year->id }}" class="form-label">ຊື່</label>
                            <input type="text" class="form-control" id="edit_name{{ $year->id }}" name="name" value="{{ $year->name }}" required maxlength="10">
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
