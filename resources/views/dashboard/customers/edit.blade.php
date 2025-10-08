@extends('layouts.container')
@section('title', 'تعديل بيانات العميل')

@section('styles')
    <style>
        .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e9ecef; }
        .form-section-title { font-size: 1.3rem; color: #4f46e5; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4f46e5; }
    </style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 1100px; margin: auto;">
        <div class="card-header">
            <h3 class="card-title">تعديل بيانات العميل: {{ $customer->name }}</h3>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.customers.index') }}" class="btn btn-secondary">العودة للقائمة</a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>خطأ!</strong> يرجى مراجعة الحقول التالية:
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <form action="{{ route('dashboard.customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- بيانات العميل --}}
                <div class="form-section">
                    <h4 class="form-section-title">1. بيانات العميل</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>اسم العميل *</label><input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required></div>
                        <div class="col-md-6 form-group mb-3"><label>رقم الجوال</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"></div>
                    </div>
                </div>

                {{-- تفاصيل الاتفاقية --}}
                <div class="form-section">
                    <h4 class="form-section-title">2. تفاصيل الاتفاقية</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>المشروع</label><input type="text" name="project" class="form-control" value="{{ old('project', $customer->project) }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>الوحدة/الشقة *</label><input type="text" name="unit" class="form-control" value="{{ old('unit', $customer->unit) }}" required></div>
                        <div class="col-md-4 form-group mb-3"><label>قيمة الاتفاقية *</label><input type="number" name="agreement_amount" class="form-control" value="{{ old('agreement_amount', $customer->agreement_amount) }}" step="0.01" required></div>
                        <div class="col-md-4 form-group mb-3">
                            <label>العملة *</label>
                            <select name="currency" class="form-control" required>
                                <option value="شيكل" @selected(old('currency', $customer->currency) == 'شيكل')>شيكل</option>
                                <option value="دولار" @selected(old('currency', $customer->currency) == 'دولار')>دولار</option>
                                <option value="دينار" @selected(old('currency', $customer->currency) == 'دينار')>دينار</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group mb-3">
                            <label>طريقة الدفع *</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="cash" @selected(old('payment_method', $customer->payment_method) == 'cash')>كاش</option>
                                <option value="check" @selected(old('payment_method', $customer->payment_method) == 'check')>شيك</option>
                                <option value="bank_transaction" @selected(old('payment_method', $customer->payment_method) == 'bank_transaction')>تحويل بنكي</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group mb-3"><label>تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control" value="{{ old('due_date', $customer->due_date ? $customer->due_date->format('Y-m-d') : '') }}"></div>
                    </div>
                </div>

                {{-- ملف العقد --}}
                <div class="form-section">
                    <h4 class="form-section-title">3. مرفقات</h4>
                    <div class="form-group">
                        <label>ملف العقد (اختياري) - ارفق ملف جديد لتغيير الملف الحالي</label>
                        <input type="file" name="contract_file" class="form-control">
                        @if($customer->contract_file)
                            <div class="mt-2">
                                الملف الحالي: <a href="{{ asset('storage/' . $customer->contract_file) }}" target="_blank">عرض الملف</a>
                            </div>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">تحديث البيانات</button>
            </form>
        </div>
    </div>
</main>
@endsection
