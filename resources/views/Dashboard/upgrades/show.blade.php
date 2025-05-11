@extends('Dashboard.layout')

@section('title', 'ລາຍລະອຽດການປັບປຸງ')

@section('page-title', 'ລາຍລະອຽດການປັບປຸງຄະແນນ')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('upgrades.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນຫາລາຍການ
        </a>
        <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກ PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>ຂໍ້ມູນການປັບປຸງ</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 150px">ລະຫັດການປັບປຸງ</th>
                        <td>{{ $upgrade->id }}</td>
                    </tr>
                    <tr>
                        <th>ວັນທີ</th>
                        <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>ພະນັກງານ</th>
                        <td>{{ $upgrade?->employee?->name }} {{ $upgrade?->employee?->sername }}</td>
                    </tr>
                    <tr>
                        <th>ສະຖານະການຊຳລະ</th>
                        <td>
                            @if($upgrade->payment_status == 'pending')
                                <span class="badge bg-warning">ລໍຖ້າ</span>
                            @else
                                <span class="badge bg-success">ສຳເລັດ</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>ຂໍ້ມູນນັກສຶກສາ</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12 text-center mb-3">
                        @if($upgrade->student->picture)
                            <img src="{{ asset('storage/' . $upgrade->student->picture) }}" alt="ຮູບນັກສຶກສາ" class="img-thumbnail" style="max-height: 150px">
                        @else
                            <div class="border p-3 text-center">
                                <i class="fas fa-user fa-5x text-secondary"></i>
                                <p class="mt-2">ບໍ່ມີຮູບພາບ</p>
                            </div>
                        @endif
                    </div>
                </div>
                <table class="table">
                    <tr>
                        <th style="width: 150px">ລະຫັດນັກສຶກສາ</th>
                        <td>{{ $upgrade->student->id }}</td>
                    </tr>
                    <tr>
                        <th>ຊື່</th>
                        <td>{{ $upgrade->student->name }} {{ $upgrade->student->sername }}</td>
                    </tr>
                    <tr>
                        <th>ເພດ</th>
                        <td>{{ $upgrade->student->gender }}</td>
                    </tr>
                    <tr>
                        <th>ເບີໂທລະສັບ</th>
                        <td>{{ $upgrade->student->tell }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>ຂໍ້ມູນສາຂາວິຊາ</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <p><strong>ສາຂາວິຊາ:</strong> {{ $upgrade->major->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <p><strong>ພາກຮຽນ:</strong> {{ $upgrade->major->semester->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <p><strong>ເທີມ:</strong> {{ $upgrade->major->term->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <p><strong>ສົກຮຽນ:</strong> {{ $upgrade->major->year->name }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>ການປັບປຸງວິຊາ</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ວິຊາ</th>
                        <th>ໜ່ວຍກິດ</th>
                        <th>ລາຄາໜ່ວຍກິດ</th>
                        <th>ຈຳນວນເງິນ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($upgrade->upgradeDetails as $detail)
                        <tr>
                            <td>{{ $detail->subject->name }}</td>
                            <td>{{ $detail->subject->credit->qty }}</td>
                            <td>{{ number_format($detail->subject->credit->price, 2) }}</td>
                            <td>{{ number_format($detail->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">ລວມທັງໝົດ</th>
                        <th>{{ number_format($upgrade->upgradeDetails->sum('total_price'), 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($upgrade->payment_proof)
<div class="card mb-4">
    <div class="card-header">
        <h5>ຫຼັກຖານການຊຳລະເງິນ</h5>
    </div>
    <div class="card-body text-center">
        <img src="{{ asset('storage/' . $upgrade->payment_proof) }}" alt="ຫຼັກຖານການຊຳລະເງິນ" class="img-fluid img-thumbnail" style="max-height: 400px;">
    </div>
</div>
@endif

@if($upgrade->payment_status == 'pending')
<div class="mb-4">
    <form action="{{ route('upgrades.confirm-payment', $upgrade->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle me-1"></i> ຢືນຢັນການຊຳລະເງິນ
        </button>
    </form>
</div>
@endif

<div class="d-flex justify-content-end">
    <button class="btn btn-danger me-2" onclick="confirmDelete('delete-upgrade-form-{{ $upgrade->id }}')">
        <i class="fas fa-trash"></i> ລຶບການປັບປຸງ
    </button>
    <form id="delete-upgrade-form-{{ $upgrade->id }}" action="{{ route('upgrades.destroy', $upgrade->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
