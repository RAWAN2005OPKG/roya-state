@extends('layouts.container')
@section('title', 'إدارة العقود')

@section('styles')
<style>
    :root {
        --primary-color: #4f46e5; --primary-hover: #3730a3; --light-bg: #f8fafc;
        --white-bg: #ffffff; --text-color: #1f2937; --text-muted: #6b7280;
        --border-color: #e5e7eb; --success-color: #10b981; --danger-color: #ef4444;
        --warning-color: #f59e0b; --info-color: #3b82f6;
        --shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06);
    }
    body { background-color: var(--light-bg); color: var(--text-color); direction: rtl; font-family: 'Cairo', sans-serif; }
    .main-content { max-width: 1600px; margin: 40px auto; padding: 0 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-header h1 { font-size: 2.5rem; font-weight: 700; }
    .header-actions { display: flex; gap: 15px; }
    .btn { padding: 12px 25px; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary { background-color: var(--primary-color); color: #ffffff; }
    .btn-danger { background-color: var(--danger-color); color: #ffffff; }
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { background-color: var(--white-bg); padding: 25px; border-radius: 12px; box-shadow: var(--shadow); }
    .kpi-card .label { color: var(--text-muted); margin-bottom: 10px; }
    .kpi-card .value { font-size: 2rem; font-weight: 700; }
    .table-container { background-color: var(--white-bg); padding: 30px; border-radius: 12px; box-shadow: var(--shadow); }
    .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .search-form input { padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 8px; min-width: 300px; }
    .table-wrapper { overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th, .data-table td { padding: 15px; text-align: right; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
    .data-table th { font-weight: 600; color: var(--text-muted); }
    .badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
    .badge-customer { background-color: #e0e7ff; color: #3730a3; }
    .badge-investor { background-color: #d1fae5; color: #065f46; }
    .badge-employee { background-color: #fef3c7; color: #92400e; }
    .action-buttons a, .action-buttons button { color: var(--text-muted); background: none; border: none; padding: 5px; font-size: 1.1rem; cursor: pointer; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-file-signature"></i> إدارة العقود</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.contracts.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة عقد جديد</a>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">إجمالي العقود</div><div class="value">{{ $totalContracts }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي قيمة العقود</div><div class="value">{{ number_format($totalValue, 2) }} <small>ILS</small></div></div>
    </div>

    <div class="table-container">
        <div class="table-controls">
            <form action="{{ route('dashboard.contracts.index') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="ابحث برقم العقد, اسم المالك, المشروع..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم العقد</th>
                        <th>صاحب العقد</th>
                        <th>نوع العقد</th>
                        <th>المشروع</th>
                        <th>قيمة العقد</th>
                        <th>تاريخ التوقيع</th>
                        <th>الحالة</th>
                        <th class="no-print">تحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contracts as $contract)
                        <tr>
                            <td><strong>{{ $contract->contract_id }}</strong></td>
                            <td>{{ $contract->contractable->name ?? 'غير محدد' }}</td>
                            <td>
                                @if($contract->contractable_type == \App\Models\Customer::class)
                                    <span class="badge badge-customer">عقد عميل</span>
                                @elseif($contract->contractable_type == \App\Models\Investor::class)
                                    <span class="badge badge-investor">عقد استثمار</span>
                                @else
                                    <span class="badge badge-employee">عقد مقاول</span>
                                @endif
                            </td>
                            <td>{{ $contract->project->project_name ?? '-' }}</td>
                            <td>{{ number_format($contract->investment_amount, 2) }} {{ $contract->currency }}</td>
                            <td>{{ $contract->signing_date->format('Y-m-d') }}</td>
                            <td>{{ $contract->status }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('dashboard.contracts.show', $contract->id) }}" title="عرض"><i class="fas fa-eye"></i></a>
                               <a href="{{ route('dashboard.contracts.edit', $contract->id) }}" title="تعديل"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center" style="padding: 2rem;">لا توجد عقود لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $contracts->appends(request()->query())->links() }}</div>
    </div>
</main>
@endsection
