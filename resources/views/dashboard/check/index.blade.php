@extends('layouts.container')
@section('title', 'إدارة الشيكات')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">إدارة الشيكات</h3>
        <div class="card-toolbar"><a href="{{ route('dashboard.checks.create') }}" class="btn btn-primary">إضافة شيك جديد</a></div>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-5">
            <div class="row">
                <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="بحث بالرقم أو الاسم..." value="{{ request('search') }}"></div>
                <div class="col-md-2"><select name="type" class="form-control"><option value="">كل الأنواع</option><option value="receivable" @selected(request('type') == 'receivable')>شيكات قبض</option><option value="payable" @selected(request('type') == 'payable')>شيكات دفع</option></select></div>
                <div class="col-md-2"><select name="status" class="form-control"><option value="">كل الحالات</option><option value="pending" @selected(request('status') == 'pending')>بالانتظار</option><option value="cashed" @selected(request('status') == 'cashed')>تم صرفه</option><option value="returned" @selected(request('status') == 'returned')>مرتجع</option><option value="cancelled" @selected(request('status') == 'cancelled')>ملغي</option></select></div>
                <div class="col-md-2"><label class="font-size-sm">استحقاق من:</label><input type="date" name="due_date_from" class="form-control" value="{{ request('due_date_from') }}"></div>
                <div class="col-md-2"><label class="font-size-sm">استحقاق إلى:</label><input type="date" name="due_date_to" class="form-control" value="{{ request('due_date_to') }}"></div>
                <div class="col-md-1"><button type="submit" class="btn btn-primary mt-5">بحث</button></div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>الرقم</th><th>النوع</th><th>الطرف</th><th>المبلغ</th><th>تاريخ الاستحقاق</th><th>الحالة</th><th>إجراءات</th></tr></thead>
                <tbody>
                    @forelse($checks as $check)
                    <tr>
                        <td><a href="{{ route('dashboard.checks.show', $check->id) }}">{{ $check->check_number }}</a></td>
                        <td>{!! $check->type == 'receivable' ? '<span class="text-success">قبض</span>' : '<span class="text-danger">دفع</span>' !!}</td>
                        <td>{{ $check->party_name }}</td>
                        <td class="font-weight-bold">{{ number_format($check->amount, 2) }} {{ $check->currency }}</td>
                        <td>{{ $check->due_date->format('Y-m-d') }}</td>
                        <td>
                            @php
                                $statusClasses = ['pending' => 'warning', 'cashed' => 'success', 'returned' => 'danger', 'cancelled' => 'secondary'];
                                $statusTexts = ['pending' => 'بالانتظار', 'cashed' => 'تم صرفه', 'returned' => 'مرتجع', 'cancelled' => 'ملغي'];
                            @endphp
                            <span class="label label-lg font-weight-bold label-light-{{ $statusClasses[$check->status] ?? 'secondary' }} label-inline">{{ $statusTexts[$check->status] ?? $check->status }}</span>
                        </td>
                        <td>
                            <div class="dropdown dropdown-inline">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="dropdown"><i class="la la-cog"></i></a>
                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                    <ul class="navi flex-column navi-hover py-2">
                                        <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">تغيير الحالة:</li>
                                        <li class="navi-item"><a href="#" onclick="event.preventDefault(); document.getElementById('status-cashed-{{$check->id}}').submit();" class="navi-link"><span class="navi-text">تم صرفه</span></a></li>
                                        <li class="navi-item"><a href="#" onclick="event.preventDefault(); document.getElementById('status-returned-{{$check->id}}').submit();" class="navi-link"><span class="navi-text">مرتجع</span></a></li>
                                        <li class="navi-item"><a href="#" onclick="event.preventDefault(); document.getElementById('status-cancelled-{{$check->id}}').submit();" class="navi-link"><span class="navi-text">ملغي</span></a></li>
                                        <li class="navi-separator my-2"></li>
                                        <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">إجراءات أخرى:</li>
                                        <li class="navi-item"><a href="{{ route('dashboard.checks.edit', $check->id) }}" class="navi-link"><span class="navi-text">تعديل</span></a></li>
                                    </ul>
                                </div>
                            </div>
                            {{-- Forms for status update --}}
                            <form id="status-cashed-{{$check->id}}" action="{{ route('dashboard.checks.update-status', $check->id) }}" method="POST" style="display: none;">@csrf @method('PUT') <input type="hidden" name="status" value="cashed"></form>
                            <form id="status-returned-{{$check->id}}" action="{{ route('dashboard.checks.update-status', $check->id) }}" method="POST" style="display: none;">@csrf @method('PUT') <input type="hidden" name="status" value="returned"></form>
                            <form id="status-cancelled-{{$check->id}}" action="{{ route('dashboard.checks.update-status', $check->id) }}" method="POST" style="display: none;">@csrf @method('PUT') <input type="hidden" name="status" value="cancelled"></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center p-5">لا توجد شيكات لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">{{ $checks->links() }}</div>
    </div>
</div>
@endsection
