@extends('layouts.container')
@section('title', 'إجمالي كافة الحسابات')

@section('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
        --glass-bg: rgba(255, 255, 255, 0.9);
    }

    .stat-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .stat-card .card-body {
        padding: 2rem;
        position: relative;
        z-index: 1;
    }
    .stat-icon {
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 3rem;
        opacity: 0.15;
        color: white;
    }
    .bg-grad-primary { background: var(--primary-gradient); color: white; }
    .bg-grad-secondary { background: var(--secondary-gradient); color: white; }
    .bg-grad-warning { background: var(--warning-gradient); color: white; }

    .data-card {
        background: var(--glass-bg);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .data-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .table thead th {
        background: #f8f9fa;
        border-top: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        font-weight: 600;
    }
    .table td { vertical-align: middle; }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .badge-pill {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="font-weight-bold text-dark-75 mb-1">إجمالي كافة الحسابات</h1>
            <p class="text-muted font-size-lg">نظرة شاملة على السيولة، البنوك، وحركة الشيكات</p>
        </div>
        <div class="text-right">
            <span class="text-muted d-block mb-1">تاريخ اليوم</span>
            <span class="font-weight-bolder text-dark font-size-h5">{{ now()->format('Y-m-d') }}</span>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-5">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card stat-card bg-grad-primary">
                <div class="card-body">
                    <i class="fas fa-wallet stat-icon"></i>
                    <h5 class="text-uppercase opacity-70">إجمالي السيولة النقدية</h5>
                    <h2 class="display-4 font-weight-bold mb-0">{{ number_format($totalCashBalance, 2) }} <small class="font-size-h4">ILS</small></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card stat-card bg-grad-secondary">
                <div class="card-body">
                    <i class="fas fa-university stat-icon"></i>
                    <h5 class="text-uppercase opacity-70">إجمالي أرصدة البنوك</h5>
                    <h2 class="display-4 font-weight-bold mb-0">{{ number_format($totalBankBalance, 2) }} <small class="font-size-h4">ILS</small></h2>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 mb-4">
            <div class="card stat-card bg-grad-warning">
                <div class="card-body">
                    <i class="fas fa-money-check-alt stat-icon"></i>
                    <h5 class="text-uppercase opacity-70">إجمالي قيمة الشيكات</h5>
                    <h2 class="display-4 font-weight-bold mb-0">{{ number_format($totalOverallBalance, 2) }} <small class="font-size-h4">ILS</small></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tables Section -->
        <div class="col-xl-8">
            <!-- Cash Safes Table -->
            <div class="card data-card">
                <div class="data-card-header">
                    <span><i class="fas fa-cash-register text-primary mr-2"></i> المحافظ والخزائن</span>
                    <a href="{{ route('dashboard.cash-safes.index') }}" class="btn btn-sm btn-light-primary font-weight-bold">إدارة <i class="fas fa-chevron-left ml-1"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-4">اسم الخزينة</th>
                                    <th>الوصف</th>
                                    <th class="text-right pr-4">تاريخ الإنشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cashSafes as $safe)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark-75">{{ $safe->name }}</td>
                                    <td>{{ $safe->description }}</td>
                                    <td class="text-right pr-4">{{ $safe->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-10 text-muted">لا توجد سجلات حالياً</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bank Accounts Table -->
            <div class="card data-card">
                <div class="data-card-header">
                    <span><i class="fas fa-building text-success mr-2"></i> الحسابات البنكية</span>
                    <a href="{{ route('dashboard.bank-accounts.index') }}" class="btn btn-sm btn-light-success font-weight-bold">إدارة <i class="fas fa-chevron-left ml-1"></i></a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-4">رقم الحساب</th>
                                    <th>البنك</th>
                                    <th class="text-right pr-4">الرصيد الفعلي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bankAccounts as $account)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark-75">{{ $account->account_number }}</td>
                                    <td>{{ $account->bank->name ?? 'N/A' }}</td>
                                    <td class="text-right pr-4 font-weight-bolder text-success">
                                        {{ number_format($account->balance, 2) }} <small>{{ $account->currency }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-10 text-muted">لا توجد سجلات حالياً</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Analytics Section -->
        <div class="col-xl-4">
            <!-- Checks Distribution Chart -->
            <div class="card data-card h-md-100">
                <div class="data-card-header">
                    <span><i class="fas fa-chart-pie text-warning mr-2"></i> توزيع الشيكات</span>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="checkStatusChart"></canvas>
                    </div>
                    <div class="mt-5">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted font-weight-bold">في المحفظة</span>
                            <span class="text-dark font-weight-bolder">{{ $checkStats['in_wallet'] }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted font-weight-bold">برسم التحصيل</span>
                            <span class="text-dark font-weight-bolder">{{ $checkStats['under_collection'] }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="text-muted font-weight-bold">مُحصّل</span>
                            <span class="text-dark font-weight-bolder">{{ $checkStats['cleared'] }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted font-weight-bold">مرتجع / ملغى</span>
                            <span class="text-dark font-weight-bolder">{{ $checkStats['bounced'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Checks Table (Full-width) -->
    <div class="row">
        <div class="col-12">
            <div class="card data-card">
                <div class="data-card-header">
                    <span><i class="fas fa-history text-muted mr-2"></i> آخر حركات الشيكات</span>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('dashboard.financial-accounts.index') }}" method="GET" class="mr-4">
                            <select name="check_status" class="form-control form-control-sm selectpicker" data-style="btn-light" onchange="this.form.submit()">
                                <option value="">كل الحالات</option>
                                <option value="in_wallet" {{ request('check_status') == 'in_wallet' ? 'selected' : '' }}>في المحفظة</option>
                                <option value="under_collection" {{ request('check_status') == 'under_collection' ? 'selected' : '' }}>برسم التحصيل</option>
                                <option value="cleared" {{ request('check_status') == 'cleared' ? 'selected' : '' }}>مُحصّل</option>
                                <option value="bounced" {{ request('check_status') == 'bounced' ? 'selected' : '' }}>مرتجع</option>
                            </select>
                        </form>
                        <a href="{{ route('dashboard.checks.index') }}" class="btn btn-sm btn-outline-warning font-weight-bold">سجل الشيكات الكامل <i class="fas fa-external-link-alt ml-1"></i></a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="pl-4">رقم الشيك</th>
                                    <th>النوع</th>
                                    <th>المستفيد / الساحب</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>القيمة</th>
                                    <th class="text-center pr-4">الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($checks as $check)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark-75">{{ $check->check_number }}</td>
                                    <td>
                                        @if($check->type == 'receivable')
                                            <span class="label label-light-success label-inline font-weight-bold">قبض</span>
                                        @else
                                            <span class="label label-light-danger label-inline font-weight-bold">صرف</span>
                                        @endif
                                    </td>
                                    <td>{{ $check->holder_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($check->due_date)->format('Y-m-d') }}</td>
                                    <td class="font-weight-bolder">{{ number_format($check->amount_ils, 2) }}</td>
                                    <td class="text-right pr-4">
                                        @php
                                            $badgeClasses = [
                                                'in_wallet' => 'info',
                                                'under_collection' => 'warning',
                                                'cleared' => 'success',
                                                'bounced' => 'danger',
                                            ];
                                            $statusNames = [
                                                'in_wallet' => 'في المحفظة',
                                                'under_collection' => 'برسم التحصيل',
                                                'cleared' => 'مُحصّل',
                                                'bounced' => 'مرتجع/ملغى',
                                            ];
                                            $class = $badgeClasses[$check->status] ?? 'secondary';
                                            $name = $statusNames[$check->status] ?? $check->status;
                                        @endphp
                                        <span class="label label-{{ $class }} label-pill label-inline font-weight-bold">{{ $name }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-10 text-muted">لا توجد شيكات تطابق البحث</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-2 d-flex justify-content-center">
                    {{ $checks->appends(request()->query())->links() }}
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
        const ctx = document.getElementById('checkStatusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['في المحفظة', 'برسم التحصيل', 'مُحصّل', 'مرتجع/ملغى'],
                datasets: [{
                    data: [
                        {{ $checkStats['in_wallet'] }}, 
                        {{ $checkStats['under_collection'] }}, 
                        {{ $checkStats['cleared'] }}, 
                        {{ $checkStats['bounced'] }}
                    ],
                    backgroundColor: ['#3699FF', '#FFA800', '#1BC5BD', '#F64E60'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((context.raw / total) * 100).toFixed(1);
                                return `${context.label}: ${context.raw} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '70%',
            }
        });
    });
</script>
@endpush
