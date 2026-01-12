@extends('layouts.container')
@section('title', 'ملف المورد: ' . $subcontractor->name)

@push('styles')
<style>
    .kpi-card { background-color: #f3f6f9; padding: 1.5rem; border-radius: 0.75rem; text-align: center; }
    .kpi-card .label { color: #6c757d; font-weight: 500; }
    .kpi-card .value { font-size: 2rem; font-weight: 700; }
    .header-actions .btn { margin-left: 0.5rem; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5 flex-wrap">
    <h1 class="h2">ملف المورد: {{ $subcontractor->name }} <span class="text-muted h4">({{ $subcontractor->specialization }})</span></h1>
    <div class="header-actions">
        <a href="{{ route('dashboard.subcontractors.index') }}" class="btn btn-secondary btn-sm"><i class="la la-list"></i> العودة للقائمة</a>
        <a href="{{ route('dashboard.subcontractors.edit', $subcontractor->id) }}" class="btn btn-warning btn-sm"><i class="la la-edit"></i> تعديل</a>
        <a href="#" class="btn btn-success btn-sm"><i class="la la-file-excel"></i> تصدير Excel</a>
    </div>
</div>

{{-- بطاقات الملخص المالي --}}
<div class="row mb-5">
    <div class="col-md-4"><div class="kpi-card"><div class="label">إجمالي قيمة العقود</div><div class="value text-primary">{{ number_format($subcontractor->total_contracts_value, 2) }} ILS</div></div></div>
    <div class="col-md-4"><div class="kpi-card"><div class="label">إجمالي المدفوع</div><div class="value text-success">{{ number_format($subcontractor->total_paid, 2) }} ILS</div></div></div>
    <div class="col-md-4"><div class="kpi-card"><div class="label">الرصيد المتبقي</div><div class="value text-danger">{{ number_format($subcontractor->remaining_balance, 2) }} ILS</div></div></div>
</div>

<div class="card card-custom gutter-b">
    <div class="card-header card-header-tabs-line">
        <div class="card-toolbar">
            <ul class="nav nav-tabs nav-tabs-line">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#details">البيانات الأساسية</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#contracts">العقود</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#expenses">كشف الحساب (المصروفات)</a></li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content">
            {{-- تبويب البيانات الأساسية --}}
            <div class="tab-pane active" id="details">
                <p><strong>ID:</strong> {{ $subcontractor->unique_id }}</p>
                <p><strong>رقم الهوية/الشركة:</strong> {{ $subcontractor->id_number ?? '-' }}</p>
                <p><strong>الجوال:</strong> {{ $subcontractor->phone ?? '-' }}</p>
                <p><strong>ملاحظات:</strong> {{ $subcontractor->notes ?? '-' }}</p>
            </div>

            {{-- تبويب العقود --}}
            <div class="tab-pane" id="contracts">
                @forelse($subcontractor->contracts as $contract)
                    <div class="mb-3 p-3 border rounded">
                        <h5>عقد لمشروع: {{ $contract->project->name }}</h5>
                        <p>قيمة العقد: {{ number_format($contract->contract_value, 2) }} {{ $contract->currency }} (يعادل {{ number_format($contract->value_in_ils, 2) }} ILS)</p>
                        <p>تاريخ العقد: {{ $contract->contract_date->format('Y-m-d') }}</p>
                        <p>التفاصيل: {{ $contract->contract_details ?? 'لا يوجد' }}</p>
                    </div>
                @empty
                    <p>لا توجد عقود مسجلة لهذا المورد.</p>
                @endforelse
            </div>

            {{-- تبويب المصروفات (الدفعات) --}}
            <div class="tab-pane" id="expenses">
                {{-- سنضيف زر إضافة مصروف هنا لاحقاً عندما نبني قسم المصروفات --}}
                {{-- <a href="#" class="btn btn-success btn-sm mb-4">إضافة دفعة جديدة</a> --}}
                <table class="table">
                    <thead><tr><th>التاريخ</th><th>المبلغ (ILS)</th><th>ملاحظات</th></tr></thead>
                    <tbody>
                        @forelse($subcontractor->expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                            <td>{{ number_format($expense->amount, 2) }} ILS</td>
                            <td>{{ $expense->notes }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">لا توجد دفعات مسجلة لهذا المورد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
