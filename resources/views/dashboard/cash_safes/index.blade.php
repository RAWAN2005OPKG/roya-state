@extends('layouts.metronic')
@section('title', 'إدارة الحسابات البنكية')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">الحسابات البنكية
                <span class="d-block text-muted pt-2 font-size-sm">عرض وإدارة جميع الحسابات البنكية</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <button class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#addBankAccountModal">
                <span class="svg-icon svg-icon-md"><i class="fas fa-plus"></i></span>إضافة حساب بنكي جديد
            </button>
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
                        <th>الرصيد الافتتاحي</th>
                        <th>الرصيد الحالي</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bankAccounts as $account)
                    <tr>
                        <td>{{ $account->bank_name }}</td>
                        <td>{{ $account->account_name }}</td>
                        <td>{{ $account->account_number }}</td>
                        <td>{{ number_format($account->initial_balance, 2) }}</td>
                        <td>{{ number_format($account->balance, 2) }}</td>
                        <td>
                            @if($account->is_active)
                                <span class="label label-lg font-weight-bold label-light-success label-inline">نشط</span>
                            @else
                                <span class="label label-lg font-weight-bold label-light-danger label-inline">غير نشط</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-clean btn-icon" data-toggle="modal" data-target="#editBankAccountModal-{{ $account->id }}" title="تعديل"><i class="la la-edit"></i></button>
                            <form action="{{ route('dashboard.bank-accounts.destroy', $account->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الحساب البنكي؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="la la-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center p-5 text-muted">لا توجد حسابات بنكية لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $bankAccounts->links() }}</div>
    </div>
</div>

{{-- Add Bank Account Modal --}}
<div class="modal fade" id="addBankAccountModal" tabindex="-1" role="dialog" aria-labelledby="addBankAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBankAccountModalLabel">إضافة حساب بنكي جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.bank-accounts.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bank_name">اسم البنك</label>
                        <input type="text" id="bank_name" name="bank_name" class="form-control" required value="{{ old('bank_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="account_name">اسم الحساب</label>
                        <input type="text" id="account_name" name="account_name" class="form-control" required value="{{ old('account_name') }}">
                    </div>
                    <div class="form-group">
                        <label for="account_number">رقم الحساب</label>
                        <input type="text" id="account_number" name="account_number" class="form-control" required value="{{ old('account_number') }}">
                    </div>
                    <div class="form-group">
                        <label for="initial_balance">الرصيد الافتتاحي</label>
                        <input type="number" id="initial_balance" name="initial_balance" class="form-control" value="{{ old('initial_balance', 0) }}" step="0.01" required>
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

{{-- Edit Bank Account Modals (Inside the loop) --}}
@foreach ($bankAccounts as $account)
<div class="modal fade" id="editBankAccountModal-{{ $account->id }}" tabindex="-1" role="dialog" aria-labelledby="editBankAccountModalLabel-{{ $account->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBankAccountModalLabel-{{ $account->id }}">تعديل الحساب: {{ $account->account_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.bank-accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bank_name-{{ $account->id }}">اسم البنك</label>
                        <input type="text" id="bank_name-{{ $account->id }}" name="bank_name" class="form-control" value="{{ old('bank_name', $account->bank_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="account_name-{{ $account->id }}">اسم الحساب</label>
                        <input type="text" id="account_name-{{ $account->id }}" name="account_name" class="form-control" value="{{ old('account_name', $account->account_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="account_number-{{ $account->id }}">رقم الحساب</label>
                        <input type="text" id="account_number-{{ $account->id }}" name="account_number" class="form-control" value="{{ old('account_number', $account->account_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="is_active-{{ $account->id }}">الحالة</label>
                        <select id="is_active-{{ $account->id }}" name="is_active" class="form-control" required>
                            <option value="1" @selected(old('is_active', $account->is_active) == '1')>نشط</option>
                            <option value="0" @selected(old('is_active', $account->is_active) == '0')>غير نشط</option>
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
