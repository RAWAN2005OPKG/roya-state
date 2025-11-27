@extends('layouts.container')
@section('title', 'الحسابات البنكية')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">الحسابات البنكية</h1>
            <p class="mb-0 text-muted">إدارة الحسابات البنكية وأرصدتها.</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('dashboard.bank-accounts.trash.index') }}" class="btn btn-outline-secondary mr-2">
                <i class="fas fa-trash-alt fa-sm mr-1"></i> سلة المحذوفات
            </a>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addAccountModal">
                <i class="fas fa-plus fa-sm mr-1"></i> إضافة حساب بنكي
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الحسابات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAccounts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الحسابات النشطة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeAccounts }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي الأرصدة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($bankAccounts->sum('balance'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card: Filters and Table -->
    <div class="card card-custom shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الحسابات</h5>
            <form action="{{ route('dashboard.bank-accounts.index') }}" method="GET" class="form-inline">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الرقم..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>اسم البنك</th>
                            <th>اسم الحساب</th>
                            <th>رقم الحساب</th>
                            <th>الرصيد</th>
                            <th>الحالة</th>
                            <th class="text-center">تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bankAccounts as $account)
                            <tr>
                                <td>{{ $account->bank_name }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>{{ $account->account_number }}</td>
                                <td class="font-weight-bold">{{ number_format($account->balance, 2) }}</td>
                                <td>
                                    @if($account->is_active)
                                        <span class="badge badge-pill badge-success">نشط</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-icon btn-light-primary" data-toggle="modal" data-target="#editAccountModal-{{ $account->id }}" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('dashboard.bank-accounts.destroy', $account->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الحساب؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Edit Account Modal -->
                            @include('dashboard.bank_accounts.partials.edit_modal')
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-exclamation-circle fa-3x mb-3"></i>
                                    <h4>لا توجد حسابات بنكية للعرض.</h4>
                                    <p>يمكنك البدء بإضافة حساب جديد من الزر أعلاه.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bankAccounts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $bankAccounts->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Account Modal -->
@include('dashboard.bank_accounts.partials.add_modal')

@endsection
