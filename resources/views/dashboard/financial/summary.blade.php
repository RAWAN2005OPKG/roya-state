@extends('layouts.container')
@section('title', 'الملخص المالي')

@section('styles')
<style>
    .kpi-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .kpi-title {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 10px;
    }
    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: #343a40;
    }
    .kpi-icon {
        font-size: 2rem;
        margin-bottom: 15px;
    }
    .positive { color: #28a745; }
    .negative { color: #dc3545; }
    .neutral { color: #007bff; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- العنوان -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">الملخص المالي</h1>
        <span class="text-muted">نظرة عامة على الأداء المالي لشركتك.</span>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card text-center">
                <div class="kpi-icon positive"><i class="fas fa-chart-line"></i></div>
                <div class="kpi-title">إجمالي الإيرادات</div>
                <div class="kpi-value positive">{{ number_format($totalRevenue, 2) }} ج.م</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card text-center">
                <div class="kpi-icon negative"><i class="fas fa-chart-area"></i></div>
                <div class="kpi-title">إجمالي المصروفات</div>
                <div class="kpi-value negative">{{ number_format($totalExpenses, 2) }} ج.م</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card text-center">
                <div class="kpi-icon {{ $netProfit >= 0 ? 'positive' : 'negative' }}"><i class="fas fa-balance-scale"></i></div>
                <div class="kpi-title">صافي الربح / الخسارة</div>
                <div class="kpi-value {{ $netProfit >= 0 ? 'positive' : 'negative' }}">{{ number_format($netProfit, 2) }} ج.م</div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="kpi-card text-center">
                <div class="kpi-icon neutral"><i class="fas fa-hand-holding-usd"></i></div>
                <div class="kpi-title">صافي التدفق النقدي</div>
                <div class="kpi-value neutral">{{ number_format($netCashFlow, 2) }} ج.م</div>
            </div>
        </div>
    </div>

    <!-- الرسم البياني وأحدث المعاملات -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header"><h3 class="card-title">الإيرادات مقابل المصروفات (آخر 6 أشهر)</h3></div>
                <div class="card-body"><canvas id="financialChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header"><h3 class="card-title">أحدث المعاملات</h3></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                            @forelse($latestTransactions as $entry)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold">{{ $entry->description }}</div>
                                        <div class="text-muted small">{{ $entry->date->format('Y-m-d') }}</div>
                                    </td>
                                    <td class="text-left">
                                        {{-- عرض أول مبلغ في القيد كمثال --}}
                                        <span class="font-weight-bold">{{ number_format($entry->items->first()->debit ?: $entry->items->first()->credit, 2) }} ج.م</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted pt-5">لا توجد معاملات حديثة.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function( ) {
        const ctx = document.getElementById('financialChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($chartData['revenue']),
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    fill: true,
                    tension: 0.3
                }, {
                    label: 'المصروفات',
                    data: @json($chartData['expense']),
                    borderColor: 'rgba(220, 53, 69, 1)',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
    });
</script>
@endpush
