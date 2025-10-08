@extends('layouts.container')
@section('title', 'العقود النشطة')

@section('styles')
     <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #3730a3;
            --secondary-color: #06b6d4;
            --dark-bg-1: #ffffff;
            --dark-bg-2: #ffffff;
            --dark-bg-3: #ffffff;
            --text-color: #000000;
            --text-muted: #131313;
            --border-color: rgba(148, 163, 184, 0.2);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }
        body {
            background: #f8f9fa;
            color: var(--text-color);
            font-family: 'Cairo', sans-serif;
            direction: rtl;
        }
        .main-content {
            width: 100%;
            max-width: 1600px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-header h1 {
            font-size: 2.8rem;
            font-weight: 700;
        }
        .page-header p {
            font-size: 1.2rem;
            color: var(--text-muted);
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .kpi-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }
        .kpi-card .label {
            color: var(--text-muted);
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        .kpi-card .value {
            font-size: 2.2rem;
            font-weight: 700;
        }
        .kpi-card .value.currency { color: var(--success-color); }
        .kpi-card .value.remaining { color: var(--danger-color); }
        .table-wrapper { overflow-x: auto; }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 18px 15px;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        .data-table th {
            font-size: 1.05rem;
            color: var(--text-muted);
            font-weight: 600;
        }
        .tag {
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .tag-cash { background: rgba(16, 185, 129, 0.15); color: var(--success-color); }
        .tag-installment { background: rgba(245, 158, 11, 0.15); color: var(--warning-color); }
        .tag-bank { background: rgba(59, 130, 246, 0.15); color: var(--info-color); }
        .btn-action {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
        }
        .btn-action:hover { color: var(--primary-color); }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 20px; }
        .search-form { display: flex; gap: 10px; }
        .search-input { padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 12px; min-width: 300px; }
        .btn { padding: 12px 24px; border: none; border-radius: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 10px; font-weight: 600; text-decoration: none; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-light { background: #f1f2f6; color: #333; }
        .sortable-link { color: inherit; text-decoration: none; }
        .pagination-links { margin-top: 25px; }
        @media print {
            body * { visibility: hidden; }
            .printable-area, .printable-area * { visibility: visible; }
            .printable-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-file-signature"></i> العقود النشطة</h1>
        <p>نظرة شاملة على جميع الاتفاقيات المالية الجارية مع العملاء</p>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">إجمالي عدد العقود</div><div class="value">{{ $totalContracts }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي قيمة العقود</div><div class="value currency">{{ number_format($totalValue, 2) }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي المدفوع</div><div class="value currency">{{ number_format($totalPaid, 2) }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي المتبقي</div><div class="value remaining">{{ number_format($totalRemaining, 2) }}</div></div>
    </div>

    <div class="card card-custom gutter-b">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title"><h3 class="card-label">قائمة العقود</h3></div>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.contracts.create') }}" class="btn btn-primary font-weight-bolder">إضافة عقد</a>
                <a href="{{ route('dashboard.contracts.trash.index') }}" class="btn btn-light font-weight-bolder ml-2">سلة المحذوفات</a>

                <div class="dropdown dropdown-inline mr-2 ml-2">
                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown">تصدير</button>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-item"><a href="#" onclick="printTable()" class="navi-link"><i class="la la-print"></i> طباعة</a></li>
                            <li class="navi-item"><a href="{{ route('dashboard.contracts.export.excel') }}" class="navi-link"><i class="la la-file-excel-o"></i> Excel</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-controls no-print">
                <form action="{{ route('dashboard.contracts.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="ابحث بالاسم, رقم العقد, الجوال..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </form>
            </div>

            <div class="table-wrapper printable-area">
                <table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
                    <thead>
                        <tr>
                            <th><a href="{{ route('dashboard.contracts.index', ['sort_by' => 'contract_id', 'sort_order' => 'asc']) }}" class="sortable-link">رقم العقد</a></th>
                            <th><a href="{{ route('dashboard.contracts.index', ['sort_by' => 'client_name', 'sort_order' => 'asc']) }}" class="sortable-link">اسم العميل</a></th>
                            <th>قيمة الاستثمار</th>
                            <th>تاريخ التوقيع</th>
                            <th class="no-print">تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->contract_id }}</td>
                                <td><strong>{{ $contract->client_name }}</strong></td>
                                <td>{{ number_format($contract->investment_amount, 2) }}</td>
                                <td>{{ $contract->signing_date->format('Y-m-d') }}</td>
                                <td class="no-print">
                                <a href="{{ route('dashboard.contracts.show', $contract->id) }}" class="btn-action" title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                                   </a>
                                    <a href="{{ route('dashboard.contracts.show', $contract->id) }}" class="btn-action" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('dashboard.contracts.edit', $contract->id) }}" class="btn-action" title="تعديل"><i class="fas fa-edit"></i></a>
                                  <a href="{{ route('dashboard.contracts.export.pdf', $contract->id) }}" class="btn btn-sm btn-clean btn-icon" title="تصدير PDF"><i class="fas fa-file-pdf text-danger"></i></a>

                                    <form action="{{ route('dashboard.contracts.destroy', $contract->id ) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-action delete" title="حذف" onclick="confirmDelete({{ $contract->id }})">
                                      <i class="fas fa-trash"></i>
                                </button>
                               </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5">لا توجد عقود تطابق بحثك.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-links no-print mt-4">
                {{ $contracts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script>
    function printTable() {
        window.print();
    }
     function confirmDelete(contractId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم نقل هذا العقد إلى سلة المحذوفات!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، انقله!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + contractId).submit();
            }
        })
    }
</script>
@endsection

