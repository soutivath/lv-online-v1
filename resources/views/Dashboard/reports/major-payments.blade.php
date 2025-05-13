@extends('Dashboard.layout')

@section('title', 'ລາຍລະອຽດການຊຳລະເງິນ')

@section('page-title', 'ລາຍລະອຽດການຊຳລະເງິນສຳລັບ ' . $major->name)

@section('page-actions')
    <div class="btn-group" role="group">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> ກັບຄືນຫາໜ້າຄວບຄຸມ
        </a>
        {{-- <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> ພິມລາຍງານ
        </button> --}}
    </div>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h5>ຂໍ້ມູນສາຂາ</h5>
                <table class="table table-borderless">
                    <tr>
                        <th width="150">ຊື່:</th>
                        <td>{{ $major->name }}</td>
                    </tr>
                    <tr>
                        <th>ປີການສຶກສາ:</th>
                        <td>{{ $major->year->name }}</td>
                    </tr>
                    <tr>
                        <th>ເທີມ:</th>
                        <td>{{ $major->term->name }}</td>
                    </tr>
                    <tr>
                        <th>ພາກຮຽນ:</th>
                        <td>{{ $major->semester->name }}</td>
                    </tr>
                    <tr>
                        <th>ສາຂາທີ່ກ່ຽວຂ້ອງ:</th>
                        <td>{{ count($relatedMajorIds) }} ສາຂາໃນພາກຮຽນ/ເທີມຕ່າງໆ</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h5>ສະຫຼຸບການຊຳລະເງິນ</h5>
                <table class="table table-borderless">
                    <tr>
                        <th width="200">ຈຳນວນນັກສຶກສາທັງໝົດ:</th>
                        <td><span class="badge bg-primary">{{ $studentCount }}</span></td>
                    </tr>
                    <tr>
                        <th>ຈຳນວນການຊຳລະທັງໝົດ:</th>
                        <td><span class="badge bg-primary">{{ $payments->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>ຈຳນວນເງິນທັງໝົດ:</th>
                        <td><span class="badge bg-success">{{ number_format($totalAmount, 2) }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">ລາຍການຊຳລະເງິນ</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ລະຫັດ</th>
                        <th>ນັກສຶກສາ</th>
                        <th>ວັນທີ</th>
                        <th>ປີການສຶກສາ</th>
                        <th>ເທີມ</th>
                        <th>ພາກຮຽນ</th>
                        <th>ຈຳນວນເງິນ</th>
                        <th>ສ່ວນຫຼຸດ</th>
                        <th>ຈຳນວນສຸດທ້າຍ</th>
                        <th>ສະຖານະ</th>
                        <th>ຄຳສັ່ງ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->student->name }} {{ $payment->student->sername }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('d/m/Y') }}</td>
                        <td>{{ $payment->major->year->name }}</td>
                        <td>{{ $payment->major->term->name }}</td>
                        <td>{{ $payment->major->semester->name }}</td>
                        <td>{{ number_format($payment->detail_price, 2) }}</td>
                        <td>{{ $payment->pro }}%</td>
                        <td>{{ number_format($payment->total_price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status == 'success' ? 'success' : 'warning' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('payments.show', $payment->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center">ບໍ່ພົບການຊຳລະເງິນສຳລັບສາຂານີ້</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="8" class="text-end">ລວມທັງໝົດ:</th>
                        <th>{{ number_format($totalAmount, 2) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @media print {
        .navbar, .btn-group, .card-header, .sidebar, footer {
            display: none !important;
        }
        .container-fluid, .card, .card-body {
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        .table {
            width: 100% !important;
        }
    }
</style>
@endsection
