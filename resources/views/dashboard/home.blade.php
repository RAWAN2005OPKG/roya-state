@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@push('styles')

<style>
    .kpi-card {
        background-color: #ffffff;
        border-radius: 0.75rem; /* 12px */
        padding: 25px;
        text-align: center;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); /* ظل احترافي */
        transition: all 0.3s ease;
        height: 100%; /* لجعل كل الكروت بنفس الارتفاع */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .kpi-card:hover {
        transform: translateY(-5px); /* حركة بسيطة عند المرور بالماوس */
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.1);
    }

    .kpi-card .kpi-title {
        font-size: 1rem;
        color: #858796; /* لون رمادي متناسق */
        margin-bottom: 10px;
        font-weight: 600;
    }

    .kpi-card .kpi-value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #3a3b45; /* لون أسود غير حاد */
    }

    /* ألوان خاصة للأرقام الموجبة والسالبة */
    .kpi-value.positive {
        color: #1cc88a; /* أخضر */
    }
    .kpi-value.negative {
        color: #e74a3b; /* أحمر */
    }

    /* تصميم أزرار الإجراءات السريعة */
    .quick-actions .btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 110px; /* زيادة الارتفاع قليلاً */
        font-size: 1rem;
        font-weight: 600;
        gap: 8px; /* مسافة بين الأيقونة والنص */
        border-radius: 0.75rem;
    }

    .quick-actions .btn i {
        font-size: 1.8rem; /* تكبير الأيقونة */
    }
</style>
@endpush

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
                        <div class="col-6 mb-3"><a href="{{ route('dashboard.sales.create') }}" class="btn btn-light-primary w-100"><i class="fas fa-file-invoice-dollar"></i> إنشاء فاتورة</a></div>
                        <div class="col-6 mb-3"><a href="{{ route('dashboard.expenses.create') }}" class="btn btn-light-danger w-100"><i class="fas fa-plus-circle"></i> إضافة مصروف</a></div>
                        <div class="col-6"><a href="{{ route('dashboard.products.create') }}" class="btn btn-light-success w-100"><i class="fas fa-box"></i> منتج جديد</a></div>
                        <div class="col-6"><a href="{{ route('dashboard.customers.create') }}" class="btn btn-light-info w-100"><i class="fas fa-user-plus"></i> عميل جديد</a></div>
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
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'الإيرادات',
                    data: @json($revenueData),
                    backgroundColor: 'rgba(27, 197, 189, 0.5)',
                    borderColor: 'rgba(27, 197, 189, 1)',
                    borderWidth: 1
                }, {
                    label: 'المصروفات',
                    data: @json($expenseData),
                    backgroundColor: 'rgba(246, 78, 96, 0.5)',
                    borderColor: 'rgba(246, 78, 96, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush

