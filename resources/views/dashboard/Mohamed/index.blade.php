@extends('layouts.container')
@section('title', 'سجل خالد ومحمد')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">سجل حركات خالد ومحمد
                <span class="d-block text-muted pt-2 font-size-sm">عرض كل الحركات المالية المسجلة</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.khaleed-mohamed.create') }}" class="btn btn-primary font-weight-bolder">
                <i class="fas fa-plus"></i> إضافة حركة جديدة
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-head-custom table-hover">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المشروع</th>
                        <th>قيمة الدفعة (شيكل)</th>
                        <th>قيمة الدفعة (دولار)</th>
                        <th>من (دفع)</th>
                        <th>صرف لمين</th>
                        <th>بيانات المصاريف</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                            <td>{{ $transaction->project->name ?? '-' }}</td>
                            <td>{{ $transaction->amount_shekel ? number_format($transaction->amount_shekel, 2) : '-' }}</td>
                            <td>{{ $transaction->amount_dollar ? number_format($transaction->amount_dollar, 2) : '-' }}</td>
                            <td><span class="label label-inline label-light-info font-weight-bold">{{ $transaction->paid_by }}</span></td>
                            <td>{{ $transaction->paid_to }}</td>
                            <td>{{ Str::limit($transaction->expense_details, 40) }}</td>
                            <td>{{ Str::limit($transaction->notes, 40) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center p-5 text-muted">لا توجد حركات لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
