@extends('layouts.container')
@section('title', 'تحليل الأرباح السنوية')

@section('styles')
<style>
    .profit-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .summary-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: 700;
        border-bottom: 2px solid #ebedf2;
    }
    .chart-box {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .year-badge {
        font-size: 1.25rem;
        padding: 0.5rem 1.5rem;
        border-radius: 10px;
        background: #f3f6f9;
        color: #3f4254;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="font-weight-bold text-dark mb-1">تحليل الأرباح السنوية</h1>
            <p class="text-muted">مقارنة الأداء المالي والنمو السنوي</p>
        </div>
        @if($latestYear)
        <div class="year-badge">
            <i class="fas fa-calendar-alt mr-2 text-primary"></i> السنة المالية: {{ $latestYear }}
        </div>
        @endif
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-9">
            <div class="chart-box">
                <h4 class="mb-5 font-weight-bold">الإيرادات مقابل المصروفات (شهرياً لعام {{ $latestYear }})</h4>
                <div style="height: 350px;">
                    <canvas id="annualTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="row">
                @php
                    $latestStats = collect($annualData)->first() ?? ['revenue' => 0, 'expenses' => 0, 'net_profit' => 0];
                @endphp
                <div class="col-12 mb-4">
                    <div class="card profit-card bg-light-success border-0">
                        <div class="card-body">
                            <div class="summary-icon bg-success mb-3 text-white"><i class="fas fa-arrow-up"></i></div>
                            <span class="text-muted d-block mb-1">إيرادات السنة</span>
                            <h3 class="font-weight-bolder text-success mb-0">{{ number_format($latestStats['revenue'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-4">
                    <div class="card profit-card bg-light-danger border-0">
                        <div class="card-body">
                            <div class="summary-icon bg-danger mb-3 text-white"><i class="fas fa-arrow-down"></i></div>
                            <span class="text-muted d-block mb-1">مصروفات السنة</span>
                            <h3 class="font-weight-bolder text-danger mb-0">{{ number_format($latestStats['expenses'], 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card profit-card bg-light-primary border-0">
                        <div class="card-body">
                            <div class="summary-icon bg-primary mb-3 text-white"><i class="fas fa-balance-scale"></i></div>
                            <span class="text-muted d-block mb-1">صافي الربح</span>
                            <h3 class="font-weight-bolder text-primary mb-0">{{ number_format($latestStats['net_profit'], 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card profit-card">
        <div class="card-header bg-transparent border-0 pt-6">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">سجل الأداء السنوي</span>
                <span class="text-muted mt-1 font-weight-bold font-size-sm">تفاصيل الإيرادات والمصروفات لكل سنة مالية</span>
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center table-hover">
                    <thead>
                        <tr class="text-right">
                            <th class="text-left" style="min-width: 100px">السنة المالية</th>
                            <th style="min-width: 150px">إجمالي الإيرادات</th>
                            <th style="min-width: 150px">إجمالي المصروفات</th>
                            <th style="min-width: 150px">صافي الربح / الخسارة</th>
                            <th style="min-width: 100px">النمو</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($annualData as $index => $data)
                        <tr class="text-right">
                            <td class="text-left">
                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $data['year'] }}</span>
                            </td>
                            <td>
                                <span class="text-success font-weight-bold">{{ number_format($data['revenue'], 2) }} ILS</span>
                            </td>
                            <td>
                                <span class="text-danger font-weight-bold">{{ number_format($data['expenses'], 2) }} ILS</span>
                            </td>
                            <td>
                                <span class="font-size-h6 font-weight-bolder {{ $data['net_profit'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                    {{ number_format($data['net_profit'], 2) }} ILS
                                </span>
                            </td>
                            <td>
                                @php
                                    $prevYear = isset($annualData[$index + 1]) ? $annualData[$index + 1]['net_profit'] : null;
                                    $growth = $prevYear && $prevYear != 0 ? (($data['net_profit'] - $prevYear) / abs($prevYear)) * 100 : null;
                                @endphp
                                @if($growth !== null)
                                    <span class="label label-light-{{ $growth >= 0 ? 'success' : 'danger' }} label-inline font-weight-bold">
                                        {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-muted">لا توجد بيانات مالية مسجلة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('annualTrendChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthlyData['labels']),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($monthlyData['revenue']),
                    borderColor: '#1BC5BD',
                    backgroundColor: 'rgba(27, 197, 189, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#1BC5BD'
                }, {
                    label: 'المصروفات',
                    data: @json($monthlyData['expenses']),
                    borderColor: '#F64E60',
                    backgroundColor: 'rgba(246, 78, 96, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#F64E60'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', align: 'end' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5] },
                        ticks: {
                            callback: function(value) { return value.toLocaleString() + ' ILS'; }
                        }
                    },
                    x: { grid: { display: false } }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                }
            }
        });
    });
</script>
@endpush
