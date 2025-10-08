@extends('layouts.container')
@section('title', 'التنبيهات')

@section('styles')
<style>
    .alert-row { border-left: 5px solid; }
    .border-danger { border-left-color: #ef4444; }
    .border-warning { border-left-color: #f59e0b; }
    .border-info { border-left-color: #3b82f6; }
    .border-secondary { border-left-color: #6b7280; }
    .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-bell text-warning"></i> إدارة التنبيهات</h1>
    </div>

    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title"><h3 class="card-label">قائمة التنبيهات</h3></div>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.alerts.create') }}" class="btn btn-primary font-weight-bolder"><i class="fas fa-plus"></i> إضافة تنبيه يدوي</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-controls">
                <form action="{{ route('dashboard.alerts.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="ابحث..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-light-primary">بحث</button>
                </form>
            </div>

            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><a href="{{ route('dashboard.alerts.index', ['sort_by' => 'title', 'sort_order' => 'asc']) }}">العنوان</a></th>
                            <th><a href="{{ route('dashboard.alerts.index', ['sort_by' => 'priority', 'sort_order' => 'asc']) }}">الأولوية</a></th>
                            <th><a href="{{ route('dashboard.alerts.index', ['sort_by' => 'due_date', 'sort_order' => 'asc']) }}">تاريخ الاستحقاق</a></th>
                            <th><a href="{{ route('dashboard.alerts.index', ['sort_by' => 'status', 'sort_order' => 'asc']) }}">الحالة</a></th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alerts as $alert)
                            <tr class="alert-row border-{{ $alert->priority_color }}">
                                <td>
                                    <i class="{{ $alert->type_icon }} text-{{ $alert->priority_color }} mr-2"></i>
                                    <strong>{{ $alert->title }}</strong>


                                    <small class="text-muted">{{ $alert->message }}</small>
                                </td>
                                <td><span class="badge badge-{{ $alert->priority_color }}">{{ $alert->priority_name }}</span></td>
                                <td>{{ $alert->due_date ? $alert->due_date->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <form action="{{ route('dashboard.alerts.update', $alert->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="active" @selected($alert->status == 'active')>نشط</option>
                                            <option value="dismissed" @selected($alert->status == 'dismissed')>متجاهل</option>
                                            <option value="resolved" @selected($alert->status == 'resolved')>تم الحل</option>
                                        </select>
                                    </form>
                                </td>
                                <td nowrap="nowrap">
                                    <form action="{{ route('dashboard.alerts.destroy', $alert->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التنبيه؟');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="fas fa-trash text-danger"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5">لا توجد تنبيهات لعرضها.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $alerts->appends(request()->query())->links() }}</div>
        </div>
    </div>
</main>
@endsection
