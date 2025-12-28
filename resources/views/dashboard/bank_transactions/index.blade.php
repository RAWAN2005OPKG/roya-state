
@extends('layouts.container')
@section('title', 'سجل الحركات البنكية')

@section('content')
<div class="card card-custom">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">سجل الحركات البنكية</h3>
        <a href="{{ route('dashboard.bank-transactions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة حركة جديدة
        </a>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-uppercase">
                        <th>التاريخ</th>
                        <th>الحساب البنكي</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الوصف / التفاصيل</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        {{-- تحديد لون الصف بناءً على نوع الحركة --}}
                        @php
                            $rowClass = '';
                            if (in_array($transaction->type, ['deposit', 'transfer_in'])) {
                                $rowClass = 'table-light-success';
                            } elseif (in_array($transaction->type, ['withdrawal', 'transfer_out'])) {
                                $rowClass = 'table-light-danger';
                            }
                        @endphp
                        <tr class="{{ $rowClass }}">
                            {{-- عرض التاريخ --}}
                            <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>

                            {{-- عرض اسم الحساب والبنك (مع التحقق من وجودها) --}}
                            <td>
                                {{ $transaction->bankAccount->account_name ?? 'حساب محذوف' }}
                                <small class="d-block text-muted">{{ $transaction->bankAccount->bank->name ?? '' }}</small>
                            </td>

                            {{-- عرض نوع الحركة بشكل مقروء --}}
                            <td>
                                @if($transaction->type == 'deposit') <span class="badge badge-success">إيداع</span>
                                @elseif($transaction->type == 'withdrawal') <span class="badge badge-danger">سحب</span>
                                @elseif($transaction->type == 'transfer_in') <span class="badge badge-primary">حوالة واردة</span>
                                @elseif($transaction->type == 'transfer_out') <span class="badge badge-warning">حوالة صادرة</span>
                                @else <span class="badge badge-secondary">{{ $transaction->type }}</span>
                                @endif
                            </td>

                            {{-- عرض المبلغ مع الإشارة (موجب أو سالب) --}}
                            <td class="font-weight-bold">
                                @if(in_array($transaction->type, ['deposit', 'transfer_in'])) +
                                @else -
                                @endif
                                {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                            </td>

                            {{-- عرض التفاصيل --}}
                            <td>{{ $transaction->details ?? '-' }}</td>

                            {{-- أزرار التحكم --}}
                            <td>
                                <a href="{{ route('dashboard.bank-transactions.edit', $transaction->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="la la-edit"></i></a>
                                {{-- يمكنك إضافة زر حذف هنا لاحقًا --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-5 text-muted">
                                <h4>لا توجد حركات لعرضها.</h4>
                                <p>يمكنك البدء بإضافة حركة جديدة.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- روابط التنقل بين الصفحات --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
