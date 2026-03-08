@extends('layouts.container')
@section('title', 'إضافة مصروف جديد')

@push('styles')
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #3730a3;
        --light-bg: #f8fafc;
        --white-bg: #ffffff;
        --text-color: #1f2937;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    body { background-color: var(--light-bg); color: var(--text-color); direction: rtl; font-family: 'Cairo', 'Arial', sans-serif; }
    .main-content { width: 100%; max-width: 1400px; margin: 20px auto; padding: 20px; }
    .page-header { text-align: center; margin-bottom: 30px; padding: 30px; background-color: var(--white-bg); border-radius: 16px; box-shadow: var(--shadow-lg); }
    .page-header h1 { font-size: 2.5rem; color: var(--text-color); gap: 15px; }
    .form-container { background-color: var(--white-bg); padding: 30px; border-radius: 16px; margin-bottom: 30px; box-shadow: var(--shadow); }
    .container-title { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid var(--border-color); display: flex; align-items: center; gap: 10px; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { margin-bottom: 8px; font-weight: 600; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; border: 2px solid var(--border-color); border-radius: 8px; transition: border-color 0.3s ease, box-shadow 0.3s ease; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .btn-submit { width: 100%; padding: 15px; border: none; border-radius: 8px; background-color: var(--primary-color); color: #fff; font-size: 1.2rem; font-weight: 700; cursor: pointer; grid-column: 1 / -1; margin-top: 20px; transition: all 0.3s ease; }
    .btn-submit:hover { background-color: var(--primary-hover); transform: translateY(-2px); }
    .hidden { display: none !important; }
    .dynamic-section { grid-column: 1 / -1; padding: 20px; background-color: var(--light-bg); border-radius: 12px; border: 2px solid var(--border-color); margin-top: 15px; }
    .dynamic-section h4 { color: var(--primary-color); margin-bottom: 15px; }
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="page-header"><h1><i class="fas fa-plus-circle"></i> إضافة مصروف جديد</h1></div>
    <div class="form-container">
        <h2 class="container-title"><i class="fas fa-money-bill-wave"></i> بيانات المصروف</h2>
        <form id="expenseForm" class="form-grid" action="{{ route('dashboard.expenses.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger" style="grid-column: 1 / -1;">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="form-group"><label for="date">تاريخ الدفع</label><input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}"></div>
            <div class="form-group"><label for="payee">اسم المستفيد</label><input type="text" name="payee" required value="{{ old('payee') }}"></div>
            <div class="form-group">
                <label for="project_id">المشروع</label>
                <select name="project_id">
                    <option value="">-- اختر المشروع (اختياري) --</option>
                    <option value="0" @selected(old('project_id') == '0')>مصروف عام (لا يتبع لمشروع)</option>
                    @foreach ($projects as $project)<option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->project_name }}</option>@endforeach
                </select>
            </div>
            <div class="form-group"><label for="amount">المبلغ</label><input type="number" id="amount" name="amount" min="0" step="0.01" required value="{{ old('amount') }}"></div>
            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" name="currency" required>
                    <option value="ILS" @selected(old('currency', 'ILS') == 'ILS')>شيكل (ILS)</option>
                    <option value="USD" @selected(old('currency') == 'USD')>دولار (USD)</option>
                    <option value="JOD" @selected(old('currency') == 'JOD')>دينار (JOD)</option>
                </select>
            </div>
            <div id="exchangeRateSection" class="form-group" style="display: none;">
                <label for="exchange_rate">سعر الصرف مقابل الشيكل</label>
                <input type="number" id="exchange_rate" name="exchange_rate" step="0.01" value="{{ old('exchange_rate', 1) }}">
                <small>القيمة بالشيكل: <b id="ils_value">0.00</b></small>
            </div>
            <div class="form-group">
                <label for="payment_source">مصدر الدفع</label>
                <select id="payment_source" name="payment_source" required>
                    <option value="">-- اختر المصدر --</option>
                    <option value="خزينة" @selected(old('payment_source') == 'خزينة')>من الخزينة العامة</option>
                    <option value="بنك" @selected(old('payment_source') == 'بنك')>من حساب بنكي</option>
                </select>
            </div>
            <div id="bankAccountSection" class="form-group" style="display: none;">
                <label for="sender_bank_account_id">من أي حساب بنكي؟</label>
                <select id="sender_bank_account_id" name="sender_bank_account_id">
                    <option value="">-- اختر الحساب --</option>
                    @foreach ($bankAccounts as $account)<option value="{{ $account->id }}" @selected(old('sender_bank_account_id') == $account->id)>{{ $account->account_name }} ({{ $account->currency }})</option>@endforeach
                </select>
            </div>
            <div class="form-group" style="grid-column: 1 / -1;"><label for="details">تفاصيل المصروف</label><textarea name="details" rows="3">{{ old('details') }}</textarea></div>
            <button type="submit" class="btn-submit"><i class="fas fa-plus-circle"></i> حفظ المصروف</button>
        </form>
    </div>
</main>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currencySelect = document.getElementById('currency');
    const exchangeRateSection = document.getElementById('exchangeRateSection');
    const exchangeRateInput = document.getElementById('exchange_rate');
    const amountInput = document.getElementById('amount');
    const ilsValueDisplay = document.getElementById('ils_value');
    const paymentSourceSelect = document.getElementById('payment_source');
    const bankAccountSection = document.getElementById('bankAccountSection');
    const bankAccountSelect = document.getElementById('sender_bank_account_id');

    function calculateILS() {
        const amount = parseFloat(amountInput.value) || 0;
        const rate = parseFloat(exchangeRateInput.value) || 1;
        ilsValueDisplay.textContent = (amount * rate).toFixed(2);
    }

    function toggleExchangeRate() {
        if (currencySelect.value === 'ILS') {
            exchangeRateSection.style.display = 'none';
            exchangeRateInput.value = 1;
        } else {
            exchangeRateSection.style.display = 'block';
            if(currencySelect.value === 'USD') exchangeRateInput.value = {{ old('exchange_rate', 3.7) }};
            if(currencySelect.value === 'JOD') exchangeRateInput.value = {{ old('exchange_rate', 5.2) }};
        }
        calculateILS();
    }

    function toggleBankSection() {
        if (paymentSourceSelect.value === 'بنك') {
            bankAccountSection.style.display = 'block';
            bankAccountSelect.required = true;
        } else {
            bankAccountSection.style.display = 'none';
            bankAccountSelect.required = false;
        }
    }

    currencySelect.addEventListener('change', toggleExchangeRate);
    amountInput.addEventListener('input', calculateILS);
    exchangeRateInput.addEventListener('input', calculateILS);
    paymentSourceSelect.addEventListener('change', toggleBankSection);

    toggleExchangeRate();
    toggleBankSection();
});
</script>
@endpush
