@extends('layouts.container')
@section('title', 'سلة محذوفات سجل خالد ومحمد')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">سلة المحذوفات: سجل خالد ومحمد
                <span class="d-block text-muted pt-2 font-size-sm">عرض الحركات التي تم حذفها</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.khaleed-mohamed.index') }}" class="btn btn-primary font-weight-bolder">
                <i class="fas fa-arrow-right"></i> العودة للسجل الرئيسي
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
                        <th>تاريخ الحذف</th>
                        <th>التاريخ الأصلي</th>
                        <th>من (دفع)</th>
                        <th>صرف لمين</th>
                        <th>المبلغ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->deleted_at->format('Y-m-d H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                            <td>{{ $transaction->paid_by }}</td>
                            <td>{{ $transaction->paid_to }}</td>
                            <td>
                                @if($transaction->amount_shekel)
                                    {{ number_format($transaction->amount_shekel, 2) }} شيكل
                                @elseif($transaction->amount_dollar)
                                    {{ number_format($transaction->amount_dollar, 2) }} دولار
                                @endif
                            </td>
                            <td nowrap="nowrap">
                                {{-- فورم الاسترجاع --}}
                                <form action="{{ route('dashboard.khaleed-mohamed.restore', $transaction->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="استرجاع"><i class="fas fa-undo"></i></button>
                                </form>

                                {{-- فورم الحذف النهائي --}}
                                <form action="{{ route('dashboard.khaleed-mohamed.force-delete', $transaction->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف نهائي" onclick="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.')"><i class="fas fa-trash-alt text-danger"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center p-5 text-muted">سلة المحذوفات فارغة.</td></tr>
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
