@extends('layouts.container')
@section('title', 'تعديل شيك: ' . $check->check_number)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>.select2-container .select2-selection--single { height: calc(1.5em + 1.3rem + 2px ) !important; }</style>
@endpush

@section('content')
<form action="{{ route('dashboard.checks.update', $check->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card card-custom">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>حدث خطأ! يرجى مراجعة الحقول التالية:</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- التفاصيل الأساسية --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>رقم الشيك <span class="text-danger">*</span></label>
                    <input type="text" name="check_number" class="form-control" value="{{ old('check_number', $check->check_number) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>اسم بنك الشيك <span class="text-danger">*</span></label>
                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $check->bank_name) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>تاريخ التحرير <span class="text-danger">*</span></label>
                    <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $check->issue_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>تاريخ الاستحقاق <span class="text-danger">*</span></label>
                    <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $check->due_date->format('Y-m-d')) }}" required>
                </div>
            </div>
            <hr>

            {{-- تفاصيل الطرف والنوع --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>نوع الشيك <span class="text-danger">*</span></label>
                    <select name="type" class="form-control" required>
                        <option value="receivable" @selected(old('type', $check->type) == 'receivable')>شيك قبض (وارد)</option>
                        <option value="payable" @selected(old('type', $check->type) == 'payable')>شيك دفع (صادر)</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>اسم الطرف (العميل/المورد) <span class="text-danger">*</span></label>
                    <input type="text" name="party_name" class="form-control" value="{{ old('party_name', $check->party_name) }}" required>
                </div>
            </div>
            <hr>

            {{-- التفاصيل المالية --}}
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>المبلغ <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount', $check->amount) }}" step="0.01" required>
                </div>
                <div class="col-md-3 form-group">
                    <label>العملة <span class="text-danger">*</span></label>
                    <select name="currency" id="currency" class="form-control" required>
                        <option value="ILS" @selected(old('currency', $check->currency) == 'ILS')>ILS</option>
                        <option value="USD" @selected(old('currency', $check->currency) == 'USD')>USD</option>
                        <option value="JOD" @selected(old('currency', $check->currency) == 'JOD')>JOD</option>
                    </select>
                </div>
                <div class="col-md-2 form-group" id="exchange_rate_wrapper">
                    <label>سعر الصرف</label>
                    <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" value="{{ old('exchange_rate', $check->exchange_rate) }}" step="0.001">
                </div>
                <div class="col-md-3 form-group">
                    <label>القيمة بالشيكل</label>
                    <input type="text" id="amount_ils_display" class="form-control" readonly>
                </div>
            </div>
            <hr>

            {{-- الربط بالبنوك والمشاريع --}}
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>إيداع في حساب بنكي (لحالة شيكات القبض)</label>
                    <select name="deposit_bank_account_id" class="form-control select2-basic">
                        <option value="">-- اختر حساب --</option>
                        @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}" @selected(old('deposit_bank_account_id', $check->deposit_bank_account_id) == $account->id)>{{ $account->account_name }} ({{$account->bank->name}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>صادر من حساب بنكي (لحالة شيكات الدفع)</label>
                    <select name="payment_bank_account_id" class="form-control select2-basic">
                        <option value="">-- اختر حساب --</option>
                        @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}" @selected(old('payment_bank_account_id', $check->payment_bank_account_id) == $account->id)>{{ $account->account_name }} ({{$account->bank->name}})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>ربط بمشروع</label>
                    <select name="project_id" class="form-control select2-basic">
                        <option value="">-- اختر مشروع --</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected(old('project_id', $check->project_id) == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control">{{ old('notes', $check->notes) }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">تحديث الشيك</button>
            <a href="{{ route('dashboard.checks.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document ).ready(function() {
    $('.select2-basic').select2();

    function calculateILS() {
        const amount = parseFloat($('#amount').val()) || 0;
        const rate = parseFloat($('#exchange_rate').val()) || 1;
        $('#amount_ils_display').val((amount * rate).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    $('#amount, #exchange_rate').on('input', calculateILS);
    $('#currency').on('change', function() {
        $('#exchange_rate_wrapper').toggle(this.value !== 'ILS');
        if (this.value === 'ILS') {
            $('#exchange_rate').val(1);
        }
        calculateILS();
    });

    // التشغيل الأولي عند تحميل الصفحة
    calculateILS();
    $('#currency').trigger('change');
});
</script>
@endpush
