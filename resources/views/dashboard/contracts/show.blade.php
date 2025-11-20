@extends('layouts.container')

@section('title', 'تفاصيل العقد رقم: ' . $contract->contract_id)

@section('styles')
<style>
    :root {
        --primary-color: #4f46e5; --primary-hover: #3730a3; --light-bg: #f8fafc;
        --white-bg: #ffffff; --text-color: #1f2937; --text-muted: #6b7280;
        --border-color: #e5e7eb; --success-color: #10b981; --danger-color: #ef4444;
        --warning-color: #f59e0b; --info-color: #3b82f6;
        --shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06);
    }
    .main-content { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-header h1 { font-size: 2.5rem; font-weight: 700; color: var(--text-color); }
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { background-color: var(--white-bg); padding: 25px; border-radius: 12px; box-shadow: var(--shadow); text-align: center; }
    .kpi-card .label { color: var(--text-muted); margin-bottom: 10px; font-size: 1rem; }
    .kpi-card .value { font-size: 2rem; font-weight: 700; }
    .bg-light { background-color: #f3f4f6 !important; }
    .bg-success-light { background-color: #d1fae5 !important; }
    .bg-warning-light { background-color: #fef3c7 !important; }
    .bg-info-light { background-color: #dbeafe !important; }
    .text-success { color: var(--success-color) !important; }
    .text-warning { color: var(--warning-color) !important; }
    .text-dark { color: var(--text-color) !important; }
    .card-custom { border: none; border-radius: 12px; box-shadow: var(--shadow); }
    .card-header-custom { background-color: var(--primary-color); color: white; border-top-left-radius: 12px; border-top-right-radius: 12px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
    .card-header-custom h4 { margin: 0; font-weight: 600; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th, .data-table td { padding: 15px; text-align: right; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
    .data-table th { font-weight: 600; color: var(--text-muted); background-color: #f9fafb; }
    .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); color: #ffffff; }
    .btn-primary:hover { background-color: var(--primary-hover); border-color: var(--primary-hover); }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-file-contract"></i> تفاصيل العقد رقم: {{ $contract->contract_id }}</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.contracts.edit', $contract->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> تعديل العقد
            </a>
        </div>
    </div>

    {{-- رسائل الأخطاء والنجاح --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ملخص المبالغ المالية (KPI Grid) --}}
    @php
        // حساب المبلغ المتبقي بناءً على البيانات المحدثة في قاعدة البيانات
        $remaining = $contract->investment_amount - $contract->total_paid;
    @endphp
    <div class="kpi-grid">
        {{-- 1. إجمالي قيمة العقد --}}
        <div class="kpi-card bg-light">
            <div class="label">إجمالي قيمة العقد</div>
            <div class="value text-dark">{{ format_number($contract->investment_amount) }} {{ $contract->currency }}</div>
        </div>

        {{-- 2. إجمالي المبلغ المدفوع --}}
        <div class="kpi-card bg-success-light">
            <div class="label text-success">إجمالي المبلغ المدفوع</div>
            <div class="value text-success">{{ format_number($contract->total_paid) }} {{ $contract->currency }}</div>
        </div>

        {{-- 3. المبلغ المتبقي --}}
        <div class="kpi-card @if($remaining > 0) bg-warning-light @else bg-info-light @endif">
            <div class="label text-warning">المبلغ المتبقي</div>
            <div class="value text-warning">{{ format_number($remaining) }} {{ $contract->currency }}</div>
        </div>
    </div>

    {{-- تفاصيل العقد الأساسية --}}
    <div class="card card-custom mb-4">
        <div class="card-header-custom">
            <h4 class="m-0">معلومات العقد الأساسية</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3"><strong>صاحب العقد:</strong> {{ $contract->contractable->name ?? 'غير محدد' }}</div>
                <div class="col-md-6 mb-3"><strong>تاريخ التوقيع:</strong> {{ $contract->signing_date->format('Y-m-d') }}</div>
                <div class="col-md-6 mb-3"><strong>المشروع المرتبط:</strong> {{ $contract->project->project_name ?? '-' }}</div>
                <div class="col-md-6 mb-3"><strong>الحالة:</strong> {{ $contract->status }}</div>
            </div>
            @if($contract->terms)
                <hr>
                <h5>الشروط والأحكام</h5>
                <p>{{ $contract->terms }}</p>
            @endif
            @if($contract->attachment)
                <hr>
                <a href="{{ Storage::url($contract->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-file-pdf"></i> عرض ملف العقد المرفق
                </a>
            @endif
        </div>
    </div>

    {{-- جدول الدفعات --}}
    <div class="card card-custom">
        <div class="card-header-custom">
            <h4 class="m-0">الدفعات المسجلة</h4>
<a href="{{ route('dashboard.contracts.payments.create', $contract->id) }}" class="btn btn-sm btn-light text-primary">
                <i class="fas fa-plus"></i> إضافة دفعة جديدة
            </a>
        </div>
        <div class="card-body">
            @if ($contract->payments->isEmpty())
                <p class="text-center text-muted py-4">لم يتم تسجيل أي دفعات لهذا العقد بعد.</p>
            @else
                <div class="table-responsive">
                    <table class="data-table" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>تاريخ الدفعة</th>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>الخزنة</th>
                                <th>الوصف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contract->payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td><strong>{{ format_number($payment->amount) }} {{ $payment->currency }}</strong></td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ $payment->fund->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->description }}</td>
                                    <td>
                                        <form action="{{ route('dashboard.contracts.payments.destroy', [$contract->id, $payment->id]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟ سيتم تعديل المبلغ المدفوع في العقد.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
