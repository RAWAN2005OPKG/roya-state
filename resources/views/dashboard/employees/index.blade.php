@extends('layouts.container')
@section('title', 'إدارة الموظفين')
@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة الموظفين</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.employees.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة موظف</a>
            <a href="{{ route('dashboard.employees.trash.index') }}" class="btn btn-danger"><i class="fas fa-trash"></i> سلة المحذوفات</a>
        </div>
    </div>
    <div class="card card-custom">
        <div class="card-body">
            <div class="table-controls">
                <form action="{{ route('dashboard.employees.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم, المنصب, الهاتف..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-light-primary">بحث</button>
                </form>
                <div class="header-actions">
                    <a href="{{ route('dashboard.employees.export.excel') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> تصدير Excel</a>
                    <button onclick="window.print();" class="btn btn-info"><i class="fas fa-print"></i> طباعة</button>
                </div>
            </div>
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><a href="{{ route('dashboard.employees.index', ['sort_by' => 'name', 'sort_order' => ($sortBy == 'name' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">الاسم</a></th>
                            <th><a href="{{ route('dashboard.employees.index', ['sort_by' => 'position', 'sort_order' => ($sortBy == 'position' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">المنصب</a></th>
                            <th><a href="{{ route('dashboard.employees.index', ['sort_by' => 'salary', 'sort_order' => ($sortBy == 'salary' && $sortOrder == 'asc') ? 'desc' : 'asc']) }}">الراتب</a></th>
                            <th>الهاتف</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td><strong>{{ $employee->name }}</strong>  
<small>{{ $employee->email }}</small></td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ number_format($employee->salary, 2) }} {{ $employee->currency }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td nowrap="nowrap">
                                    <a href="{{ route('dashboard.employees.edit', $employee->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <form id="delete-form-{{ $employee->id }}" action="{{ route('dashboard.employees.destroy', $employee->id) }}" method="POST" style="display: inline;">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon" title="حذف" onclick="confirmDelete({{ $employee->id }})"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">لا توجد بيانات لعرضها.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $employees->appends(request()->query())->links() }}</div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id ) {
        Swal.fire({
            title: 'هل أنت متأكد؟', text: "سيتم نقل هذا الموظف إلى سلة المحذوفات!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، انقله!', cancelButtonText: 'إلغاء'
        }).then((result) => { if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); } });
    }
</script>
@endsection
