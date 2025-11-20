@extends('layouts.container')
@section('title', 'فواتير المشتريات')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-file-invoice-dollar"></i> فواتير المشتريات</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.purchases.invoices.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة فاتورة شراء</a>
        </div>
    </div>

    {{-- ملخص المبالغ المالية (KPI Grid) --}}
    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">عدد الموردين</div><div class="value">{{ number_format($supplierCount) }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي المستحق</div><div class="value">{{ number_format($totalRemaining, 2) }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي المدفوع</div><div class="value">{{ number_format($totalPaid, 2) }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي المشتريات</div><div class="value">{{ number_format($totalInvoices, 2) }}</div></div>
    </div>

    <div class="table-container">
        <div class="table-controls">
            <form action="{{ route('dashboard.purchases.invoices.index') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="ابحث برقم الفاتورة أو المورد..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
            {{-- فلاتر الحالة --}}
            <div class="status-filters">
                <a href="{{ route('dashboard.purchases.invoices.index') }}" class="btn btn-sm @if(empty($status)) btn-info @else btn-light @endif">الكل</a>
                <a href="{{ route('dashboard.purchases.invoices.index', ['status' => 'paid']) }}" class="btn btn-sm @if($status == 'paid') btn-success @else btn-light @endif">مدفوعة</a>
                <a href="{{ route('dashboard.purchases.invoices.index', ['status' => 'partial']) }}" class="btn btn-sm @if($status == 'partial') btn-warning @else btn-light @endif">مدفوعة جزئياً</a>
                <a href="{{ route('dashboard.purchases.invoices.index', ['status' => 'unpaid']) }}" class="btn btn-sm @if($status == 'unpaid') btn-danger @else btn-light @endif">غير مدفوعة</a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المورد</th>
                        <th>تاريخ الفاتورة</th>
                        <th>الإجمالي</th>
                        <th>المتبقي</th>
                        <th>الحالة</th>
                        <th class="no-print">تحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            <td>{{ $invoice->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>{{ number_format($invoice->total_amount, 2) }}</td>
                            <td>{{ number_format($invoice->remaining_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $invoice->status }}">{{ $invoice->status }}</span>
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('dashboard.purchases.invoices.show', $invoice->id) }}" title="عرض"><i class="fas fa-eye"></i></a>
                                {{-- ... أزرار التعديل والحذف ... --}}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center" style="padding: 2rem;">لا توجد فواتير مشتريات لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $invoices->appends(request()->query())->links() }}</div>
    </div>
</main>
@endsection
