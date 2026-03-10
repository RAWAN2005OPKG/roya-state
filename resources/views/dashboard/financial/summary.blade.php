@extends('layouts.container')
@section('title', 'التحليل والملخص المالي')

@section('styles')
<style>
    .kpi-card {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
    }
    .kpi-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 5px;
    }
    .card-revenue::before { background: #1BC5BD; }
    .card-expense::before { background: #F64E60; }
    .card-profit::before { background: #8950FC; }
    .card-flow::before { background: #FFA800; }

    .kpi-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    .bg-light-success { background-color: #C9F7F5 !important; color: #1BC5BD !important; }
    .bg-light-danger { background-color: #FFE2E5 !important; color: #F64E60 !important; }
    .bg-light-primary { background-color: #EEE5FF !important; color: #8950FC !important; }
    .bg-light-warning { background-color: #FFF4DE !important; color: #FFA800 !important; }

    .kpi-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #181C32;
        letter-spacing: -0.5px;
    }
    .kpi-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: #B5B5C3;
        margin-bottom: 5px;
    }

    .ratio-box {
        background: #F3F6F9;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
    }
    .progress-custom {
        height: 12px;
        border-radius: 10px;
        background: #E4E6EF;
    }

    .transaction-item {
        padding: 15px 0;
        border-bottom: 1px dashed #EBedf3;
    }
    .transaction-item:last-child { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row align-items-center mb-8">
        <div class="col-md-8">
            <h1 class="font-weight-bold text-dark mb-1">لوحة التحليل المالي</h1>
            <p class="text-muted font-size-h6">مراقبة الأداء المالي، التدفقات النقدية، والمؤشرات الرئيسية</p>
        </div>
        <div class="col-md-4 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-white font-weight-bold dropdown-toggle" data-toggle="dropdown">تصدير التقرير</button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#"><i class="fas fa-file-pdf mr-2"></i> PDF</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-file-excel mr-2"></i> Excel</a>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Row -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card card-revenue">
                <div class="kpi-icon bg-light-success"><i class="fas fa-chart-line"></i></div>
                <div class="kpi-label">إجمالي الإيرادات</div>
                <div class="kpi-value">{{ number_format($totalRevenue, 2) }} <small class="font-size-sm">شيكل</small></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card card-expense">
                <div class="kpi-icon bg-light-danger"><i class="fas fa-shopping-cart"></i></div>
                <div class="kpi-label">إجمالي المصروفات</div>
                <div class="kpi-value">{{ number_format($totalExpenses, 2) }} <small class="font-size-sm">شيكل</small></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card card-profit">
                <div class="kpi-icon bg-light-primary"><i class="fas fa-gem"></i></div>
                <div class="kpi-label">صافي الربح</div>
                <div class="kpi-value text-primary">{{ number_format($netProfit, 2) }} <small class="font-size-sm">شيكل</small></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="kpi-card card-flow">
                <div class="kpi-icon bg-light-warning"><i class="fas fa-exchange-alt"></i></div>
                <div class="kpi-label">صافي التدفق النقدي</div>
                <div class="kpi-value text-warning">{{ number_format($netCashFlow, 2) }} <small class="font-size-sm">شيكل</small></div>
            </div>
        </div>
    </div>

    <!-- Main Analysis Row -->
    <div class="row">
        <!-- Chart Section -->
        <div class="col-xl-8 mb-5">
            <div class="card card-custom h-100 shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header border-0 pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">تحليل الإيرادات والمصروفات</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm">مقارنة شهرية لآخر 6 أشهر</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="financial_chart_container" style="height: 400px;">
                        <canvas id="financialMainChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ratios & Side Stats -->
        <div class="col-xl-4 mb-5">
            <div class="card card-custom h-100 shadow-sm border-0 text-center" style="border-radius: 20px;">
                <div class="card-header border-0 pt-7 text-right">
                    <h3 class="card-title font-weight-bolder text-dark">المؤشرات المالية</h3>
                </div>
                <div class="card-body">
                    <div class="ratio-box mb-6">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="font-weight-bold text-muted">هامش الربح</span>
                            <span class="font-weight-bolder text-primary">{{ number_format($profitMargin, 1) }}%</span>
                        </div>
                        <div class="progress progress-custom">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, max(0, $profitMargin)) }}%"></div>
                        </div>
                    </div>

                    <div class="ratio-box mb-6">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="font-weight-bold text-muted">نسبة المصروفات</span>
                            <span class="font-weight-bolder text-danger">{{ number_format($expenseRatio, 1) }}%</span>
                        </div>
                        <div class="progress progress-custom">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(100, max(0, $expenseRatio)) }}%"></div>
                        </div>
                    </div>

                    <div class="mt-10 px-4">
                        <div class="d-flex align-items-center mb-6">
                            <div class="symbol symbol-40 symbol-light-info mr-5"><span class="symbol-label"><i class="fas fa-info-circle text-info"></i></span></div>
                            <div class="d-flex flex-column text-right flex-grow-1">
                                <span class="text-dark-75 font-weight-bolder font-size-lg">كفاءة الأداء</span>
                                <span class="text-muted font-weight-bold">بناءً على الإيرادات الحالية</span>
                            </div>
                        </div>
                        <p class="text-muted font-size-sm text-right">أداؤك المالي يظهر استقراراً ملحوظاً. تذكر مراجعة المصروفات التشغيلية لزيادة هامش الربح.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card card-custom shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header border-0 pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">أحدث الحركات المالية</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm">سجل آخر 8 قيود محاسبية</span>
                    </h3>
                    <div class="card-toolbar">
                        <a href="{{ route('dashboard.journal-entries.index') }}" class="btn btn-light-primary font-weight-bold btn-sm">عرض الكل</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                            <thead>
                                <tr class="text-right">
                                    <th class="text-left" style="min-width: 150px">البيان / الوصف</th>
                                    <th style="min-width: 120px">التاريخ</th>
                                    <th style="min-width: 120px">المبلغ الأساسي</th>
                                    <th style="min-width: 120px">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestTransactions as $entry)
                                <tr class="text-right">
                                    <td class="text-left">
                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg text-hover-primary">{{ $entry->description }}</span>
                                        <span class="text-muted font-weight-bold">قيد محاسبي #{{ $entry->id }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-weight-bold font-size-lg">{{ $entry->date->format('Y-m-d') }}</span>
                                    </td>
                                    <td>
                                        @php $amt = $entry->items->first()->debit ?: $entry->items->first()->credit; @endphp
                                        <span class="text-dark font-weight-bolder d-block font-size-lg">{{ number_format($amt, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="label label-lg label-light-primary label-inline">مُسجل</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-10 text-muted font-size-h6">لا توجد حركات حديثة لعرضها.</td></tr>
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
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('financialMainChart').getContext('2d');
        
        // Gradient for revenue
        const revGrad = ctx.createLinearGradient(0, 0, 0, 400);
        revGrad.addColorStop(0, 'rgba(27, 197, 189, 0.4)');
        revGrad.addColorStop(1, 'rgba(27, 197, 189, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($chartData['revenue']),
                    borderColor: '#1BC5BD',
                    borderWidth: 3,
                    backgroundColor: revGrad,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#1BC5BD',
                    pointHoverRadius: 7,
                    pointRadius: 4
                }, {
                    label: 'المصروفات',
                    data: @json($chartData['expense']),
                    borderColor: '#F64E60',
                    borderWidth: 3,
                    backgroundColor: 'transparent',
                    fill: false,
                    tension: 0.4,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#F64E60',
                    pointHoverRadius: 7,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end', labels: { usePointStyle: true, padding: 20 } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [3, 3], drawBorder: false },
                        ticks: { padding: 10 }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush
