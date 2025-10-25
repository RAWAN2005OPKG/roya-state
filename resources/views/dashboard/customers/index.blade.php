@extends('layouts.container')
@section('title', 'إدارة العملاء')

@section('styles')
    <style>
 :root {
        --primary-color: #4f46e5; 
        --primary-hover: #3730a3; 
        --secondary-color: #06b6d4; 
        --white-bg: #ffffff; 
        --light-bg: #f8fafc; 
        --card-bg: #ffffff; 
        --text-color: #1f2937;
        --text-muted: #6b7280; 
        --border-color: #e5e7eb; 
        --success-color: #10b981; 
        --danger-color: #ef4444; 
        --warning-color: #f59e0b; 
        --info-color: #3b82f6;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', 'Arial', sans-serif;
    }

    body {
        background-color: var(--light-bg);
        color: var(--text-color);
        direction: rtl;
        text-align: right;
        line-height: 1.6;
    }

    .background {
        background-color: var(--light-bg);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        direction: rtl;
        padding: 20px;
        overflow-y: auto;
    }

    .form-container {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        width: 100%;
        max-width: 950px;
        border: 1px solid var(--border-color);
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
    }

    .header-text h1 {
        font-size: 1.8rem;
        color: var(--text-color);
        margin: 0;
    }

    .header-text p {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
    }

    .form-section {
        margin-bottom: 35px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .section-header i {
        margin-left: 5px;
    }

    .section-header h3 {
        margin: 0;
        font-weight: 600;
        color: var(--primary-color);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-color);
    }

    .form-group label.required::after {
        content: '*';
        color: var(--danger-color);
        margin-right: 5px;
    }
    input, select, textarea {
        width: 100%;
        padding: 12px 15px;
        background-color: var(--white-bg);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    input::placeholder, textarea::placeholder {
        color: var(--text-muted);
    }

    input[readonly] {
        background-color: var(--light-bg);
        color: var(--text-muted);
        cursor: not-allowed;
        border-style: dashed;
    }

    .input-with-currency {
        position: relative;
    }

    .input-with-currency .currency {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .input-with-currency input {
        padding-left: 70px;
    }

    .dynamic-section, .hidden-section {
        display: none;
        background-color: var(--light-bg);
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
        grid-column: 1 / -1;
        border: 1px solid var(--border-color);
    }

    .checklist-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 10px;
    }

    .checklist-item {
        display: flex;
        align-items: center;
        color: var(--text-color);
    }

    .checklist-item input {
        margin-left: 10px;
        width: 18px;
        height: 18px;
    }

    .checklist-item label {
        color: var(--text-color);
        font-weight: normal;
    }

    .file-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: var(--light-bg);
    }

    .file-upload-area:hover, .file-upload-area.drag-over {
        border-color: var(--primary-color);
        background-color: rgba(79, 70, 229, 0.05);
    }

    .upload-content i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .upload-content p {
        margin: 0;
        font-size: 1.1rem;
        color: var(--text-color);
    }

    .upload-content .file-types {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .media-preview {
        position: relative;
        max-width: 100%;
        margin-top: 15px;
    }

    .media-preview img, .media-preview video {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .remove-media {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .remove-media:hover {
        background-color: var(--danger-color);
        transform: scale(1.1);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
    }

    .btn-secondary {
        background-color: var(--text-muted);
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-success {
        background-color: var(--success-color);
        color: #ffffff;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    .btn-action {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.1rem;
        padding: 5px;
        transition: color 0.3s;
    }

    .btn-action:hover {
        color: var(--primary-color);
    }

    .swal2-popup {
        background: var(--white-bg) !important;
        color: var(--text-color) !important;
        border: 1px solid var(--border-color);
    }

    .swal2-title {
        color: var(--primary-color) !important;
    }

    .swal2-html-container {
        color: var(--text-muted) !important;
    }

    .swal2-confirm {
        background-color: var(--primary-color) !important;
        color: white !important;
    }

    .swal2-cancel {
        background-color: var(--text-muted) !important;
        color: #ffffff !important;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: var(--white-bg);
        margin: auto;
        padding: 30px;
        border: 1px solid var(--border-color);
        width: 90%;
        max-width: 700px;
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        position: relative;
        animation: modalOpen 0.3s ease-out;
    }

    @keyframes modalOpen {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-content h2 {
        color: var(--primary-color);
        margin-bottom: 20px;
        text-align: center;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--text-muted);
        text-decoration: none;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }

    .btn-back:hover {
        color: var(--primary-color);
    }

    .main-content {
        width: 100%;
        max-width: 1600px;
        margin: 40px auto;
        padding: 0 20px;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 30px;
        background-color: var(--white-bg);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
    }

    .page-header h1 {
        font-size: 2.8rem;
        color: var(--text-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header h1 i {
        font-size: 2.5rem;
        color: var(--primary-color);
    }

    .header-actions {
        display: flex;
        gap: 15px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .kpi-card {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .kpi-card .label {
        color: var(--text-muted);
        margin-bottom: 15px;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .kpi-card .value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-color);
        line-height: 1.2;
    }

    .table-container {
        background-color: var(--white-bg);
        padding: 35px;
        border-radius: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 40px;
    }

    .container-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .container-title {
        font-size: 1.8rem;
        color: var(--text-color);
        margin: 0;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 18px 15px;
        text-align: right;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
        vertical-align: middle;
        color: var(--text-color);
    }

    .data-table th {
        font-size: 1.05rem;
        color: var(--text-muted);
        font-weight: 600;
        background-color: var(--light-bg);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.05);
        transform: scale(1.01);
    }

    .report-section {
        background-color: var(--white-bg);
        padding: 35px;
        border-radius: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-top: 40px;
    }

    .report-section .page-header {
        background: none;
        padding: 0;
        margin-bottom: 25px;
        box-shadow: none;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }

    .report-section .page-header h2 {
        font-size: 2rem;
        color: var(--text-color);
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .main-content {
            padding: 0 10px;
            margin: 20px auto;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .container-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .data-table th, .data-table td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }
    }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .search-form { display: flex; gap: 10px; }
        .header-actions { display: flex; gap: 10px; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة العملاء</h1>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">إجمالي العملاء</div><div class="value">{{ $totalClients }}</div></div>
        <div class="kpi-card"><div class="label">إجمالي الاتفاقيات</div><div class="value">{{ number_format($totalAgreements, 2) }}</div></div>

    </div>

    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">قائمة العملاء</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.customers.create') }}" class="btn btn-primary font-weight-bolder"><i class="fas fa-plus"></i> إضافة عميل</a>
                <a href="{{ route('dashboard.customers.trash.index') }}" class="btn btn-danger font-weight-bolder ml-2"><i class="fas fa-trash"></i> سلة المحذوفات</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-controls">
                <form action="{{ route('dashboard.customers.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="ابحث عن عميل..." value="{{ $search }}">
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
                            <th><a href="{{ route('dashboard.customers.index', ['sort_by' => 'name', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">الاسم</a></th>
                            <th><a href="{{ route('dashboard.customers.index', ['sort_by' => 'project', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">المشروع/الوحدة</a></th>
                            <th><a href="{{ route('dashboard.customers.index', ['sort_by' => 'agreement_amount', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">قيمة الاتفاقية</a></th>
                            <th>طريقة الدفع</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td><strong>{{ $customer->name }}</strong>
<small>{{ $customer->phone }}</small></td>
                                <td>{{ $customer->project }} / {{ $customer->unit }}</td>
                                <td>{{ number_format($customer->agreement_amount, 2) }} {{ $customer->currency }}</td>
                                <td>{{ $customer->payment_method }}</td>
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
                            <tr><td colspan="5" class="text-center">لا توجد بيانات لعرضها.</td></tr>
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

@section('script')
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
        })
    }
</script>
@endsection
