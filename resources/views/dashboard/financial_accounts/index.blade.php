@extends('layouts.container')
@section('title', 'المركز المالي')

@section('content')

<!-- Totals Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-success mr-5">
                        <span class="symbol-label"><i class="fas fa-cash-register text-success"></i></span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="font-weight-bold text-dark-75 font-size-lg mb-1">أرصدة الخزائن (الكاش)</span>
                        <span class="text-muted font-weight-bold">{{ number_format($totalCashBalance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                        <span class="symbol-label"><i class="fas fa-university text-primary"></i></span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="font-weight-bold text-dark-75 font-size-lg mb-1">أرصدة البنوك</span>
                        <span class="text-muted font-weight-bold">{{ number_format($totalBankBalance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40 symbol-light-info mr-5">
                        <span class="symbol-label"><i class="fas fa-wallet text-info"></i></span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="font-weight-bold text-dark-75 font-size-lg mb-1">إجمالي الأرصدة المتاحة</span>
                        <span class="text-muted font-weight-bold">{{ number_format($totalOverallBalance, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Card with Tabs -->
<div class="card card-custom">
    <div class="card-header card-header-tabs-line">
        <div class="card-toolbar">
            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab_cash_safes">
                        <span class="nav-icon"><i class="fas fa-cash-register"></i></span>
                        <span class="nav-text">الخزائن النقدية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_bank_accounts">
                        <span class="nav-icon"><i class="fas fa-university"></i></span>
                        <span class="nav-text">الحسابات البنكية</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab_checks">
                        <span class="nav-icon"><i class="fas fa-money-check-alt"></i></span>
                        <span class="nav-text">حافظة الشيكات</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-title">
            {{-- يمكنك إضافة أزرار هنا لاحقاً مثل "إضافة حركة جديدة" --}}
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content">

            <!-- 1. تبويب الخزائن النقدية (الكاش) -->
            <div class="tab-pane fade show active" id="tab_cash_safes" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-uppercase">
                                <th class="pl-0">اسم الخزينة</th>
                                <th>الرصيد الحالي</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cashSafes as $safe)
                            <tr>
                                <td class="pl-0">{{ $safe->name }}</td>
                                <td class="font-weight-bold">{{ number_format($safe->balance, 2) }}</td>
                                <td>
                                    @if($safe->is_active)
                                        <span class="label label-lg font-weight-bold label-light-success label-inline">نشطة</span>
                                    @else
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline">غير نشطة</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center p-5 text-muted">لا توجد خزائن لعرضها.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. تبويب الحسابات البنكية -->
            <div class="tab-pane fade" id="tab_bank_accounts" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-uppercase">
                                <th class="pl-0">اسم البنك</th>
                                <th>اسم الحساب</th>
                                <th>رقم الحساب</th>
                                <th>الرصيد</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bankAccounts as $account)
                            <tr>
                                <td class="pl-0">{{ $account->bank_name }}</td>
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
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center p-5 text-muted">لا توجد حسابات بنكية لعرضها.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. تبويب الشيكات -->
            <div class="tab-pane fade" id="tab_checks" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-uppercase">
                                <th>رقم الشيك</th>
                                <th>النوع</th>
                                <th>صاحب الشيك</th>
                                <th>المبلغ</th>
                                <th>تاريخ الاستحقاق</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checks as $check)
                            <tr>
                                <td>{{ $check->check_number }}</td>
                                <td>
                                    @if($check->type == 'incoming')<span class="label label-light-success label-inline">وارد</span>
                                    @else<span class="label label-light-danger label-inline">صادر</span>@endif
                                </td>
                                <td>{{ $check->holder_name }}</td>
                                <td class="font-weight-bold">{{ number_format($check->amount, 2) }} {{ $check->currency }}</td>
                                <td>{{ $check->due_date->format('Y-m-d') }}</td>
                                <td>
                                    @if($check->status == 'cashed') <span class="label label-light-info label-inline">تم الصرف</span>
                                    @elseif($check->status == 'returned') <span class="label label-light-warning label-inline">مرتجع</span>
                                    @else <span class="label label-light-dark label-inline">في الحافظة</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center p-5 text-muted">لا توجد شيكات لعرضها.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
