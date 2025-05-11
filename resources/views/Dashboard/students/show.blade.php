@extends('Dashboard.layout')

@section('title', 'ລາຍລະອຽດນັກສຶກສາ')

@section('page-title', 'ລາຍລະອຽດນັກສຶກສາ')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('students.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນຫາລາຍການ
        </a>
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> ແກ້ໄຂ
        </a>
        <a href="{{ route('students.export-pdf', $student->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກ PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>ຮູບໂປຣຟາຍ</h5>
            </div>
            <div class="card-body text-center">
                @if($student->picture)
                    <img src="{{ asset('storage/' . $student->picture) }}" alt="{{ $student->name }}" class="img-fluid img-thumbnail" style="max-height: 300px;">
                @else
                    <div class="border p-3">
                        <i class="fas fa-user-circle fa-6x text-secondary"></i>
                        <p class="mt-3">ບໍ່ມີຮູບພາບ</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>ຂໍ້ມູນສ່ວນຕົວ</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%;">ລະຫັດນັກສຶກສາ</th>
                            <td>{{ $student->id }}</td>
                        </tr>
                        <tr>
                            <th>ຊື່</th>
                            <td>{{ $student->name }}</td>
                        </tr>
                        <tr>
                            <th>ນາມສະກຸນ</th>
                            <td>{{ $student->sername }}</td>
                        </tr>
                        <tr>
                            <th>ເພດ</th>
                            <td>{{ $student->gender }}</td>
                        </tr>
                        <tr>
                            <th>ວັນເດືອນປີເກີດ</th>
                            <td>{{ \Carbon\Carbon::parse($student->birthday)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>ອາຍຸ</th>
                            <td>{{ \Carbon\Carbon::parse($student->birthday)->age }} ປີ</td>
                        </tr>
                        <tr>
                            <th>ສັນຊາດ</th>
                            <td>{{ $student->nationality }}</td>
                        </tr>
                        <tr>
                            <th>ເບີໂທລະສັບ</th>
                            <td>{{ $student->tell }}</td>
                        </tr>
                        <tr>
                            <th>ທີ່ຢູ່</th>
                            <td>{{ $student->address }}</td>
                        </tr>
                        <tr>
                            <th>ອີເມລ</th>
                            <td>{{ $student->user ? $student->user->email : 'ບໍ່ມີທີ່ຢູ່ອີເມລ' }}</td>
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
                <h5>ເອກະສານການສຶກສາ</h5>
            </div>
            <div class="card-body">
                @if($student->score)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header">ເອກະສານຄະແນນ</div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/' . $student->score) }}" class="img-fluid img-thumbnail" alt="ເອກະສານຄະແນນ">
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> ຍັງບໍ່ມີການອັບໂຫລດເອກະສານຄະແນນເທື່ອ.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add tabs for registrations, payments, and upgrades if needed -->
@endsection
