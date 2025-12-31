@extends('layouts.container')
@section('title', 'القيود اليومية (الدفعات)')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-invoice-dollar text-success mr-2"></i> القيود اليومية</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.payments.create') }}" class="btn btn-success"><i class="la la-plus"></i> تسجيل قيد جديد</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.payments.index') }}" class="mb-8 p-4 bg-light rounded">
            <div class="row">
                <div class="col-md-3 form-group"><label>بحث بالاسم/ID</label><input type="text" name="search_payable" class="form-control" placeholder="اسم العميل/المستثمر..." value="{{ request('search_payable') }}"></div>
                <div class="col-md-3 form-group"><label>نوع الحركة</label><select name="payment_type" class="form-control"><option value="">الكل</option><option value="in" @selected(request('payment_type') == 'in')>قبض</option><option value="out" @selected(request('payment_type') == 'out')>صرف</option></select></div>
                <div class="col-md-2 form-group"><label>من تاريخ</label><input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"></div>
                <div class="col-md-2 form-group"><label>إلى تاريخ</label><input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}"></div>
                <div class="col-md-2 align-self-end"><button type="submit" class="btn btn-primary">فلترة</button><a href="{{ route('dashboard.payments.index') }}" class="btn btn-secondary ml-2">إلغاء</a></div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>التاريخ</th>
                        <th>الكيان</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>القيمة (ILS)</th>
                        <th>الطريقة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td>
                            @if($payment->payable)
                                <span class="font-weight-bold">{{ $payment->payable->name }}</span>

                                <small class="text-muted">{{ str_replace('App\\Models\\', '', $payment->payable_type) }} / {{ $payment->payable->unique_id }}</small>
                            @else
                                <span class="text-danger">كيان محذوف</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->type == 'in')
                                <span class="badge badge-light-success">قبض</span>
                            @else
                                <span class="badge badge-light-danger">صرف</span>
                            @endif
                        </td>
                        <td class="font-weight-bold">{{ number_format($payment->amount, 2) }} <span class="text-muted">{{ $payment->currency }}</span></td>
                        <td class="font-weight-bolder">{{ number_format($payment->amount_ils, 2) }}</td>
                        <td>{{ $payment->method }}</td>
                        <td>{{ Str::limit($payment->notes, 30) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5">لا توجد قيود مسجلة تطابق البحث.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
