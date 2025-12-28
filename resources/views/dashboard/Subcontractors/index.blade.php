
@extends('layouts.container')
@section('title', 'إدارة المقاولين والموردين')

@section('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
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
    .btn-icon {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 5px;
        color: #6c757d;
        transition: color 0.2s;
    }
    .btn-icon:hover {
        color: #4f46e5;
    }
    .text-danger {
        color: #ef4444 !important;
    }
</style>
@endsection
@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-hard-hat"></i> إدارة المقاولين والموردين</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.subcontractors.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مقاول جديد</a>
            <a href="{{ route('dashboard.subcontractors.trash') }}" class="btn btn-danger"><i class="fas fa-trash"></i> سلة المحذوفات</a>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <div class="table-controls">
                <form action="{{ route('dashboard.subcontractors.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="ابحث..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-light-primary">بحث</button>
                </form>
                <div class="header-actions">
                    {{-- التصحيح: اسم المسار هو .exportExcel --}}
                    <a href="{{ route('dashboard.subcontractors.exportExcel') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> تصدير Excel</a>
                    <button onclick="window.print();" class="btn btn-info"><i class="fas fa-print"></i> طباعة</button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive mt-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>نوع الخدمة</th>
                            <th>الهاتف</th>
                            <th>عدد العقود</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subcontractors as $subcontractor)
                            <tr>
                                <td><strong>{{ $subcontractor->name }}</strong></td>
                                <td>{{ $subcontractor->service_type }}</td>
                                <td>{{ $subcontractor->phone ?? '-' }}</td>
                                <td><span class="badge badge-info">{{ $subcontractor->contracts_count }}</span></td>
                                <td nowrap="nowrap">
                                    <a href="{{ route('dashboard.subcontractors.show', $subcontractor->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('dashboard.subcontractors.edit', $subcontractor->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $subcontractor->id }}" action="{{ route('dashboard.subcontractors.destroy', $subcontractor->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد؟')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">لا يوجد مقاولون لعرضهم.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $subcontractors->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
{{-- تأكد من أن مكتبة SweetAlert2 موجودة في التصميم الرئيسي --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id ) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم نقل هذا المقاول إلى سلة المحذوفات!",
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
@endsection
