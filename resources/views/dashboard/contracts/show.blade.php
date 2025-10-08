@extends('layouts.container')
@section('title', 'تفاصيل العقد: ' . $contract->contract_id)

@section('styles')
<style>
    body { background-color: #f4f6f9; }
    .contract-details-card {
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-top: 40px;
    }
    .contract-header {
        padding: 25px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .contract-header h3 {
        margin: 0;
        color: #333;
        font-size: 1.8rem;
    }
    .contract-body { padding: 30px; }
    .detail-group { margin-bottom: 30px; }
    .detail-group h5 {
        color: #4f46e5;
        font-size: 1.2rem;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 2px solid #4f46e5;
        display: inline-block;
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    .detail-item strong { color: #555; }
    .detail-item span { color: #777; }
    .actions-bar {
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        text-align: left;
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="contract-details-card" id="printable-area">
        <div class="contract-header">
            <h3>تفاصيل العقد رقم: {{ $contract->contract_id }}</h3>
            <a href="{{ route('dashboard.contracts.index') }}" class="btn btn-light">العودة للقائمة</a>
        </div>
        <div class="contract-body">
            <div class="detail-group">
                <h5>1. معلومات العقد</h5>
                <div class="detail-item"><strong>تاريخ التوقيع:</strong> <span>{{ $contract->signing_date->format('Y-m-d') }}</span></div>
                <div class="detail-item"><strong>الحالة:</strong> <span>{{ $contract->status == 'active' ? 'نشط' : 'مسودة' }}</span></div>
            </div>

            <div class="detail-group">
                <h5>2. بيانات العميل</h5>
                <div class="detail-item"><strong>اسم العميل:</strong> <span>{{ $contract->client_name }}</span></div>
                <div class="detail-item"><strong>رقم الهوية:</strong> <span>{{ $contract->client_id_number ?? '-' }}</span></div>
                <div class="detail-item"><strong>رقم الجوال:</strong> <span>{{ $contract->client_phone }}</span></div>
            </div>

            <div class="detail-group">
                <h5>3. تفاصيل الدفع</h5>
                <div class="detail-item"><strong>طرق الدفع:</strong> <span>{{ str_replace(',', ', ', $contract->payment_method) }}</span></div>
                <div class="detail-item"><strong>الدفعة الأولى:</strong> <span>{{ number_format($contract->down_payment_initial, 2) }}</span></div>
                <div class="detail-item"><strong>المبلغ المتبقي:</strong> <span>{{ number_format($contract->remaining_amount, 2) }}</span></div>
            </div>
             <div id="cash-details" class="form-section hidden">
                    <h5 class="form-section-title" style="font-size: 1.1rem; border-color: #10b981;">تفاصيل الدفع النقدي</h5>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>من استلم المبلغ</label><input type="text" name="cash_receiver" class="form-control" value="{{ old('cash_receiver') }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>وظيفة المستلم</label><input type="text" name="cash_receiver_job" class="form-control" value="{{ old('cash_receiver_job') }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>تاريخ الاستلام</label><input type="date" name="cash_receipt_date" class="form-control" value="{{ old('cash_receipt_date') }}"></div>
                    </div>
                </div>

                <div id="bank-details" class="form-section hidden">
                    <h5 class="form-section-title" style="font-size: 1.1rem; border-color: #3b82f6;">تفاصيل التحويل البنكي</h5>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>البنك المرسل</label><input type="text" name="sender_bank" class="form-control" value="{{ old('sender_bank') }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>البنك المستقبل</label><input type="text" name="receiver_bank" class="form-control" value="{{ old('receiver_bank') }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>رقم مرجع التحويل</label><input type="text" name="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>تاريخ التحويل</label><input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date') }}"></div>
                    </div>
                </div>

                <div id="check-details" class="form-section hidden">
                    <h5 class="form-section-title" style="font-size: 1.1rem; border-color: #f59e0b;">تفاصيل الشيك</h5>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>رقم الشيك</label><input type="text" name="check_number" class="form-control" value="{{ old('check_number') }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>اسم مالك الشيك</label><input type="text" name="check_owner" class="form-control" value="{{ old('check_owner') }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>البنك المسحوب عليه</label><input type="text" name="check_bank" class="form-control" value="{{ old('check_bank') }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>تاريخ الاستحقاق</label><input type="date" name="check_due_date" class="form-control" value="{{ old('check_due_date') }}"></div>
                    </div>
                </div>

        </div>
    </div>
    <div class="actions-bar mt-4">
        <button onclick="printContract()" class="btn btn-info">
            <i class="fas fa-print"></i> طباعة
        </button>
        <a href="{{ route('dashboard.contracts.edit', $contract->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> تعديل العقد
        </a>
    </div>
</main>
@endsection

@section('script')
<script>
    function printContract() {

        window.print();
    }
</script>
@endsection
