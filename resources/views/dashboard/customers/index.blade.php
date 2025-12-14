@extends('layouts.container')
@section('title', 'إدارة العملاء')

@push('styles')

<style>
    /* تصميم كروت الإحصائيات العلوية (KPI Cards) */
    .kpi-card {
        background-color: #ffffff;
        border-radius: 0.75rem;
        padding: 25px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-left: 4px solid #4e73df; /* لون الشريط الجانبي */
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.1);
    }

    .kpi-card .label {
        color: #858796;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .kpi-card .value {
        font-size: 2rem;
        font-weight: 700;
        color: #3a3b45;
    }

    /* تصميم رأس الصفحة */
    .page-header {
        margin-bottom: 2.5rem;
    }

    .page-header h1 {
        font-weight: 700;
        color: #3a3b45;
    }

    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-form {
        display: flex;
        gap: 0.5rem;
    }

    .header-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* تصميم خاص لاسم العميل في الجدول */
    .customer-name {
        display: flex;
        flex-direction: column;
    }

    .customer-name strong {
        font-weight: 600;
        color: #3a3b45;
    }

    .customer-name small {
        font-size: 0.85rem;
        color: #858796;
    }

    .table .btn-icon {
        height: 30px;
        width: 30px;
    }
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة العملاء</h1>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">إجمالي العملاء</div><div class="value">{{ $totalClients }}</div></div>
        <div class="kpi-card" style="border-left-color: #1cc88a;"><div class="label">إجمالي قيمة الاتفاقيات</div><div class="value">{{ number_format($totalAgreements, 2) }}</div></div>
    </div>

    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title"><h3 class="card-label">قائمة العملاء</h3></div>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.customers.create') }}" class="btn btn-primary font-weight-bolder"><i class="fas fa-plus"></i> إضافة عميل</a>
                <a href="{{ route('dashboard.customers.trash.index') }}" class="btn btn-danger font-weight-bolder ml-2"><i class="fas fa-trash"></i> سلة المحذوفات</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-controls">
                <form action="{{ route('dashboard.customers.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو الهاتف..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-light-primary">بحث</button>
                </form>
                <div class="header-actions">
                    <a href="{{ route('dashboard.customers.export.excel') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> تصدير Excel</a>
                    <button onclick="window.print();" class="btn btn-info"><i class="fas fa-print"></i> طباعة</button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><a href="{{ route('dashboard.customers.index', ['sort_by' => 'name', 'sort_order' => ($sortOrder ?? 'desc') == 'asc' ? 'desc' : 'asc']) }}">الاسم</a></th>
                            <th><a href="{{ route('dashboard.customers.index', ['sort_by' => 'agreement_amount', 'sort_order' => ($sortOrder ?? 'desc') == 'asc' ? 'desc' : 'asc']) }}">قيمة الاتفاقية</a></th>
                            <th>عدد العقود</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>
                                    <div class="customer-name">
                                        <strong>{{ $customer->name }}</strong>
                                        <small>{{ $customer->phone ?? 'لا يوجد هاتف' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->agreement_amount)
                                        {{ number_format($customer->agreement_amount, 2) }} {{ $customer->currency }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $customer->contracts_count }}</td>
                                <td nowrap="nowrap">
                                    <a href="{{ route('dashboard.customers.show', $customer->id) }}" class="btn btn-sm btn-clean btn-icon" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('dashboard.customers.edit', $customer->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $customer->id }}" action="{{ route('dashboard.customers.destroy', $customer->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="حذف" onclick="confirmDelete({{ $customer->id }})"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">لا توجد بيانات لعرضها.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $customers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id ) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم نقل هذا العميل إلى سلة المحذوفات!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، انقله!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
