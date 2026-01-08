@extends('layouts.container')
@section('title', 'ملف المستثمر: ' . $investor->name)

@push('styles')
<style>
    .kpi-card { background-color: #ffffff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); text-align: center; }
    .kpi-card .label { color: #6b7280; margin-bottom: 10px; font-size: 1rem; }
    .kpi-card .value { font-size: 2rem; font-weight: 700; }
    @media print {
        body * { visibility: hidden; }
        .printable-area, .printable-area * { visibility: visible; }
        .printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="printable-area">
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xl-100 mr-5">
                            <div class="symbol-label" style="background-image:url('https://ui-avatars.com/api/?name={{ urlencode($investor->name ) }}&background=E1F0FF&color=3699FF&font-size=0.33')"></div>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{ $investor->name }}</a>
                            <div class="text-muted mt-1">{{ $investor->company ?? 'مستثمر فرد' }}</div>
                            <div class="d-flex flex-wrap mt-2">
                                <span class="mr-10"><i class="far fa-id-card mr-2"></i>{{ $investor->id_number ?? '-' }}</span>
                                <span><i class="fas fa-mobile-alt mr-2"></i>{{ $investor->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gutter-b">
                <div class="col-md-4">
                    <div class="kpi-card"><div class="label">إجمالي الاستثمار</div><div class="value text-primary">{{ number_format($investor->total_investment_ils, 2) }} ILS</div></div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card"><div class="label">إجمالي المصروف له</div><div class="value text-success">{{ number_format($investor->total_paid_out, 2) }} ILS</div></div>
                </div>
                <div class="col-md-4">
                    <div class="kpi-card"><div class="label">الرصيد المتبقي له</div><div class="value text-danger">{{ number_format($investor->remaining_balance, 2) }} ILS</div></div>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">كشف حساب المستثمر</h3>
                    <div class="card-toolbar no-print">
                        <button onclick="window.print();" class="btn btn-light-primary btn-sm mr-2"><i class="fas fa-print"></i> طباعة</button>
                        <a href="{{ route('dashboard.investors.export.word', $investor->id) }}" class="btn btn-info btn-sm mr-2"><i class="fas fa-file-word"></i> تصدير Word</a>
                        <a href="{{ route('dashboard.payments.create', ['payable_type' => 'Investor', 'payable_id' => $investor->id]) }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> إضافة قيد</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead><tr><th>التاريخ</th><th>النوع</th><th>المبلغ</th><th>الطريقة</th><th>ملاحظات</th></tr></thead>
                        <tbody>
                            @forelse($investor->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($payment->type == 'out') <span class="badge badge-light-success">صرف له</span>
                                    @else <span class="badge badge-light-primary">قبض منه</span> @endif
                                </td>
                                <td><strong class="text-dark">{{ number_format($payment->amount_ils, 2) }} ILS</strong></td>
                                <td>{{ $payment->method }}</td>
                                <td>{{ $payment->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center">لا توجد قيود مسجلة لهذا المستثمر.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush
