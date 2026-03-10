@extends('layouts.container')
@section('title', 'إجمالي كافة الحسابات')

@section('styles')
<style>
    .account-card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: none;
        margin-bottom: 20px;
    }
    .account-card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #ebedf2;
        padding: 15px 20px;
        font-weight: bold;
        font-size: 1.1rem;
        border-radius: 10px 10px 0 0;
    }
    .total-balance-box {
        background: linear-gradient(135deg, #1bc5bd 0%, #0da69e 100%);
        color: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(27, 197, 189, 0.3);
        margin-bottom: 30px;
    }
    .total-balance-box h2 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        font-weight: 400;
    }
    .total-balance-box .amount {
        font-size: 3rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- العنوان -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">إجمالي كافة الحسابات</h1>
        <span class="text-muted">موقف السيولة وأرصدة البنوك والشيكات</span>
    </div>

    <!-- صندوق الإجمالي العام -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="total-balance-box">
                <h2>إجمالي السيولة المتوفرة (رأس المال العامل)</h2>
                <div class="amount">{{ number_format($totalOverallBalance, 2) }} <small class="font-size-h4">شيكل</small></div>
                <div class="mt-3 opacity-70">يحتوي على: النقدية بالخزينة + حسابات البنوك + شيكات برسم التحصيل</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الخزينة النقدية -->
        <div class="col-xl-6">
            <div class="card account-card h-100">
                <div class="account-card-header d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-wallet text-primary mr-2"></i> المحفظة النقدية (الكاش)</div>
                    <span class="badge badge-primary badge-pill" style="font-size: 1rem;">{{ number_format($totalCashBalance, 2) }} شيكل</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>اسم الخزينة</th>
                                    <th>الوصف</th>
                                    <th class="text-right">تاريخ الانشاء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cashSafes as $safe)
                                <tr>
                                    <td class="font-weight-bold">{{ $safe->name }}</td>
                                    <td>{{ $safe->description }}</td>
                                    <td class="text-right">{{ $safe->created_at->format('Y-m-d') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">لا يوجد خزائن مسجلة</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <a href="{{ route('dashboard.cash-safes.index') }}" class="btn btn-sm btn-outline-primary">إدارة الخزائن <i class="fas fa-arrow-left fa-sm ml-1"></i></a>
                </div>
            </div>
        </div>

        <!-- الحسابات البنكية -->
        <div class="col-xl-6">
            <div class="card account-card h-100">
                <div class="account-card-header d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-university text-success mr-2"></i> الحسابات البنكية</div>
                    <span class="badge badge-success badge-pill" style="font-size: 1rem;">{{ number_format($totalBankBalance, 2) }} شيكل</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>رقم الحساب</th>
                                    <th>البنك</th>
                                    <th class="text-right">الرصيد الفعلي</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bankAccounts as $account)
                                <tr>
                                    <td class="font-weight-bold">{{ $account->account_number }}</td>
                                    <td>{{ $account->bank->name ?? 'غير محدد' }}</td>
                                    <td class="text-right font-weight-bold {{ $account->balance < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($account->balance, 2) }} <small>{{ $account->currency }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-4 text-muted">لا يوجد حسابات بنكية</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <a href="{{ route('dashboard.bank-accounts.index') }}" class="btn btn-sm btn-outline-success">إدارة البنوك <i class="fas fa-arrow-left fa-sm ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- الشيكات وحالتها -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card account-card">
                <div class="account-card-header d-flex justify-content-between align-items-center">
                    <div><i class="fas fa-money-check-alt text-warning mr-2"></i> سجل الشيكات الأخير</div>
                    <div>
                        <!-- فلتر بسيط للشيكات -->
                        <form action="{{ route('dashboard.financial-accounts.index') }}" method="GET" class="form-inline">
                            <select name="check_status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="">كل الحالات</option>
                                <option value="in_wallet" {{ request('check_status') == 'in_wallet' ? 'selected' : '' }}>في المحفظة</option>
                                <option value="under_collection" {{ request('check_status') == 'under_collection' ? 'selected' : '' }}>برسم التحصيل</option>
                                <option value="cleared" {{ request('check_status') == 'cleared' ? 'selected' : '' }}>مُحصّل</option>
                                <option value="bounced" {{ request('check_status') == 'bounced' ? 'selected' : '' }}>مرتجع</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>رقم الشيك</th>
                                    <th>النوع</th>
                                    <th>المستفيد / الساحب</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>القيمة</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($checks as $check)
                                <tr>
                                    <td class="font-weight-bold">{{ $check->check_number }}</td>
                                    <td>
                                        @if($check->type == 'receivable')
                                            <span class="badge badge-light-success">شيك قبض</span>
                                        @else
                                            <span class="badge badge-light-danger">شيك صرف</span>
                                        @endif
                                    </td>
                                    <td>{{ $check->holder_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($check->due_date)->format('Y-m-d') }}</td>
                                    <td class="font-weight-bold">{{ number_format($check->amount_ils, 2) }}</td>
                                    <td>
                                        @php
                                            $badgeClasses = [
                                                'in_wallet' => 'badge-info',
                                                'under_collection' => 'badge-warning',
                                                'cleared' => 'badge-success',
                                                'bounced' => 'badge-danger',
                                            ];
                                            $statusNames = [
                                                'in_wallet' => 'في المحفظة',
                                                'under_collection' => 'برسم التحصيل',
                                                'cleared' => 'مُحصّل',
                                                'bounced' => 'بالغ الدفع/مرتجع',
                                            ];
                                            $class = $badgeClasses[$check->status] ?? 'badge-secondary';
                                            $name = $statusNames[$check->status] ?? $check->status;
                                        @endphp
                                        <span class="badge {{ $class }}">{{ $name }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">لم يتم العثور على شيكات مطابقة</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <div>
                        {{ $checks->appends(request()->query())->links() }}
                    </div>
                    <a href="{{ route('dashboard.checks.index') }}" class="btn btn-sm btn-outline-warning">إدارة الشيكات الكاملة <i class="fas fa-arrow-left fa-sm ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
