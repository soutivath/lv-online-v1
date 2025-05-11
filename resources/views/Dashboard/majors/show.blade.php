@extends('Dashboard.layout')

@section('title', 'ລາຍລະອຽດສາຂາວິຊາ')

@section('page-title', 'ລາຍລະອຽດສາຂາວິຊາ')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('majors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນຫາລາຍການ
        </a>
        <a href="{{ route('majors.edit', $major->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> ແກ້ໄຂ
        </a>
        <a href="{{ route('majors.export-pdf', $major->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກ PDF
        </a>
    </div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5>ຂໍ້ມູນສາຂາວິຊາ</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 30%;">ລະຫັດ</th>
                    <td>{{ $major->id }}</td>
                </tr>
                <tr>
                    <th>ຊື່</th>
                    <td>{{ $major->name }}</td>
                </tr>
                <tr>
                    <th>ພາກຮຽນ</th>
                    <td>{{ $major->semester->name }}</td>
                </tr>
                <tr>
                    <th>ເທີມ</th>
                    <td>{{ $major->term->name }}</td>
                </tr>
                <tr>
                    <th>ປີ</th>
                    <td>{{ $major->year->name }}</td>
                </tr>
                <tr>
                    <th>ຄ່າຮຽນ</th>
                    <td>{{ number_format($major->tuition->price, 2) }}</td>
                </tr>
                <tr>
                    <th>ລະຫັດ</th>
                    <td>{{ $major->sokhn }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- Display related subjects if any -->
<!-- Display registered students if any -->
@endsection
