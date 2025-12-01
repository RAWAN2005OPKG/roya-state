@extends('layouts.container')
@section('title', 'إدارة الحسابات البنكية')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة الحسابات البنكية</h1>
        <button class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#addBankAccountModal">
            <span class="svg-icon svg-icon-md"><i class="fas fa-plus"></i></span>إضافة حساب بنكي
        </button>
    </div>

    <!-- Totals Cards -->
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label"><i class="fas fa-university text-primary"></i></span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="font-weight-bold text-dark-75 font-size-lg mb-1">إجمالي عدد الحسابات</span>
                            <span class="text-muted font-weight-bold">{{ $totalAccounts }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card card-custom gutter-b">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40 symbol-light-success mr-5">
                            <span class="symbol-label"><i class="fas fa-check-circle text-success"></i></span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1">
                            <span class="font-weight-bold text-dark-75 font-size-lg mb-1">الحسابات النشطة</span>
                            <span class="text-muted font-weight-bold">{{ $activeAccounts }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Accounts Table -->
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">قائمة الحسابات البنكية</h3>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-uppercase">
                            <th>اسم البنك</th>
                            <th>اسم الحساب</th>
                            <th>رقم الحساب</th>
                            <th>الرصيد الحالي</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- هنا تم تصحيح الخطأ --}}
                        @forelse($bankAccounts as $account)
                        <tr>
                            <td>{{ $account->bank_name }}</td>
                            <td>{{ $account->account_name }}</td>
                            <td>{{ $account->account_number }}</td>
                            <td class="font-weight-bold">{{ number_format($account->balance, 2) }}</td>
                            <td>
                                @if($account->is_active)
                                    <span class="label label-lg font-weight-bold label-light-success label-inline">نشط</span>
                                @else
                                    <span class="label label-lg font-weight-bold label-light-danger label-inline">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.bank-accounts.show', $account->id) }}" class="btn btn-sm btn-clean btn-icon" title="كشف حساب"><i class="la la-eye"></i></a>
                                <button class="btn btn-sm btn-clean btn-icon" data-toggle="modal" data-target="#editBankAccountModal-{{ $account->id }}" title="تعديل"><i class="la la-edit"></i></button>
                                <form action="{{ route('dashboard.bank-accounts.destroy', $account->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="la la-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center p-5 text-muted">لا توجد حسابات بنكية لعرضها.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">{{ $bankAccounts->links() }}</div>
        </div>
    </div>
</div>

{{-- =================================================== --}}
{{-- Modals Section --}}
{{-- =================================================== --}}

<!-- Add Bank Account Modal -->
<div class="modal fade" id="addBankAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.bank-accounts.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">إضافة حساب بنكي جديد</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>اسم البنك</label>
                        <select name="bank_name" class="form-control" required>
                            <option value="">-- اختر من دليل البنوك --</option>
                            @foreach($banks as $bank_name)
                                <option value="{{ $bank_name }}">{{ $bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>اسم الحساب</label>
                        <input type="text" name="account_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الحساب</label>
                        <input type="text" name="account_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>الرصيد الافتتاحي</label>
                        <input type="number" name="initial_balance" class="form-control" value="0" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Bank Account Modals -->
@foreach ($bankAccounts as $account)
<div class="modal fade" id="editBankAccountModal-{{ $account->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('dashboard.bank-accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header"><h5 class="modal-title">تعديل الحساب: {{ $account->account_name }}</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>اسم البنك</label>
                        <select name="bank_name" class="form-control" required>
                            <option value="">-- اختر من دليل البنوك --</option>
                            @foreach($banks as $bank_name)
                                <option value="{{ $bank_name }}" @selected($account->bank_name == $bank_name)>{{ $bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>اسم الحساب</label>
                        <input type="text" name="account_name" class="form-control" value="{{ $account->account_name }}" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الحساب</label>
                        <input type="text" name="account_number" class="form-control" value="{{ $account->account_number }}" required>
                    </div>
                    <div class="form-group">
                        <label>الحالة</label>
                        <select name="is_active" class="form-control" required>
                            <option value="1" @selected($account->is_active)>نشط</option>
                            <option value="0" @selected(!$account->is_active)>غير نشط</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
