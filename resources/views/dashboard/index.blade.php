@extends('layouts.container')
@section('title', 'لوحة التحكم')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">
@endpush

@section('content')
<main class="main-content">
    {{-- رأس الصفحة --}}
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h1>
        <div class="header-actions">
            <a href="#" class="btn btn-primary"><i class="fas fa-plus"></i> إنشاء فاتورة</a>
        </div>
    </div>

    <div class="dashboard-grid">
        {{-- بطاقة نظرة عامة والاشتراك --}}
        <div class="dashboard-card col-span-12 subscription-card">
            <div>
                <h3 style="font-weight: 600;">نظرة عامة</h3>
                <p class="text-muted">مرحباً بعودتك، إليك ملخص سريع لنشاطك المالي.</p>
            </div>
            <div class="text-center">
                <h4 style="font-weight: 600;">خطة الاشتراك: مؤسسات</h4>
                <span class="badge badge-success">نشط</span>
                <a href="#" class="btn btn-sm btn-outline-primary mt-2">تجديد / ترقية</a>
            </div>
        </div>

        {{-- بطاقات الإحصائيات الرئيسية --}}
        <div class="kpi-card"><div class="label">إجمالي الإيرادات</div><div class="value text-success">{{ number_format($totalRevenue, 2) }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي المصروفات</div><div class="value text-danger">{{ number_format($totalExpenses, 2) }}</div></div>
        <div class="kpi-card"><div class="label">صافي الربح / الخسارة</div><div class="value">{{ number_format($totalRevenue - $totalExpenses, 2) }}</div></div>
        <div class="kpi-card"><div class="label">صافي التدفق النقدي</div><div class="value text-info">0.00</div></div>

        {{-- الرسم البياني --}}
        <div class="dashboard-card col-span-8">
            <div class="card-header">
                <h3 class="card-title">الإيرادات مقابل المصروفات (آخر 6 أشهر)</h3>
            </div>
            <canvas id="revenueChart"></canvas>
        </div>

        {{-- تصنيفات المصروفات --}}
    @if($expenseCategories->isEmpty())
    <div class="empty-state">
        <i class="fas fa-chart-pie"></i>
        <p>لا توجد بيانات لعرضها.</p>
    </div>
@else
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>التصنيف</th><th>المبلغ</th></tr></thead>
            <tbody>
                @foreach($expenseCategories as $exp)
                    <tr>
                        <td>{{ $exp->category->name ?? 'غير مصنف' }}</td>
                        <td>{{ number_format($exp->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif


        {{-- فواتير مبيعات متأخرة --}}
        <div class="dashboard-card col-span-6">
            <div class="card-header"><h3 class="card-title">فواتير مبيعات متأخرة</h3></div>
            @if($overdueSales->isEmpty())
                <div class="empty-state"><i class="fas fa-check-circle text-success"></i><p>لا توجد فواتير متأخرة. عمل رائع!</p></div>
            @else
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead><tr><th>العميل</th><th>المبلغ</th><th>تاريخ الاستحقاق</th></tr></thead>
                        <tbody>
                            @foreach($overdueSales as $invoice)
                                <tr><td>{{ $invoice->customer->name }}</td><td>{{ number_format($invoice->total_amount, 2) }}</td><td>{{ $invoice->due_date->format('Y-m-d') }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- فواتير مشتريات مستحقة --}}
      @if($duePurchases->isEmpty())
    <div class="empty-state"><i class="fas fa-check-circle text-success"></i><p>لا توجد فواتير مشتريات مستحقة.</p></div>
@else
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>المورد</th><th>المبلغ المستحق</th><th>تاريخ الاستحقاق</th></tr></thead>
            <tbody>
                @foreach($duePurchases as $invoice)
                    <tr>
                        <td>{{ $invoice->supplier->name ?? 'N/A' }}</td>
                        <td>{{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }}</td>
                        <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif


        {{-- إجراءات سريعة --}}
        <div class="dashboard-card col-span-12">
            <div class="card-header"><h3 class="card-title">إجراءات سريعة</h3></div>
            <div class="quick-actions-grid">
                <a href="{{ route('dashboard.sales.create') }}" class="action-item"><i class="fas fa-file-invoice-dollar"></i><span>إنشاء فاتورة</span></a>
                <a href="#" class="action-item"><i class="fas fa-receipt"></i><span>إضافة مصروف</span></a>
                <a href="#" class="action-item"><i class="fas fa-users"></i><span>عميل جديد</span></a>
                <a href="#" class="action-item"><i class="fas fa-box"></i><span>منتج جديد</span></a>
                <a href="#" class="action-item"><i class="fas fa-file-alt"></i><span>عرض التقارير</span></a>
            </div>
        </div>
    </div>
</main>

@push('scripts')
{{-- مكتبة الرسوم البيانية --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function ( ) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // أو 'line'
            data: {
                labels: {!! $chartData['labels'] !!},
                datasets: [
                    {
                        label: 'الإيرادات',
                        data: {!! $chartData['revenues'] !!},
                        backgroundColor: 'rgba(0, 158, 247, 0.6)',
                        borderColor: 'rgba(0, 158, 247, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'المصروفات',
                        data: {!! $chartData['expenses'] !!},
                        backgroundColor: 'rgba(241, 65, 108, 0.6)',
                        borderColor: 'rgba(241, 65, 108, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush
@endsection
