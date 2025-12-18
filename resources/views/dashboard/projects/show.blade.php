@extends('layouts.container')
@section('title', 'عرض المشروع: ' . $project->project_name)

@push('styles')
<style>
    /* تصميم احترافي وموحد مستوحى من Metronic */
    .kpi-card {
        border-radius: 0.42rem;
        box-shadow: 0 0 30px 0 rgba(82,63,105,0.05);
    }
    .kpi-card .card-body {
        padding: 1.5rem;
    }
    .kpi-label {
        color: #B5B5C3;
        font-size: 1rem;
        font-weight: 500;
    }
    .kpi-value {
        color: #464E5F;
        font-size: 1.75rem;
        font-weight: 700;
    }
    .kpi-value .currency {
        font-size: 1rem;
        font-weight: 500;
        color: #7E8299;
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #181C32;
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- بداية: رأس الصفحة -->
    <div class="d-flex justify-content-between align-items-center mb-8">
        <h1 class="h2 text-dark font-weight-bolder">
            <i class="fas fa-project-diagram text-primary mr-3"></i>
            ملف المشروع: {{ $project->project_name }}
        </h1>
        <div>
            <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-light-primary font-weight-bold mr-2"><i class="fas fa-edit"></i> تعديل</a>
            <a href="{{ route('dashboard.projects.index') }}" class="btn btn-clean font-weight-bold">العودة للقائمة</a>
        </div>
    </div>
    <!-- نهاية: رأس الصفحة -->

    <!-- بداية: بطاقات المؤشرات المالية -->
    <div class="row mb-8">
        <div class="col-lg-4">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="kpi-label">ميزانية المشروع</div>
                    <div class="kpi-value text-primary">{{ number_format($project->budget, 2) }} <span class="currency">{{ $project->currency }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="kpi-label">إجمالي التكاليف</div>
                    <div class="kpi-value text-danger">{{ number_format($totalProjectCosts, 2) }} <span class="currency">{{ $project->currency }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card kpi-card">
                <div class="card-body">
                    <div class="kpi-label">الميزانية المتبقية</div>
                    <div class="kpi-value {{ $remainingBudget >= 0 ? 'text-success' : 'text-warning' }}">{{ number_format($remainingBudget, 2) }} <span class="currency">{{ $project->currency }}</span></div>
                </div>
            </div>
        </div>
    </div>
    <!-- نهاية: بطاقات المؤشرات المالية -->

    <div class="row">
        <div class="col-lg-12">
            <!-- بداية: قسم الجداول المالية -->
            <div class="card card-custom">
                <div class="card-header card-header-tabs-line">
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-tabs-line">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab_expenses">المصروفات العامة</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_khaleed_mohamed">سجل خالد ومحمد</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_customers">العملاء</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_investors">المستثمرون</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <!-- تبويب المصروفات العامة -->
                        <div class="tab-pane fade show active" id="tab_expenses" role="tabpanel">
                            <h4 class="mb-4">إجمالي المصروفات: {{ number_format($project->expenses->sum('amount'), 2) }}</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>التاريخ</th><th>المبلغ</th><th>المدفوع له</th><th>المصدر</th></tr></thead>
                                    <tbody>
                                        @forelse($project->expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->date }}</td>
                                                <td class="font-weight-bold">{{ number_format($expense->amount, 2) }} {{ $expense->currency }}</td>
                                                <td>{{ $expense->payee }}</td>
                                                <td>{{ $expense->payment_source }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center p-5">لا توجد مصروفات عامة مسجلة لهذا المشروع.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- تبويب سجل خالد ومحمد -->
                        <div class="tab-pane fade" id="tab_khaleed_mohamed" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>التاريخ</th><th>المبلغ</th><th>صرف لمين</th><th>دفع بواسطة</th></tr></thead>
                                    <tbody>
                                        @forelse($project->khaleedMohamedTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->date }}</td>
                                                <td class="font-weight-bold">{{ $transaction->amount_shekel ?: $transaction->amount_dollar }}</td>
                                                <td>{{ $transaction->paid_to }}</td>
                                                <td><span class="label label-light-info label-inline">{{ $transaction->paid_by }}</span></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center p-5">لا توجد حركات من سجل خالد ومحمد لهذا المشروع.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- تبويب العملاء -->
                        <div class="tab-pane fade" id="tab_customers" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead><tr><th>اسم العميل</th><th>الهاتف</th><th>قيمة الاتفاقية</th><th>إجراء</th></tr></thead>
                                    <tbody>
                                        @forelse($project->customers as $customer)
                                            <tr>
                                                <td>{{ $customer->name }}</td>
                                                <td>{{ $customer->phone ?? '-' }}</td>
                                                <td>{{ number_format($customer->agreement_amount, 2) }}</td>
                                                <td><a href="{{ route('dashboard.customers.show', $customer->id) }}" class="btn btn-sm btn-clean btn-icon" title="عرض ملف العميل"><i class="fas fa-external-link-alt"></i></a></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center p-5">لا يوجد عملاء مرتبطون بهذا المشروع.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- تبويب المستثمرين -->
                        <div class="tab-pane fade" id="tab_investors" role="tabpanel">
                            {{-- يمكنك إضافة جدول المستثمرين هنا بنفس الطريقة --}}
                            <p class="text-center p-5">سيتم عرض جدول المستثمرين هنا.</p>
                        </div>

                    </div>
                </div>
            </div>
            <!-- نهاية: قسم الجداول المالية -->
        </div>
    </div>
</div>
@endsection
