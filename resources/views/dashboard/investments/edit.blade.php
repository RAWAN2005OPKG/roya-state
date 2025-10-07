@extends('layouts.container')
@section('title', 'تعديل الاستثمار')

@section('styles')
    <style>
        .form-container { background-color: #fff; padding: 30px; border-radius: 16px; max-width: 900px; margin: 40px auto; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { background-color: #4f46e5; color: #fff; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; margin-top: 20px; }
        .form-errors { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .section-title { font-size: 1.2rem; color: #4f46e5; margin-top: 20px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 2px solid #e5e7eb; grid-column: 1 / -1; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="form-container">
        <h2 style="font-size: 1.8rem; color: #4f46e5; margin-bottom: 25px;">تعديل استثمار في مشروع: {{ $investment->project }}</h2>

        @if ($errors->any())
            <div class="form-errors">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('dashboard.investments.update', $investment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <h3 class="section-title">البيانات الأساسية</h3>

                <div class="form-group">
                    <label for="investor_id">المستثمر *</label>
                    <select id="investor_id" name="investor_id" required>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}" @selected(old('investor_id', $investment->investor_id) == $investor->id)>{{ $investor->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">تاريخ الاستثمار *</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $investment->date->format('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label for="project">المشروع *</label>
                    <input type="text" id="project" name="project" value="{{ old('project', $investment->project) }}" required>
                </div>
                <div class="form-group">
                    <label for="type">نوع الاستثمار (شقة/أرض)</label>
                    <input type="text" id="type" name="type" value="{{ old('type', $investment->type) }}">
                </div>
                <div class="form-group">
                    <label for="amount">المبلغ *</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount', $investment->amount) }}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="currency">العملة *</label>
                    <select id="currency" name="currency" required>
                        <option value="شيكل" @selected(old('currency', $investment->currency) == 'شيكل')>شيكل</option>
                        <option value="دولار" @selected(old('currency', $investment->currency) == 'دولار')>دولار</option>
                        <option value="دينار" @selected(old('currency', $investment->currency) == 'دينار')>دينار</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="share_percentage">نسبة الحصة (%)</label>
                    <input type="number" id="share_percentage" name="share_percentage" value="{{ old('share_percentage', $investment->share_percentage) }}" step="0.01">
                </div>
                <div class="form-group">
                    <label for="status">حالة الاستثمار</label>
                    <select id="status" name="status">
                        <option value="active" @selected(old('status', $investment->status) == 'active')>نشط</option>
                        <option value="completed" @selected(old('status', $investment->status) == 'completed')>مكتمل</option>
                        <option value="cancelled" @selected(old('status', $investment->status) == 'cancelled')>ملغي</option>
                    </select>
                </div>

                <h3 class="section-title">بيانات الدفع</h3>

                <div class="form-group">
                    <label for="payment_method">طريقة الدفع</label>
                    <input type="text" id="payment_method" name="payment_method" value="{{ old('payment_method', $investment->payment_method) }}">
                </div>
                <div class="form-group">
                    <label for="payee">لمن تم الدفع</label>
                    <input type="text" id="payee" name="payee" value="{{ old('payee', $investment->payee) }}">
                </div>
                <div class="form-group">
                    <label for="payment_date">تاريخ الدفع</label>
                    <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', $investment->payment_date?->format('Y-m-d')) }}">
                </div>

                <h3 class="section-title">تفاصيل بنكية (إن وجدت)</h3>

                <div class="form-group">
                    <label for="bank_name">اسم البنك</label>
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $investment->bank_name) }}">
                </div>
                <div class="form-group">
                    <label for="other_bank_name">اسم بنك آخر</label>
                    <input type="text" id="other_bank_name" name="other_bank_name" value="{{ old('other_bank_name', $investment->other_bank_name) }}">
                </div>
                <div class="form-group">
                    <label for="transaction_id">رقم التحويلة</label>
                    <input type="text" id="transaction_id" name="transaction_id" value="{{ old('transaction_id', $investment->transaction_id) }}">
                </div>

                <h3 class="section-title">بيانات إضافية</h3>

                <div class="form-group">
                    <label for="contract_id">رقم العقد/الإيصال</label>
                    <input type="text" id="contract_id" name="contract_id" value="{{ old('contract_id', $investment->contract_id) }}">
                </div>
                <div class="form-group full-width">
                    <label for="notes">ملاحظات واتفاقات خاصة</label>
                    <textarea id="notes" name="notes" rows="4">{{ old('notes', $investment->notes) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-submit">تحديث الاستثمار</button>
        </form>
    </div>
</main>
@endsection
