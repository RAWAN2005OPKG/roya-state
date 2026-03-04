@extends('layouts.container')
@section('title', 'تعديل حركة')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">تعديل حركة في سجل خالد ومحمد</h3>
    </div>
    {{-- تم تغيير اسم المتغير هنا ليتوافق مع الكنترولر --}}
    <form class="form" action="{{ route('dashboard.khaleed-mohamed.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group row">
                <div class="col-lg-4">
                    <label>التاريخ <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control" value="{{ old('date', $transaction->date) }}" required/>
                </div>
                <div class="col-lg-4">
                    <label>من (دفع) <span class="text-danger">*</span></label>
                    <select name="paid_by" class="form-control" required>
                        <option value="محمد" {{ old('paid_by', $transaction->paid_by) == 'محمد' ? 'selected' : '' }}>محمد</option>
                        <option value="خالد" {{ old('paid_by', $transaction->paid_by) == 'خالد' ? 'selected' : '' }}>خالد</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <label>المشروع المرتبط</label>
                    <select name="project_id" class="form-control form-control-select2">
                        <option value="">-- بدون مشروع --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $transaction->project_id) == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-6">
                    <label>قيمة الدفعة (شيكل)</label>
                    <input type="number" name="amount_shekel" class="form-control" value="{{ old('amount_shekel', $transaction->amount_shekel) }}" placeholder="0.00" step="0.01"/>
                </div>
                <div class="col-lg-6">
                    <label>قيمة الدفعة (دولار)</label>
                    <input type="number" name="amount_dollar" class="form-control" value="{{ old('amount_dollar', $transaction->amount_dollar) }}" placeholder="0.00" step="0.01"/>
                </div>
            </div>
            <div class="form-group">
                <label>صرف لمين <span class="text-danger">*</span></label>
                <input type="text" name="paid_to" class="form-control" value="{{ old('paid_to', $transaction->paid_to) }}" placeholder="اسم الشخص أو الجهة المستلمة" required/>
            </div>
            <div class="form-group">
                <label>بيانات المصاريف</label>
                <textarea name="expense_details" class="form-control" rows="3" placeholder="تفاصيل المصروفات...">{{ old('expense_details', $transaction->expense_details) }}</textarea>
            </div>
            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية...">{{ old('notes', $transaction->notes) }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">حفظ التعديلات</button>
            <a href="{{ route('dashboard.khaleed-mohamed.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
