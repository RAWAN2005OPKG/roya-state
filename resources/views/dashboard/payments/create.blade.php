@extends('layouts.app')

@section('title', 'إضافة دفعة جديدة للعقد: ' . $contract->id)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">إضافة دفعة جديدة للعقد رقم: {{ $contract->id }}</div>

                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h5 class="text-secondary">إجمالي قيمة العقد</h5>
                                <h3 class="text-dark">{{ format_number($contract->investment_amount) }} {{ $contract->currency }}</h3>
                            </div>
                        </div>

                        {{-- المبلغ المدفوع حتى الآن --}}
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-success-light">
                                <h5 class="text-success">المبلغ المدفوع حتى الآن</h5>
                                <h3 class="text-success">{{ format_number($contract->total_paid) }} {{ $contract->currency }}</h3>
                            </div>
                        </div>

                        {{-- المبلغ المتبقي للدفع --}}
                        <div class="col-md-4">
                            <div class="p-3 border rounded @if($remaining > 0) bg-warning-light @else bg-info-light @endif">
                                <h5 class="text-warning">المبلغ المتبقي للدفع</h5>
                                <h3 class="text-warning">{{ format_number($remaining) }} {{ $contract->currency }}</h3>
                            </div>
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
                            {{-- تم إضافة max="{{ $remaining }}" للتحقق من الحد الأقصى في الواجهة --}}
                            <input id="amount" type="number" step="0.01" max="{{ $remaining }}" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required autofocus>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

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
                                تسجيل الدفعة
                            </button>
                            <a href="{{ route('dashboard.contracts.show', $contract->id) }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
