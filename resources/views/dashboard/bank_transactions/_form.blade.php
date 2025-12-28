
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>حدث خطأ! يرجى مراجعة الحقول التالية:</strong>
        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

{{-- =================================================================== --}}
{{--  القسم 1: تفاصيل الحركة الأساسية                                  --}}
{{-- =================================================================== --}}
<h4 class="form-section-title">1. تفاصيل الحركة الأساسية</h4>

<div class="form-group row">
    <div class="col-lg-6">
        <label>نوع الحركة <span class="text-danger">*</span></label>
        <select name="type" id="transaction_type" class="form-control" required>
            <option value="deposit" @selected(old('type', $transaction->type ?? '') == 'deposit')>إيداع</option>
            <option value="withdrawal" @selected(old('type', $transaction->type ?? '') == 'withdrawal')>سحب</option>
            <option value="transfer" @selected(old('type', $transaction->type ?? '') == 'transfer')>حوالة بنكية</option>
        </select>
    </div>
    <div class="col-lg-6">
        <label>تاريخ الحركة <span class="text-danger">*</span></label>
        <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', isset($transaction) ? $transaction->transaction_date->format('Y-m-d') : date('Y-m-d')) }}" required>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-6">
        <label>المبلغ <span class="text-danger">*</span></label>
        <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" value="{{ old('amount', $transaction->amount ?? '') }}" required>
    </div>
    <div class="col-lg-6">
        <label>العملة <span class="text-danger">*</span></label>
        <input type="text" name="currency" id="currency_field" class="form-control" value="{{ old('currency', $transaction->currency ?? 'ILS') }}" readonly>
    </div>
</div>

{{-- =================================================================== --}}
{{--  القسم 2: تحديد الحسابات (يظهر ويتغير بناءً على نوع الحركة)      --}}
{{-- =================================================================== --}}
<div id="accounts_section">
    {{-- سيتم تعبئة هذا القسم باستخدام JavaScript --}}
</div>

{{-- =================================================================== --}}
{{--  القسم 3: تفاصيل إضافية                                           --}}
{{-- =================================================================== --}}
<h4 class="form-section-title mt-5">2. تفاصيل إضافية (اختياري)</h4>
<div class="form-group">
    <label>الوصف / التفاصيل</label>
    <textarea name="details" class="form-control" rows="3">{{ old('details', $transaction->details ?? '') }}</textarea>
</div>


{{-- =================================================================== --}}
{{--  القسم 4: JavaScript للتحكم الديناميكي بالنموذج                  --}}
{{-- =================================================================== --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const transactionTypeSelect = document.getElementById('transaction_type');
    const accountsSection = document.getElementById('accounts_section');
    const currencyField = document.getElementById('currency_field');
    const bankAccounts = @json($bankAccounts->keyBy('id')); // تحويل الحسابات إلى كائن JavaScript

    // قوالب HTML لكل نوع من أنواع الحركات
    const templates = {
        deposit: `
            <h4 class="form-section-title mt-5">إلى حساب (المستقبل)</h4>
            <div class="form-group">
                <label>الحساب البنكي الذي تم الإيداع فيه <span class="text-danger">*</span></label>
                <select name="bank_account_id" class="form-control form-control-select2 dynamic-account-select" required data-placeholder="اختر الحساب">
                    <option></option>
                    @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}" data-currency="{{ $account->currency }}" @selected(old('bank_account_id', $transaction->bank_account_id ?? '') == $account->id)>
                            {{ $account->account_name }} ({{ $account->bank->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
        `,
        withdrawal: `
            <h4 class="form-section-title mt-5">من حساب (المرسل)</h4>
            <div class="form-group">
                <label>الحساب البنكي الذي تم السحب منه <span class="text-danger">*</span></label>
                <select name="bank_account_id" class="form-control form-control-select2 dynamic-account-select" required data-placeholder="اختر الحساب">
                    <option></option>
                    @foreach($bankAccounts as $account)
                        <option value="{{ $account->id }}" data-currency="{{ $account->currency }}" @selected(old('bank_account_id', $transaction->bank_account_id ?? '') == $account->id)>
                            {{ $account->account_name }} ({{ $account->bank->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
        `,
        transfer: `
            <h4 class="form-section-title mt-5">تفاصيل الحوالة</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>من حساب (المرسل) <span class="text-danger">*</span></label>
                    <select name="from_account_id" class="form-control form-control-select2 dynamic-account-select" required data-placeholder="اختر الحساب المرسل">
                        <option></option>
                        @foreach($bankAccounts as $account)
                            <option value="{{ $account->id }}" data-currency="{{ $account->currency }}" @selected(old('from_account_id') == $account->id)>
                                {{ $account->account_name }} ({{ $account->bank->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>إلى حساب (المستقبل) <span class="text-danger">*</span></label>
                    <select name="to_account_id" class="form-control form-control-select2" required data-placeholder="اختر الحساب المستقبل">
                        <option></option>
                        @foreach($bankAccounts as $account)
                            <option value="{{ $account->id }}" @selected(old('to_account_id') == $account->id)>
                                {{ $account->account_name }} ({{ $account->bank->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        `
    };

    function updateForm() {
        const selectedType = transactionTypeSelect.value;
        accountsSection.innerHTML = templates[selectedType] || '';
        $('.form-control-select2').select2({ width: '100%' });
    }

    // دالة لتحديث حقل العملة بناءً على الحساب المختار
    function updateCurrency(event) {
        const selectedOption = event.target.options[event.target.selectedIndex];
        const currency = selectedOption.dataset.currency;
        if (currency) {
            currencyField.value = currency;
        }
    }

    // ربط الأحداث
    updateForm();
    transactionTypeSelect.addEventListener('change', updateForm);
    // استخدام 'delegate' لربط الحدث بالحقول التي يتم إنشاؤها ديناميكيًا
    $(document).on('change', '.dynamic-account-select', updateCurrency);
});
</script>
@endpush
