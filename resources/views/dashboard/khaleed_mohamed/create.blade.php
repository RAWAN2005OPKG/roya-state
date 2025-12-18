@extends('layouts.container')
@section('title', 'إضافة حركة جديدة')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">إضافة حركة جديدة لسجل خالد ومحمد</h3>
    </div>
    <form class="form" action="{{ route('dashboard.khaleed-mohamed.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-4">
                    <label>التاريخ <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required/>
                </div>
                <div class="col-lg-4">
                    <label>من (دفع) <span class="text-danger">*</span></label>
                    <select name="paid_by" class="form-control" required>
                        <option value="" disabled selected>-- اختر --</option>
                        <option value="محمد">محمد</option>
                        <option value="خالد">خالد</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>المشروع المرتبط</label>
                    <select name="project_id" class="form-control form-control-select2">
                        <option value="">-- بدون مشروع --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>قيمة الدفعة (شيكل)</label>
                    <input type="number" name="amount_shekel" class="form-control" placeholder="0.00" step="0.01"/>
                </div>
                <div class="col-lg-6">
                    <label>قيمة الدفعة (دولار)</label>
                    <input type="number" name="amount_dollar" class="form-control" placeholder="0.00" step="0.01"/>
                </div>
            </div>
            <div class="form-group">
                <label>صرف لمين <span class="text-danger">*</span></label>
                <input type="text" name="paid_to" class="form-control" placeholder="اسم الشخص أو الجهة المستلمة" required/>
            </div>
            <div class="form-group">
                <label>بيانات المصاريف</label>
                <textarea name="expense_details" class="form-control" rows="3" placeholder="تفاصيل المصروفات..."></textarea>
            </div>
            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية..."></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">حفظ الحركة</button>
            <a href="{{ route('dashboard.khaleed-mohamed.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
