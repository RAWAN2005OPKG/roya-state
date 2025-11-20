@extends('layouts.container')

@section('title', 'إضافة دفعة جديدة للعقد: ' . $contract->contract_id)

@section('styles')
<style>
    :root {
        --primary-color: #4f46e5; --primary-hover: #3730a3; --light-bg: #f8fafc;
        --white-bg: #ffffff; --text-color: #1f2937; --text-muted: #6b7280;
        --border-color: #e5e7eb; --success-color: #10b981; --danger-color: #ef4444;
        --warning-color: #f59e0b; --info-color: #3b82f6;
        --shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06);
    }
    .main-content { max-width: 900px; margin: 40px auto; padding: 0 20px; }
    .card-custom { border: none; border-radius: 12px; box-shadow: var(--shadow); }
    .card-header-custom { background-color: var(--primary-color); color: white; border-top-left-radius: 12px; border-top-right-radius: 12px; padding: 15px 20px; }
    .card-header-custom h4 { margin: 0; font-weight: 600; }
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { padding: 20px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); text-align: center; }
    .kpi-card h5 { margin-bottom: 5px; font-size: 1rem; }
    .kpi-card h3 { font-size: 1.5rem; font-weight: 700; }
    .bg-light { background-color: #f3f4f6 !important; }
    .bg-success-light { background-color: #d1fae5 !important; }
    .bg-warning-light { background-color: #fef3c7 !important; }
    .bg-info-light { background-color: #dbeafe !important; }
    .text-success { color: var(--success-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-dark { color: var(--text-color) !important; }
    .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); color: #ffffff; }
    .btn-secondary { background-color: var(--text-muted); border-color: var(--text-muted); color: #ffffff; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="card card-custom">
        <div class="card-header-custom">
            <h4 class="m-0">إضافة دفعة جديدة للعقد رقم: {{ $contract->contract_id }}</h4>
        </div>

        <div class="card-body">

            {{-- ملخص المبالغ المالية --}}
            <div class="kpi-grid">
                {{-- 1. إجمالي قيمة العقد --}}
                <div class="kpi-card bg-light">
                    <h5 class="text-secondary">إجمالي قيمة العقد</h5>
                    <h3 class="text-dark">{{ format_number($contract->investment_amount) }} {{ $contract->currency }}</h3>
                </div>

                {{-- 2. المبلغ المدفوع حتى الآن --}}
                <div class="kpi-card bg-success-light">
                    <h5 class="text-success">المبلغ المدفوع حتى الآن</h5>
                    <h3 class="text-success">{{ format_number($contract->total_paid) }} {{ $contract->currency }}</h3>
                </div>

                {{-- 3. المبلغ المتبقي للدفع --}}
                <div class="kpi-card @if($remaining > 0) bg-warning-light @else bg-info-light @endif">
                    <h5 class="text-warning">المبلغ المتبقي للدفع</h5>
                    <h3 class="text-warning">{{ format_number($remaining) }} {{ $contract->currency }}</h3>
                </div>
            </div>

            {{-- رسائل الأخطاء والنجاح --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- نموذج إضافة الدفعة --}}
            <form method="POST" action="{{ route('dashboard.contracts.payments.store', $contract->id) }}">
                @csrf

                {{-- حقل المبلغ --}}
                <div class="form-group mb-3">
                    <label for="amount">مبلغ الدفعة (المتبقي: {{ format_number($remaining) }} {{ $contract->currency }})</label>
                    <input id="amount" type="number" step="0.01" max="{{ $remaining }}" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required autofocus>
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

               {{-- حقل العملة (للقراءة فقط) --}}
               <div class="form-group mb-3">
                    <label for="currency">العملة</label>
                   <input id="currency" type="text" class="form-control @error('currency') is-invalid @enderror" name="currency" value="{{ old('currency', $contract->currency) }}" required readonly>
                    @error('currency')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- حقل تاريخ الدفعة --}}
                <div class="form-group mb-3">
                    <label for="payment_date">تاريخ الدفعة</label>
                    <input id="payment_date" type="date" class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- حقل طريقة الدفع --}}
                <div class="form-group mb-3">
                    <label for="payment_method">طريقة الدفع</label>
                    <select id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" name="payment_method" required>
                        <option value="">اختر طريقة الدفع</option>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>نقداً</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                        <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>شيك</option>
                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                    @error('payment_method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- حقل الخزنة (Fund) --}}
                <div class="form-group mb-3">
                    <label for="fund_id">الخزنة/الصندوق</label>
                    <select id="fund_id" class="form-control @error('fund_id') is-invalid @enderror" name="fund_id" required>
                        <option value="">اختر الخزنة</option>
                        @foreach ($funds as $fund)
                            <option value="{{ $fund->id }}" {{ old('fund_id') == $fund->id ? 'selected' : '' }}>{{ $fund->name }}</option>
                        @endforeach
                    </select>
                    @error('fund_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- حقل الوصف --}}
                <div class="form-group mb-3">
                    <label for="description">الوصف/ملاحظات (اختياري)</label>
                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> تسجيل الدفعة
                    </button>
                    <a href="{{ route('dashboard.contracts.show', $contract->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
