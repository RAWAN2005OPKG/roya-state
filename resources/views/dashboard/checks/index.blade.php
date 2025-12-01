@extends('layouts.container')
@section('title', 'إدارة الشيكات')

@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">حافظة الشيكات
                <span class="d-block text-muted pt-2 font-size-sm">عرض وإدارة جميع الشيكات</span>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.checks.create') }}" class="btn btn-primary font-weight-bolder">
            <span class="svg-icon svg-icon-md"><i class="fas fa-plus"></i></span>إضافة شيك جديد</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-uppercase">
                        <th>رقم الشيك</th>
                        <th>النوع</th>
                        <th>صاحب الشيك</th>
                        <th>المبلغ</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checks as $check)
                    <tr>
                        <td>{{ $check->check_number }}</td>
                        <td>
                            @if($check->type == 'incoming')<span class="label label-light-success label-inline">وارد</span>
                            @else<span class="label label-light-danger label-inline">صادر</span>@endif
                        </td>
                        <td>{{ $check->holder_name }}</td>
                        <td class="font-weight-bold">{{ number_format($check->amount, 2) }} {{ $check->currency }}</td>
                        <td>{{ $check->due_date->format('Y-m-d') }}</td>
                        <td>
                            @if($check->status == 'cashed') <span class="label label-light-info label-inline">تم الصرف</span>
                            @elseif($check->status == 'returned') <span class="label label-light-warning label-inline">مرتجع</span>
                            @else <span class="label label-light-dark label-inline">في الحافظة</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('dashboard.checks.edit', $check->id) }}" class="btn btn-sm btn-clean btn-icon" title="تعديل"><i class="la la-edit"></i></a>
                            <form action="{{ route('dashboard.checks.destroy', $check->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الشيك؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-clean btn-icon" title="حذف"><i class="la la-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center p-5 text-muted">لا توجد شيكات لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $checks->links() }}</div>
    </div>
</div>
@endsection
