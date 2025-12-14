@extends('layouts.container')
@section('title', 'تعديل حركة مالية')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">تعديل حركة في سجل وليد الخالص</h3>
    </div>
    {{-- لاحظ تغيير المسار إلى update وتمرير ID الحركة --}}
    <form class="form" action="{{ route('dashboard.waleed-transactions.update', $waleedTransaction->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- مهم جداً لعملية التعديل --}}
        <div class="card-body">
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>التاريخ <span class="text-danger">*</span></label>
                    {{-- هنا نعرض القيمة القديمة من قاعدة البيانات --}}
                    <input type="date" name="date" class="form-control" value="{{ old('date', $waleedTransaction->date) }}" required/>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>قيمة الدفعة (شيكل)</label>
                    <input type="number" name="amount_shekel" class="form-control" placeholder="0.00" step="0.01" value="{{ old('amount_shekel', $waleedTransaction->amount_shekel) }}"/>
                </div>
                <div class="col-lg-6">
                    <label>قيمة الدفعة (دولار)</label>
                    <input type="number" name="amount_dollar" class="form-control" placeholder="0.00" step="0.01" value="{{ old('amount_dollar', $waleedTransaction->amount_dollar) }}"/>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>من (دفع ليد) <span class="text-danger">*</span></label>
                    <input type="text" name="paid_by" class="form-control" placeholder="اسم الشخص الذي دفع" value="{{ old('paid_by', $waleedTransaction->paid_by) }}" required/>
                </div>
                <div class="col-lg-6">
                    <label>صرف لمين <span class="text-danger">*</span></label>
                    <input type="text" name="paid_to" class="form-control" placeholder="اسم الشخص أو الجهة المستلمة" value="{{ old('paid_to', $waleedTransaction->paid_to) }}" required/>
                </div>
            </div>
            <div class="form-group">
                <label>بيانات المصاريف</label>
                <textarea name="expense_details" class="form-control" rows="3">{{ old('expense_details', $waleedTransaction->expense_details) }}</textarea>
            </div>
            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $waleedTransaction->notes) }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">حفظ التعديلات</button>
            <a href="{{ route('dashboard.waleed-transactions.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
