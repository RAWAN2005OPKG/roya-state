@extends('layouts.container')
@section('title', 'تعديل سند خالد #' . $khaled->id)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>.select2-container .select2-selection--single { height: calc(1.5em + 1.3rem + 2px  ) !important; }</style>
@endpush

@section('content')
<form action="{{ route('dashboard.khaled.update', $khaled->id) }}" method="POST" id="voucher-form">
    @csrf
    @method('PUT')
    @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="card mb-5"><div class="card-body">
        <h4 class="card-title">المعلومات الأساسية</h4>
        <div class="row">
            <div class="col-md-4 form-group"><label>تاريخ السند <span class="text-danger">*</span></label><input type="date" name="voucher_date" class="form-control" value="{{ old('voucher_date', $khaled->voucher_date->format('Y-m-d')) }}" required></div>
            <div class="col-md-4 form-group"><label>نوع السند <span class="text-danger">*</span></label><select name="type" id="voucher_type" class="form-control" required><option value="receipt" {{ $khaled->type == 'receipt' ? 'selected' : '' }}>سند قبض</option><option value="payment" {{ $khaled->type == 'payment' ? 'selected' : '' }}>سند صرف</option></select></div>
            <div class="col-md-4 form-group"><label>طريقة الدفع <span class="text-danger">*</span></label><select name="payment_method" id="payment_method" class="form-control" required><option value="cash" {{ $khaled->payment_method == 'cash' ? 'selected' : '' }}>نقدي</option><option value="bank_transfer" {{ $khaled->payment_method == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option><option value="check" {{ $khaled->payment_method == 'check' ? 'selected' : '' }}>شيك</option></select></div>
        </div>
        <div class="form-group"><label>البيان/الوصف <span class="text-danger">*</span></label><textarea name="description" class="form-control" required>{{ old('description', $khaled->description) }}</textarea></div>
    </div></div>

    <div class="card mb-5"><div class="card-body">
        <h4 class="card-title">تفاصيل المبلغ</h4>
        <div class="row">
            <div class="col-md-4 form-group"><label>المبلغ <span class="text-danger">*</span></label><input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount', $khaled->amount) }}" required></div>
            <div class="col-md-3 form-group"><label>العملة <span class="text-danger">*</span></label><select name="currency" id="currency" class="form-control" required><option value="ILS" {{ $khaled->currency == 'ILS' ? 'selected' : '' }}>ILS</option><option value="USD" {{ $khaled->currency == 'USD' ? 'selected' : '' }}>USD</option><option value="JOD" {{ $khaled->currency == 'JOD' ? 'selected' : '' }}>JOD</option></select></div>
            <div class="col-md-2 form-group" id="exchange_rate_wrapper"><label>سعر الصرف</label><input type="number" name="exchange_rate" id="exchange_rate" class="form-control" step="0.001" value="{{ old('exchange_rate', $khaled->exchange_rate) }}"></div>
            <div class="col-md-3 form-group"><label>القيمة بالشيكل</label><input type="text" id="amount_ils_display" class="form-control" readonly></div>
        </div>
    </div></div>

    <div id="payment-details-wrapper"></div>

    <div class="card mb-5"><div class="card-body">
        <h4 class="card-title">ربط وتحويل (اختياري)</h4>
        <div class="row">
            <div class="col-md-4 form-group"><label>ربط بمشروع</label><select name="project_id" class="form-control select2-basic"><option value="">-- اختر مشروع --</option>@foreach($projects as $project)<option value="{{ $project->id }}" {{ $khaled->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>ربط بعميل</label><select name="client_id" class="form-control select2-basic"><option value="">-- اختر عميل --</option>@foreach($clients as $client)<option value="{{ $client->id }}" {{ $khaled->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label>ربط بمستثمر</label><select name="investor_id" class="form-control select2-basic"><option value="">-- اختر مستثمر --</option>@foreach($investors as $investor)<option value="{{ $investor->id }}" {{ $khaled->investor_id == $investor->id ? 'selected' : '' }}>{{ $investor->name }}</option>@endforeach</select></div>
        </div>
        <div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes', $khaled->notes) }}</textarea></div>
    </div></div>

    <button type="submit" class="btn btn-primary btn-lg">تحديث السند</button>
    <a href="{{ route('dashboard.khaled.index') }}" class="btn btn-secondary btn-lg">إلغاء</a>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document ).ready(function() {
    $('.select2-basic').select2();

    const khaledData = @json($khaled);
    const exchangeRates = @json($exchangeRates);
    const cashSafesOptions = `@foreach($cashSafes as $safe)<option value="{{ $safe->id }}" ${khaledData.cash_safe_id == "{{ $safe->id }}" ? 'selected' : ''}>{{ $safe->name }} ({{ $safe->currency }})</option>@endforeach`;
    const bankAccountsOptions = `@foreach($bankAccounts as $account)<option value="{{ $account->id }}">{{ $account->account_name }} - {{ $account->bank->name ?? '' }} ({{ $account->currency }})</option>@endforeach`;

    function renderPaymentDetails() {
        const method = $('#payment_method').val();
        const type = $('#voucher_type').val();
        const wrapper = $('#payment-details-wrapper');
        let html = '';

        if (method === 'cash') {
            html = `<div class="card mb-5"><div class="card-body"><h4>تفاصيل الدفع النقدي</h4>
                <div class="form-group"><label>${type === 'receipt' ? 'إلى خزينة' : 'من خزينة'} <span class="text-danger">*</span></label><select name="cash_safe_id" class="form-control select2-basic" required>${cashSafesOptions}</select></div>
                <div class="row"><div class="col-md-6 form-group"><label>اسم المستلم/المسلم</label><input type="text" name="handler_name" class="form-control" value="${khaledData.handler_name || ''}"></div><div class="col-md-6 form-group"><label>الوظيفة</label><input type="text" name="handler_role" class="form-control" value="${khaledData.handler_role || ''}"></div></div>
            </div></div>`;
        } else if (method === 'bank_transfer') {
            html = `<div class="card mb-5"><div class="card-body"><h4>تفاصيل التحويل البنكي</h4>
                <div class="form-group"><label>من حساب بنكي</label><select name="from_bank_account_id" class="form-control select2-basic"><option value="">-- اختر --</option>${bankAccountsOptions}</select></div>
                <div class="form-group"><label>إلى حساب بنكي</label><select name="to_bank_account_id" class="form-control select2-basic"><option value="">-- اختر --</option>${bankAccountsOptions}</select></div>
            </div></div>`;
        } else if (method === 'check') {
            html = `<div class="card mb-5"><div class="card-body"><h4>تفاصيل الشيك</h4>
                <div class="row">
                    <div class="col-md-4 form-group"><label>رقم الشيك <span class="text-danger">*</span></label><input type="text" name="check_number" class="form-control" value="${khaledData.check_number || ''}" required></div>
                    <div class="col-md-4 form-group"><label>اسم صاحب الشيك (الساحب) <span class="text-danger">*</span></label><input type="text" name="check_owner_name" class="form-control" value="${khaledData.check_owner_name || ''}" required></div>
                    <div class="col-md-4 form-group"><label>اسم البنك <span class="text-danger">*</span></label><input type="text" name="check_bank_name" class="form-control" value="${khaledData.check_bank_name || ''}" required></div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group"><label>تاريخ الاستحقاق <span class="text-danger">*</span></label><input type="date" name="check_due_date" class="form-control" value="${khaledData.check_due_date ? khaledData.check_due_date.split('T')[0] : ''}" required></div>
                </div>
            </div></div>`;
        }
        wrapper.html(html);
        wrapper.find('.select2-basic').select2();
        // Set selected values for bank accounts after rendering
        if (method === 'bank_transfer') {
            wrapper.find('select[name="from_bank_account_id"]').val(khaledData.from_bank_account_id).trigger('change');
            wrapper.find('select[name="to_bank_account_id"]').val(khaledData.to_bank_account_id).trigger('change');
        }
    }

    function calculateILS() {
        const amount = parseFloat($('#amount').val()) || 0;
        const rate = parseFloat($('#exchange_rate').val()) || 1;
        $('#amount_ils_display').val((amount * rate).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    function updateExchangeRate() {
        const currency = $('#currency').val();
        if (currency === 'ILS') {
            $('#exchange_rate_wrapper').hide();
            $('#exchange_rate').val(1);
            calculateILS();
        } else {
            $('#exchange_rate_wrapper').show();
            if ($('#exchange_rate').val() == 1) { // Update only if it's the default value
                 $('#exchange_rate').val(exchangeRates[currency] || 1);
            }
            calculateILS();
        }
    }

    $('#payment_method, #voucher_type').on('change', renderPaymentDetails);
    $('#amount, #exchange_rate').on('input', calculateILS);
    $('#currency').on('change', updateExchangeRate);

    renderPaymentDetails();
    updateExchangeRate();
});
</script>
@endpush
@endsection
