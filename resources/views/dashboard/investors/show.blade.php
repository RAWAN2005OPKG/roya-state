@extends('layouts.container')
@section('title', 'ملف المستثمر: ' . $investor->name)

@push('styles')
<style>
    .kpi-card { background-color: #fff; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.5rem; }
    .kpi-card .label { color: #6b7280; font-size: 1rem; }
    .kpi-card .value { font-size: 2.2rem; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- بيانات المستثمر الأساسية --}}
    <div class="card card-custom gutter-b">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-100 mr-5"><span class="symbol-label font-size-h1">{{ substr($investor->name, 0, 1) }}</span></div>
                <div>
                    <h4 class="font-weight-bolder">{{ $investor->name }}</h4>
                    <div class="text-muted">ID: {{ $investor->unique_id }}</div>
                    <div class="mt-2">
                        <span class="mr-4"><i class="fas fa-id-card-alt mr-1"></i> {{ $investor->id_number ?? '-' }}</span>
                        <span><i class="fas fa-phone mr-1"></i> {{ $investor->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- بطاقات الإحصائيات المالية --}}
    <div class="row">
        <div class="col-md-4"><div class="kpi-card mb-4"><div class="label">إجمالي الاستثمار</div><div class="value text-primary">{{ number_format($investor->total_invested, 2) }} ILS</div></div></div>
        <div class="col-md-4"><div class="kpi-card mb-4"><div class="label">إجمالي المصروف له</div><div class="value text-success">{{ number_format($investor->total_paid, 2) }} ILS</div></div></div>
        <div class="col-md-4"><div class="kpi-card mb-4"><div class="label">الرصيد المتبقي</div><div class="value text-danger">{{ number_format($investor->remaining_balance, 2) }} ILS</div></div></div>
    </div>

    {{-- جدول الدفعات (القيود) --}}
    <div class="card card-custom gutter-b">
        <div class="card-header"><h3 class="card-title">كشف حساب المستثمر (القيود)</h3>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.payments.create', ['payable_type' => 'Investor', 'payable_id' => $investor->id]) }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> إضافة قيد جديد</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead><tr><th>التاريخ</th><th>النوع</th><th>المبلغ الأصلي</th><th>القيمة (ILS)</th><th>الطريقة</th><th>ملاحظات</th></tr></thead>
                <tbody>
                    @forelse($investor->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td><span class="badge badge-light-{{ $payment->type == 'in' ? 'success' : 'danger' }}">{{ $payment->type == 'in' ? 'قبض' : 'صرف' }}</span></td>
                        <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                        <td class="font-weight-bold">{{ number_format($payment->amount_ils, 2) }}</td>
                        <td>{{ $payment->method }}</td>
                        <td>{{ $payment->notes }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">لا توجد قيود مسجلة لهذا المستثمر.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- جدول المشاريع المستثمر فيها --}}
    <div class="card card-custom">
        <div class="card-header"><h3 class="card-title">المشاريع المستثمر فيها</h3></div>
        <div class="card-body">
            <table class="table">
                <thead><tr><th>المشروع</th><th>نسبة الاستثمار</th><th>المبلغ المستثمر</th><th>القيمة (ILS)</th></tr></thead>
                <tbody>
                    @forelse($investor->projects as $project)
                    <tr>
                        <td><a href="{{ route('dashboard.projects.show', $project->id) }}">{{ $project->name }}</a></td>
                        <td>{{ $project->pivot->investment_percentage }}%</td>
                        <td>{{ number_format($project->pivot->invested_amount, 2) }} {{ $project->pivot->currency }}</td>
                        <td class="font-weight-bold">{{ number_format($project->pivot->invested_amount_ils, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">هذا المستثمر غير مرتبط بأي مشاريع حالياً.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
