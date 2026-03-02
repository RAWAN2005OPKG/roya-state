@extends('layouts.container')
@section('title', 'إدارة الحركات البنكية')

@section('content')
{{-- عرض أرصدة الحسابات --}}
<div class="row">
    @if(isset($bankAccounts))
        @foreach($bankAccounts as $account)
        <div class="col-md-4 mb-4">
            <div class="card card-custom h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $account->account_name }}</h5>
                    <p class="card-text text-muted">{{ $account->bank->name ?? 'N/A' }} - {{ $account->account_number }}</p>
                    <h3 class="font-weight-bolder {{ ($account->balance ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($account->balance ?? 0, 2) }} <span class="h6">{{ $account->currency }}</span>
                    </h3>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>

{{-- جدول الحركات --}}
<div class="card card-custom mt-5">
    <div class="card-header">
        <h3 class="card-title">سجل الحركات البنكية</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.bank-transactions.trash') }}" class="btn btn-danger mr-2"><i class="la la-trash"></i> سلة المحذوفات</a>
            <a href="{{ route('dashboard.bank-transactions.create') }}" class="btn btn-primary"><i class="la la-plus"></i> إضافة حركة جديدة</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

        <form method="GET" action="{{ route('dashboard.bank-transactions.index') }}" class="mb-5">
            <div class="row">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="ابحث..." value="{{ $request->search ?? '' }}">
                        <div class="input-group-append"><button class="btn btn-outline-primary" type="submit">بحث</button></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="10" @selected(($request->per_page ?? 10) == 10)>إظهار 10</option>
                        <option value="20" @selected(($request->per_page ?? 10) == 20)>إظهار 20</option>
                        <option value="30" @selected(($request->per_page ?? 10) == 30)>إظهار 30</option>
                        <option value="50" @selected(($request->per_page ?? 10) == 50)>إظهار 50</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>من الحساب</th>
                        <th>إلى الحساب</th>
                        <th>النوع</th>
                        <th class="text-right">المبلغ</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('d-m-Y') }}</td>
                        <td>{{ $transaction->fromAccount->account_name ?? '-' }}</td>
                        <td>{{ $transaction->toAccount->account_name ?? '-' }}</td>
                        <td>
                            @if($transaction->type == 'deposit') <span class="badge badge-success">إيداع</span>
                            @elseif($transaction->type == 'withdrawal') <span class="badge badge-danger">سحب</span>
                            @else <span class="badge badge-info">تحويل</span> @endif
                        </td>
                        <td class="text-right font-weight-bold">
                            <span class="{{ $transaction->type == 'deposit' ? 'text-success' : ($transaction->type == 'withdrawal' ? 'text-danger' : 'text-info') }}">
                                {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.bank-transactions.show', $transaction->id) }}" class="btn btn-sm btn-icon btn-light-info" title="عرض"><i class="la la-eye"></i></a>
                            <a href="{{ route('dashboard.bank-transactions.edit', $transaction->id) }}" class="btn btn-sm btn-icon btn-light-warning" title="تعديل"><i class="la la-edit"></i></a>
                            <form action="{{ route('dashboard.bank-transactions.destroy', $transaction->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');" style="display: inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف"><i class="la la-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-5">لا توجد حركات بنكية لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($transactions))
        <div class="d-flex justify-content-center mt-5">
            {{ $transactions->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
