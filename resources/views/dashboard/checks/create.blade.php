@extends('layouts.container')
@section('title', 'إضافة شيك جديد')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">نموذج إضافة شيك جديد</h3>
    </div>
    <form class="form" action="{{ route('dashboard.checks.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-4">
                    <label>رقم الشيك:</label>
                    <input type="text" name="check_number" class="form-control" placeholder="أدخل رقم الشيك" required value="{{ old('check_number') }}"/>
                </div>
                <div class="col-lg-4">
                    <label>صاحب الشيك (المستفيد/الدافع):</label>
                    <input type="text" name="holder_name" class="form-control" placeholder="أدخل اسم صاحب الشيك" required value="{{ old('holder_name') }}"/>
                </div>
                <div class="col-lg-4">
                    <label>بنك الشيك:</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="مثال: بنك فلسطين" required value="{{ old('bank_name') }}"/>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-4">
                    <label>المبلغ:</label>
                    <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" required value="{{ old('amount') }}"/>
                </div>
                <div class="col-lg-4">
                    <label>العملة:</label>
                    <select name="currency" class="form-control" required>
                        <option value="SAR" @selected(old('currency') == 'SAR')>ريال سعودي</option>
                        <option value="USD" @selected(old('currency') == 'USD')>دولار أمريكي</option>
                        <option value="EUR" @selected(old('currency') == 'EUR')>يورو</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>تاريخ الاستحقاق:</label>
                    <input type="date" name="due_date" class="form-control" required value="{{ old('due_date', date('Y-m-d')) }}"/>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>نوع الشيك:</label>
                    <select name="type" class="form-control" required>
                        <option value="incoming" @selected(old('type') == 'incoming')>شيك وارد (مقبوضات)</option>
                        <option value="outgoing" @selected(old('type') == 'outgoing')>شيك صادر (مدفوعات)</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label>الحالة الأولية:</label>
                    <select name="status" class="form-control" required>
                        <option value="in_wallet" @selected(old('status') == 'in_wallet')>في الحافظة</option>
                        <option value="cashed" @selected(old('status') == 'cashed')>تم الصرف</option>
                        <option value="returned" @selected(old('status') == 'returned')>مرتجع</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>ملاحظات:</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">حفظ الشيك</button>
            <a href="{{ route('dashboard.checks.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
