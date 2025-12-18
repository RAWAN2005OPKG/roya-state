@extends('layouts.container')
@section('title', 'الحركات البنكية')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">سجل الحركات البنكية
                <span class="d-block text-muted pt-2 font-size-sm">عرض أحدث الحركات المسجلة</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.bank-transactions.trash') }}" class="btn btn-danger font-weight-bolder mr-2">
                <i class="fas fa-trash"></i> سلة المحذوفات
            </a>
            <a href="{{ route('dashboard.bank-transactions.create') }}" class="btn btn-primary font-weight-bolder">
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
                        <th>الحساب</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->date }}</td>
                        <td>{{ $transaction->bankAccount->account_name ?? '-' }} ({{ $transaction->bankAccount->bank->name ?? 'N/A' }})</td>
                        <td><span class="label label-inline {{ $transaction->type == 'deposit' ? 'label-light-success' : 'label-light-danger' }}">{{ $transaction->type == 'deposit' ? 'إيداع' : 'سحب' }}</span></td>
                        <td class="font-weight-bold">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                        <td>{{ Str::limit($transaction->notes, 50) }}</td>
                        <td nowrap="nowrap">
                            <a href="{{ route('dashboard.bank-transactions.edit', $transaction->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="la la-edit"></i></a>
                            <form action="{{ route('dashboard.bank-transactions.destroy', $transaction->id) }}" method="POST" style="display:inline" onsubmit="return confirm('هل أنت متأكد من نقل هذه الحركة إلى سلة المحذوفات؟ سيتم عكس قيمتها من رصيد الحساب.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="la la-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center p-5 text-muted">لا توجد حركات بنكية لعرضها.</td></tr>
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
