@extends('layouts.container')
@section('title', 'إدارة المشاريع')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-project-diagram"></i> إدارة المشاريع</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.projects.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مشروع</a>
            <a href="{{ route('dashboard.projects.trash.index') }}" class="btn btn-danger"><i class="fas fa-trash"></i> سلة المحذوفات</a>
        </div>
    </div>

    <div class="card card-custom">
        <div class="card-body">
            <div class="table-controls d-flex justify-content-between align-items-center mb-3">
                <form action="{{ route('dashboard.projects.index') }}" method="GET" class="search-form d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="ابحث بالاسم, المالك..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-light-primary">بحث</button>
                </form>

                <div class="header-actions">
                    <a href="{{ route('dashboard.projects.export.excel') }}" class="btn btn-success me-2"><i class="fas fa-file-excel"></i> تصدير Excel</a>
                    <button onclick="window.print();" class="btn btn-info"><i class="fas fa-print"></i> طباعة</button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>اسم المشروع</th>
                            <th>عنوان المشروع</th>
                            <th>تاريخ الإنشاء</th>
                            <th>المالك</th>
                            <th>العملة</th>
                            <th>سعر الشقة</th>
                            <th>الدفعة الأولى</th>
                            <th>الحالة</th>
                            <th>الميزانية</th>
                            <th>إجمالي الاستثمارات</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->project_title }}</td>
                                <td>{{ $project->due_date?->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ $project->owner_name ?? '-' }}</td>
                                <td>{{ strtoupper($project->currency ?? 'USD') }}</td>
                                <td>{{ number_format($project->apartment_price ?? 0, 2) }}</td>
                                <td>{{ number_format($project->down_payment ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge {{ $project->project_status == 'ready_finished' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $project->project_status ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ number_format($project->budget ?? 0, 2) }}</td>
                                <td>{{ number_format($project->totalInvested() ?? 0, 2) }}</td>
                                <td nowrap>
                                    <a href="{{ route('dashboard.projects.show', $project->id) }}" class="btn btn-sm btn-info me-1" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-sm btn-primary me-1" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $project->id }}" action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" title="حذف" onclick="confirmDelete({{ $project->id }})"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11">لا توجد مشاريع لعرضها.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-center">
                {{ $projects->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم نقل هذا المشروع إلى سلة المحذوفات!",
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

@section('styles')
<style>
.main-content {
    padding: 20px;
    background-color: #f8f9fa;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.table-controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}
.table th a {
    text-decoration: none;
    color: inherit;
}
.table th a:hover {
    text-decoration: underline;
}
.table td, .table th {
    vertical-align: middle;
}
.badge {
    font-size: 0.9rem;
}
</style>
@endsection
