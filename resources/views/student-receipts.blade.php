@extends('Admin.Layout')
@section('title', 'ໃບຮັບເງິນຂອງຂ້ອຍ')
@section('contents')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0">ໃບຮັບເງິນແລະການຊຳລະເງິນຂອງຂ້ອຍ</h4>
                </div>
                <div class="card-body p-4">
                    @if(empty($payments->count()) && empty($upgrades->count()))
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i> ທ່ານຍັງບໍ່ມີປະຫວັດການຊຳລະເງິນ
                        </div>
                    @else
                        <ul class="nav nav-tabs mb-4" id="receiptTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments-content" type="button" role="tab" aria-controls="payments-content" aria-selected="true">
                                    <i class="bi bi-cash-coin me-1"></i> ການຊຳລະຄ່າຮຽນ ({{ $payments->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="upgrades-tab" data-bs-toggle="tab" data-bs-target="#upgrades-content" type="button" role="tab" aria-controls="upgrades-content" aria-selected="false">
                                    <i class="bi bi-arrow-up-circle me-1"></i> ການອັບເກຣດ ({{ $upgrades->count() }})
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="receiptTabsContent">
                            <!-- Payments Tab -->
                            <div class="tab-pane fade show active" id="payments-content" role="tabpanel" aria-labelledby="payments-tab">
                                @if($payments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ວັນທີ</th>
                                                    <th>ໃບບິນ</th>
                                                    <th>ລາຍການ</th>
                                                    <th>ຈຳນວນເງິນ</th>
                                                    <th>ສະຖານະ</th>
                                                    <th>ຄຳສັ່ງ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    // Group payments by bill number
                                                    $groupedPayments = $payments->groupBy('bill_number');
                                                @endphp
                                                
                                                @foreach($groupedPayments as $billNumber => $paymentGroup)
                                                    @php
                                                        $firstPayment = $paymentGroup->first();
                                                        $groupTotal = $paymentGroup->sum('total_price');
                                                        $allSuccessful = $paymentGroup->every(function($payment) { 
                                                            return $payment->status === 'success'; 
                                                        });
                                                    @endphp
                                                    <tr class="payment-group" data-bill="{{ $billNumber }}">
                                                        <td>{{ \Carbon\Carbon::parse($firstPayment->date)->format('d/m/Y') }}</td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $billNumber ?: 'ບໍ່ມີໃບບິນ' }}</span>
                                                        </td>
                                                        <td>
                                                            @if($paymentGroup->count() > 1)
                                                                <span class="badge bg-info">{{ $paymentGroup->count() }} ລາຍການ</span>
                                                                <button class="btn btn-sm btn-outline-primary ms-2 toggle-details" data-bill="{{ $billNumber }}">
                                                                    <i class="bi bi-chevron-down"></i>
                                                                </button>
                                                            @else
                                                                {{ $firstPayment->major->name }}
                                                            @endif
                                                        </td>
                                                        <td>{{ number_format($groupTotal, 2) }}</td>
                                                        <td>
                                                            @if(!$allSuccessful)
                                                                <span class="badge bg-warning">ລໍຖ້າການຢືນຢັນ</span>
                                                            @else
                                                                <span class="badge bg-success">ຢືນຢັນແລ້ວ</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($allSuccessful)
                                                                <a href="{{ route('payments.export-pdf', $firstPayment->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                    <i class="bi bi-file-earmark-pdf"></i> ດາວໂຫລດໃບຮັບເງິນ
                                                                </a>
                                                            @else
                                                                <button class="btn btn-sm btn-secondary" disabled>
                                                                    <i class="bi bi-hourglass-split"></i> ລໍຖ້າການຢືນຢັນ
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    
                                                    @if($paymentGroup->count() > 1)
                                                        <tr class="payment-details-row d-none" data-bill="{{ $billNumber }}">
                                                            <td colspan="6" class="p-0">
                                                                <div class="p-3 bg-light">
                                                                    <h6 class="mb-2">ລາຍລະອຽດຂອງລາຍການທັງໝົດ</h6>
                                                                    <table class="table table-sm table-bordered mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>ສາຂາ</th>
                                                                                <th>ພາກຮຽນ/ເທີມ/ປີ</th>
                                                                                <th>ຈຳນວນເງິນ</th>
                                                                                <th>ສະຖານະ</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($paymentGroup as $payment)
                                                                                <tr>
                                                                                    <td>{{ $payment->major->name }}</td>
                                                                                    <td>{{ $payment->major->semester->name }} / {{ $payment->major->term->name }} / {{ $payment->major->year->name }}</td>
                                                                                    <td>{{ number_format($payment->total_price, 2) }}</td>
                                                                                    <td>
                                                                                        @if($payment->status == 'pending')
                                                                                            <span class="badge bg-warning">ລໍຖ້າການຢືນຢັນ</span>
                                                                                        @else
                                                                                            <span class="badge bg-success">ຢືນຢັນແລ້ວ</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> ທ່ານຍັງບໍ່ມີການຊຳລະຄ່າຮຽນ
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Upgrades Tab -->
                            <div class="tab-pane fade" id="upgrades-content" role="tabpanel" aria-labelledby="upgrades-tab">
                                @if($upgrades->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ວັນທີ</th>
                                                    <th>ສາຂາ</th>
                                                    <th>ວິຊາທີ່ອັບເກຣດ</th>
                                                    <th>ຈຳນວນເງິນ</th>
                                                    <th>ສະຖານະ</th>
                                                    <th>ຄຳສັ່ງ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($upgrades as $upgrade)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($upgrade->date)->format('d/m/Y') }}</td>
                                                        <td>{{ $upgrade->major->name }}</td>
                                                        <td>
                                                            @foreach($upgrade->upgradeDetails as $detail)
                                                                <div class="badge bg-info mb-1">{{ $detail->subject->name }}</div>
                                                            @endforeach
                                                        </td>
                                                        <td>{{ number_format($upgrade->upgradeDetails->sum('total_price'), 2) }}</td>
                                                        <td>
                                                            @if($upgrade->payment_status == 'pending')
                                                                <span class="badge bg-warning">ລໍຖ້າການຢືນຢັນ</span>
                                                            @else
                                                                <span class="badge bg-success">ຢືນຢັນແລ້ວ</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($upgrade->payment_status == 'success')
                                                                <a href="{{ route('upgrades.export-pdf', $upgrade->id) }}" class="btn btn-sm btn-primary" target="_blank">
                                                                    <i class="bi bi-file-earmark-pdf"></i> ດາວໂຫລດໃບຮັບເງິນ
                                                                </a>
                                                            @else
                                                                <button class="btn btn-sm btn-secondary" disabled>
                                                                    <i class="bi bi-hourglass-split"></i> ລໍຖ້າການຢືນຢັນ
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i> ທ່ານຍັງບໍ່ມີການອັບເກຣດວິຊາຮຽນ
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="text-center">
                <a href="{{ route('home') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> ກັບຄືນໜ້າຫຼັກ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ensure tabs work correctly
    const tabs = document.querySelectorAll('#receiptTabs .nav-link');
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('show', 'active'));
            
            // Activate clicked tab and its content
            this.classList.add('active');
            const target = this.getAttribute('data-bs-target');
            document.querySelector(target).classList.add('show', 'active');
        });
    });
    
    // Add toggle functionality for payment details
    const toggleButtons = document.querySelectorAll('.toggle-details');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const billNumber = this.getAttribute('data-bill');
            const detailsRow = document.querySelector(`.payment-details-row[data-bill="${billNumber}"]`);
            
            if (detailsRow) {
                detailsRow.classList.toggle('d-none');
                
                // Toggle the chevron icon
                const icon = this.querySelector('i');
                if (icon.classList.contains('bi-chevron-down')) {
                    icon.classList.replace('bi-chevron-down', 'bi-chevron-up');
                } else {
                    icon.classList.replace('bi-chevron-up', 'bi-chevron-down');
                }
            }
        });
    });
});
</script>
@endsection
