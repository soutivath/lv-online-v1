@extends('Dashboard.layout')

@section('title', 'ລາຍລະອຽດການລົງທະບຽນ')

@section('page-title', 'ລາຍລະອຽດການລົງທະບຽນ')

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('registrations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນຫາລາຍການ
        </a>
        <a href="{{ route('registrations.export-pdf', $registration->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-file-pdf"></i> ສົ່ງອອກ PDF
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>ຂໍ້ມູນການລົງທະບຽນ</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 150px">ລະຫັດການລົງທະບຽນ</th>
                        <td>{{ $registration->id }}</td>
                    </tr>
                    <tr>
                        <th>ວັນທີ</th>
                        <td>{{ \Carbon\Carbon::parse($registration->date)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>ສ່ວນຫຼຸດ</th>
                        <td>{{ $registration->pro }}%</                    </tr>
                    <tr>
                        <th>ພະນັກງານ</th>
                        <td>{{ $registration?->employee?->name }} {{ $registration?->employee?->sername }}</td>
                    </tr>
                    <tr>
                        <th>ສະຖານະການຊຳລະ</th>
                        <td>
                            @if($registration->payment_status == 'pending')
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
                        @if($registration->student->picture)
                            <img src="{{ asset('storage/' . $registration->student->picture) }}" alt="ຮູບນັກສຶກສາ" class="img-thumbnail" style="max-height: 150px">
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
                        <td>{{ $registration->student->id }}</td>
                    </tr>
                    <tr>
                        <th>ຊື່</th>
                        <td>{{ $registration->student->name }} {{ $registration->student->sername }}</td>
                    </tr>
                    <tr>
                        <th>ເພດ</th>
                        <td>{{ $registration->student->gender }}</td>
                    </tr>
                    <tr>
                        <th>ເບີໂທ</th>
                        <td>{{ $registration->student->tell }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5>ລາຍລະອຽດການລົງທະບຽນ</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ສາຂາວິຊາ</th>
                        <th>ເທີມ</th>
                        <th>ພາກການສຶກສາ</th>
                        <th>ສົກຮຽນ</th>
                        <th>ລາຄາພື້ນຖານ</th>
                        <th>ສ່ວນຫຼຸດ</th>
                        <th>ລາຄາສຸດທ້າຍ</th>
                        <th>ສະຖານະການຊຳລະ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registration->registrationDetails as $detail)
                        <tr>
                            <td>{{ $detail->major->name }}</td>
                            <td>{{ $detail->major->semester->name }}</td>
                            <td>{{ $detail->major->term->name }}</td>
                            <td>{{ $detail->major->year->name }}</td>
                            <td>{{ number_format($detail->detail_price, 2) }}</td>
                            <td>{{ $registration->pro }}% ({{ number_format($detail->detail_price - $detail->total_price, 2) }})</td>
                            <td>{{ number_format($detail->total_price, 2) }}</td>
                            <td>
                                @if(isset($majorPaymentStatuses[$detail->major_id]))
                                    @if($majorPaymentStatuses[$detail->major_id]['paid_directly'])
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> 
                                            ຈ່າຍແລ້ວ
                                            <a href="{{ route('payments.show', $majorPaymentStatuses[$detail->major_id]['payment_id']) }}" class="text-white">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </span>
                                    @elseif($registration->payment_status == 'success')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> 
                                            ຈ່າຍແລ້ວຜ່ານການລົງທະບຽນ
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> 
                                            ລໍຖ້າ
                                        </span>
                                    @endif
                                @else
                                    @if($registration->payment_status == 'success')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> 
                                            ຈ່າຍແລ້ວ
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock"></i> 
                                            ລໍຖ້າ
                                        </span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">ລວມທັງໝົດ</th>
                        <th>{{ number_format($registration->registrationDetails->sum('total_price'), 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@if($registration->payment_proof)
<div class="card mb-4">
    <div class="card-header">
        <h5>ຫຼັກຖານການຊຳລະເງິນ</h5>
    </div>
    <div class="card-body text-center">
        <img src="{{ asset('storage/' . $registration->payment_proof) }}" alt="ຫຼັກຖານການຊຳລະເງິນ" class="img-fluid img-thumbnail" style="max-height: 400px;">
    </div>
</div>
@endif

@if($registration->payment_status == 'pending')
<div class="mb-4">
    <form action="{{ route('registrations.confirm-payment', $registration->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle me-1"></i> ຢືນຢັນການຊຳລະເງິນ
        </button>
    </form>
</div>
@endif

<div class="d-flex justify-content-end">
    <button class="btn btn-danger me-2" onclick="confirmDelete('delete-registration-form-{{ $registration->id }}')">
        <i class="fas fa-trash"></i> ລຶບການລົງທະບຽນ
    </button>
    <form id="delete-registration-form-{{ $registration->id }}" action="{{ route('registrations.destroy', $registration->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection
