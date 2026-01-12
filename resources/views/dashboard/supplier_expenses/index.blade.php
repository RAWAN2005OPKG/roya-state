@extends('layouts.container')
@section('title', 'سجل مصروفات الموردين')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-receipt text-info mr-2"></i> سجل مصروفات الموردين والمقاولين</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.supplier_expenses.create') }}" class="btn btn-success btn-sm"><i class="la la-plus"></i> تسجيل دفعة جديدة</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المبلغ (ILS)</th>
                        <th>صُرِفَ لِـ</th>
                        <th>البيان (ملاحظات)</th>
                        <th>القائم بالعملية</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td class="font-weight-bold text-danger">{{ number_format($expense->amount, 2) }}</td>
                        <td>
                            @if($expense->payable)
                                <a href="{{ route('dashboard.subcontractors.show', $expense->payable->id) }}">{{ $expense->payable->name }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $expense->notes ?? '-' }}</td>
                        <td>{{ $expense->paid_by ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5">لا توجد مصروفات مسجلة للموردين بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection
