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
            <div class="table-controls">
                <form action="{{ route('dashboard.projects.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم, المالك, العنوان..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-light-primary">بحث</button>
                </form>
                <div class="header-actions">
                    <a href="{{ route('dashboard.projects.export.excel') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> تصدير Excel</a>
                    <button onclick="window.print();" class="btn btn-info"><i class="fas fa-print"></i> طباعة</button>
                </div>
            </div>
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><a href="{{ route('dashboard.projects.index', ['sort_by' => 'name', 'sort_order' => ($sortBy == 'name' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">اسم المشروع</a></th>
                            <th><a href="{{ route('dashboard.projects.index', ['sort_by' => 'owner_name', 'sort_order' => ($sortBy == 'owner_name' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">المالك</a></th>
                            <th><a href="{{ route('dashboard.projects.index', ['sort_by' => 'project_status', 'sort_order' => ($sortBy == 'project_status' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">الحالة</a></th>
                            <th><a href="{{ route('dashboard.projects.index', ['sort_by' => 'total_budget', 'sort_order' => ($sortBy == 'total_budget' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">الميزانية</a></th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr>
                                <td><strong>{{ $project->name }}</strong>  
<small>{{ $project->project_title }}</small></td>
                                <td>{{ $project->owner_name }}</td>
                                <td><span class="badge badge-info">{{ $project->project_status }}</span></td>
                                <td>{{ number_format($project->total_budget, 2) }} {{ $project->currency }}</td>
                                <td nowrap="nowrap">
                                    <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $project->id }}" action="{{ route('dashboard.projects.destroy', $project->id) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="حذف" onclick="confirmDelete({{ $project->id }})"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">لا توجد مشاريع لعرضها.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $projects->appends(request()->query())->links() }}</div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id ) {
        Swal.fire({
            title: 'هل أنت متأكد؟', text: "سيتم نقل هذا المشروع إلى سلة المحذوفات!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، انقله!', cancelButtonText: 'إلغاء'
        }).then((result) => { if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); } });
    }
</script>
@endsection
