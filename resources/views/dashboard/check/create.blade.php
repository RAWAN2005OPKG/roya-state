@extends('layouts.container')
@section('title', 'إضافة شيك جديد')

@section('content')
<form action="{{ route('dashboard.checks.store') }}" method="POST">
    @csrf
    <div class="card card-custom">
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

            <div class="row"><div class="col-md-6 form-group"><label>رقم الشيك <span class="text-danger">*</span></label><input type="text" name="check_number" class="form-control" value="{{ old('check_number') }}" required></div><div class="col-md-6 form-group"><label>اسم بنك الشيك <span class="text-danger">*</span></label><input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required></div></div>
            <div class="row"><div class="col-md-6 form-group"><label>تاريخ التحرير <span class="text-danger">*</span></label><input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', date('Y-m-d')) }}" required></div><div class="col-md-6 form-group"><label>تاريخ الاستحقاق <span class="text-danger">*</span></label><input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required></div></div>
            <hr>
            <div class="row"><div class="col-md-6 form-group"><label>نوع الشيك <span class="text-danger">*</span></label><select name="type" class="form-control" required><option value="receivable">شيك قبض (وارد)</option><option value="payable">شيك دفع (صادر)</option></select></div></div>
            <div class="row"><div class="col-md-6 form-group"><label>اسم الطرف (العميل/المورد) <span class="text-danger">*</span></label><input type="text" name="party_name" class="form-control" value="{{ old('party_name') }}" required></div><div class="col-md-6 form-group"><label>رقم هاتف الطرف</label><input type="text" name="party_phone" class="form-control" value="{{ old('party_phone') }}"></div></div>
            <hr>
            <div class="row"><div class="col-md-4 form-group"><label>المبلغ <span class="text-danger">*</span></label><input type="number" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" step="0.01" required></div><div class="col-md-3 form-group"><label>العملة <span class="text-danger">*</span></label><select name="currency" id="currency" class="form-control" required><option value="ILS">ILS</option><option value="USD">USD</option><option value="JOD">JOD</option></select></div><div class="col-md-2 form-group" id="exchange_rate_wrapper"><label>سعر الصرف</label><input type="number" name="exchange_rate" id="exchange_rate" class="form-control" value="{{ old('exchange_rate', 3.75) }}" step="0.001"></div><div class="col-md-3 form-group"><label>القيمة بالشيكل</label><input type="text" id="amount_ils_display" class="form-control" readonly></div></div>
            <hr>
            <div class="row"><div class="col-md-6 form-group"><label>إيداع في حساب بنكي (لحالة شيكات القبض)</label><select name="deposit_bank_account_id" class="form-control"><option value="">-- اختر حساب --</option>@foreach($bankAccounts as $account)<option value="{{ $account->id }}">{{ $account->account_name }} ({{$account->bank->name}})</option>@endforeach</select></div><div class="col-md-6 form-group"><label>صادر من حساب بنكي (لحالة شيكات الدفع)</label><select name="payment_bank_account_id" class="form-control"><option value="">-- اختر حساب --</option>@foreach($bankAccounts as $account)<option value="{{ $account->id }}">{{ $account->account_name }} ({{$account->bank->name}})</option>@endforeach</select></div></div>
            <div class="row"><div class="col-md-6 form-group"><label>ربط بمشروع</label><select name="project_id" class="form-control"><option value="">-- اختر مشروع --</option>@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->name }}</option>@endforeach</select></div></div>
            <div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes') }}</textarea></div>
        </div>
        <div class="card-footer"><button type="submit" class="btn btn-primary mr-2">حفظ الشيك</button><a href="{{ route('dashboard.checks.index') }}" class="btn btn-secondary">إلغاء</a></div>
    </div>
</form>
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

@endsection
