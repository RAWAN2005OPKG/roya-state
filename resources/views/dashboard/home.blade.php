@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
{{-- هذا الـ CSS مخصص لهذه الصفحة فقط --}}
<style>
    .kpi-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .kpi-card .kpi-title {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 10px;
    }
    .kpi-card .kpi-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #343a40;
    }
    .kpi-value.positive { color: #28a745; }
    .kpi-value.negative { color: #dc3545; }

    .quick-actions .btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100px;
        font-size: 1rem;
        gap: 8px;
    }
    .quick-actions .btn i {
        font-size: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    {{-- صف الإحصائيات الرئيسي --}}
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="kpi-card">
                <div class="kpi-title">إجمالي الإيرادات</div>
                <div class="kpi-value positive">{{ number_format($totalRevenue, 2) }} ج.م</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="kpi-card">
                <div class="kpi-title">إجمالي المصروفات</div>
                <div class="kpi-value negative">{{ number_format($totalExpenses, 2) }} ج.م</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="kpi-card">
                <div class="kpi-title">صافي الربح / الخسارة</div>
                <div class="kpi-value {{ $netProfit >= 0 ? 'positive' : 'negative' }}">{{ number_format($netProfit, 2) }} ج.م</div>
            </div>
        </div>
    </div>

    {{-- صف الرسم البياني والإجراءات السريعة --}}
    <div class="row">
        {{-- الرسم البياني --}}
        <div class="col-lg-8 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header">
                    <h3 class="card-title">الإيرادات مقابل المصروفات (آخر 6 أشهر)</h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueExpenseChart"></canvas>
                </div>
            </div>
        </div>

        {{-- الإجراءات السريعة --}}
        <div class="col-lg-4 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header">
                    <h3 class="card-title">إجراءات سريعة</h3>
                </div>
                <div class="card-body">
                    <div class="row quick-actions">
                        <div class="col-6 mb-3"><a href="#" class="btn btn-light-primary w-100"><i class="fas fa-file-invoice-dollar"></i> إنشاء فاتورة</a></div>
                        <div class="col-6 mb-3"><a href="#" class="btn btn-light-danger w-100"><i class="fas fa-plus-circle"></i> إضافة مصروف</a></div>
                        <div class="col-6"><a href="#" class="btn btn-light-success w-100"><i class="fas fa-box"></i> منتج جديد</a></div>
                        <div class="col-6"><a href="#" class="btn btn-light-info w-100"><i class="fas fa-user-plus"></i> عميل جديد</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- صف الفواتير المتأخرة --}}
    <div class="row">
        {{-- فواتير مبيعات متأخرة --}}
        <div class="col-lg-6 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header">
                    <h3 class="card-title">فواتير مبيعات متأخرة</h3>
                </div>
                <div class="card-body">
                    @forelse($overdueSalesInvoices as $invoice)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <a href="#">{{ $invoice->customer->name ?? 'عميل محذوف' }}</a>
                                <span class="d-block text-muted">فاتورة رقم: {{ $invoice->number }}</span>
                            </div>
                            <div class="text-danger font-weight-bold">{{ number_format($invoice->total_amount, 2) }} ج.م</div>
                        </div>
                    @empty
                        <p class="text-center text-muted mt-5">لا توجد فواتير مبيعات متأخرة. عمل رائع!</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- فواتير مشتريات مستحقة --}}
        <div class="col-lg-6 mb-4">
            <div class="card card-custom h-100">
                <div class="card-header">
                    <h3 class="card-title">فواتير مشتريات مستحقة</h3>
                </div>
                <div class="card-body">
                    @forelse($overduePurchaseInvoices as $invoice)
                         <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <a href="#">{{ $invoice->supplier->name ?? 'مورد محذوف' }}</a>
                                <span class="d-block text-muted">فاتورة رقم: {{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="text-warning font-weight-bold">{{ number_format($invoice->total_amount, 2) }} ج.م</div>
                        </div>
                    @empty
                        <p class="text-center text-muted mt-5">لا توجد فواتير مشتريات مستحقة.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- مكتبة Chart.js لرسم المخططات البيانية --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function( ) {
        const ctx = document.getElementById('revenueExpenseChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // يمكنك تغييره إلى 'line'
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($revenueData),
                    backgroundColor: 'rgba(40, 167, 69, 0.5)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }, {
                    label: 'المصروفات',
                    data: @json($expenseData),
                    backgroundColor: 'rgba(220, 53, 69, 0.5)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush
