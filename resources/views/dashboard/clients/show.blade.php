@extends('layouts.container')
@section('title', 'تفاصيل العقد رقم: ' . $contract->id)

@push('styles')
<style>
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { background-color: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .kpi-card .label { color: #6c757d; font-size: 1rem; margin-bottom: 8px; display: block; }
    .kpi-card .value { font-size: 1.8rem; font-weight: 700; color: #212529; }
    .kpi-card .sub-value { font-size: 1.1rem; color: #495057; }
    .card-custom { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
</style>
@endpush

@section('content')
<main class="main-content" style="max-width: 1600px; margin: 40px auto;">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-file-invoice-dollar text-primary"></i> ملف العقد</h1>
        <a href="{{ route('dashboard.payments.create', ['contract_id' => $contract->id]) }}" class="btn btn-success btn-lg">
            <i class="fas fa-plus"></i> إضافة دفعة جديدة
        </a>
    </div>

    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    {{-- 1. بطاقات العرض الرئيسية (KPIs) --}}
    <div class="kpi-grid">
        {{-- بطاقة بيانات العميل --}}
        <div class="kpi-card">
            <span class="label">بيانات العميل</span>
            <div class="value text-primary">{{ $contract->contractable->name }}</div>
            <div class="sub-value mt-2">
                <strong>ID:</strong> {{ $contract->contractable->unique_id ?? 'N/A' }} |
                <strong>جوال:</strong> {{ $contract->contractable->phone ?? '-' }} |
                <strong>هوية:</strong> {{ $contract->contractable->id_number ?? '-' }}
            </div>
        </div>

        {{-- بطاقة بيانات الوحدة --}}
        <div class="kpi-card">
            <span class="label">بيانات الوحدة</span>
            <div class="value">{{ $contract->project->name ?? 'N/A' }}</div>
            <div class="sub-value mt-2">
                <strong>شقة:</strong> {{ $contract->projectUnit->unit_number ?? 'N/A' }} |
                <strong>طابق:</strong> {{ $contract->projectUnit->floor ?? 'N/A' }} |
                <strong>تشطيب:</strong> {{ $contract->projectUnit->finish_type === 'finished' ? 'نعم' : 'لا' }}
            </div>
        </div>

        {{-- بطاقة بيانات الدفع --}}
        <div class="kpi-card">
            <span class="label">البيانات المالية (بالشيكل)</span>
            <div class="value text-info">{{ number_format($contract->investment_amount_ils, 2) }}</div>
            <div class="sub-value mt-2">
                <span class="text-success"><strong>مدفوع:</strong> {{ number_format($contract->total_paid, 2) }}</span> |
                <span class="text-danger"><strong>متبقي:</strong> {{ number_format($contract->remaining_balance, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- 2. جدول الدفعات (كشف الحساب) --}}
    <div class="card card-custom">
        <div class="card-header"><h3 class="card-title">كشف حساب دفعات العقد</h3></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>تاريخ الدفعة</th>
                            <th>المبلغ الأصلي</th>
                            <th>القيمة (ILS)</th>
                            <th>طريقة الدفع</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contract->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                <td><strong>{{ number_format($payment->amount, 2) }}</strong> {{ $payment->currency }}</td>
                                <td class="font-weight-bolder">{{ number_format($payment->amount_ils, 2) }}</td>
                                <td>{{ $payment->method }}</td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5">لا توجد دفعات مسجلة لهذا العقد بعد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
