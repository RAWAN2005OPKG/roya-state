@extends('layouts.container')
@section('title', 'سلة محذوفات الحركات البنكية')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">سلة محذوفات الحركات البنكية</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.bank-transactions.index') }}" class="btn btn-primary font-weight-bolder">
                <i class="fas fa-arrow-right"></i> العودة للسجل الرئيسي
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>تاريخ الحذف</th>
                        <th>التاريخ الأصلي</th>
                        <th>الحساب</th>
                        <th>المبلغ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->deleted_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $transaction->date }}</td>
                            <td>{{ $transaction->bankAccount->account_name ?? 'حساب محذوف' }}</td>
                            <td class="font-weight-bold">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</td>
                            <td nowrap="nowrap">
                                <form action="{{ route('dashboard.bank-transactions.restore', $transaction->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="استرجاع"><i class="fas fa-undo"></i></button>
                                </form>
                                <form action="{{ route('dashboard.bank-transactions.force-delete', $transaction->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف نهائي" onclick="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.')"><i class="fas fa-trash-alt text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center p-5 text-muted">سلة المحذوفات فارغة.</td></tr>
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
