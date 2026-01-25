@extends('layouts.container')
@section('title', 'إدارة الشيكات')

@section('content')
{{-- قسم الملخص المالي --}}
<div class="row">
    <div class="col-lg-3 col-6"><div class="small-box bg-info"><div class="inner"><h3>{{ number_format($stats['pending_inbound']) }}</h3><p>شيكات قبض قيد التحصيل</p></div><div class="icon"><i class="fas fa-hourglass-half"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-warning"><div class="inner"><h3>{{ number_format($stats['pending_outbound']) }}</h3><p>شيكات صرف قيد الاستحقاق</p></div><div class="icon"><i class="fas fa-paper-plane"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-success"><div class="inner"><h3>{{ number_format($stats['collected_total']) }}</h3><p>إجمالي الشيكات المحصلة</p></div><div class="icon"><i class="fas fa-check-circle"></i></div></div></div>
    <div class="col-lg-3 col-6"><div class="small-box bg-danger"><div class="inner"><h3>{{ number_format($stats['bounced_total']) }}</h3><p>إجمالي الشيكات المرتجعة</p></div><div class="icon"><i class="fas fa-exclamation-triangle"></i></div></div></div>
</div>

{{-- جدول الشيكات --}}
<div class="card card-custom">
    <div class="card-header"><h3 class="card-title">قائمة الشيكات</h3><div class="card-toolbar"><a href="{{ route('dashboard.cheques.create') }}" class="btn btn-primary">إضافة شيك جديد</a></div></div>
    <div class="card-body">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>رقم الشيك</th>
                        <th>النوع</th>
                        <th>القيمة</th>
                        <th>تاريخ الاستحقاق</th>
                        <th>من / إلى</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cheques as $cheque)
                        <tr>
                            <td>{{ $cheque->cheque_number }}</td>
                            <td>
                                @if($cheque->type == 'inbound') <span class="badge badge-success">قبض</span>
                                @else <span class="badge badge-danger">صرف</span> @endif
                            </td>
                            <td>{{ number_format($cheque->amount, 2) }}</td>
                            <td>{{ $cheque->due_date->format('Y-m-d') }}</td>
                            <td>{{ $cheque->payable->name ?? 'غير محدد' }}</td>
                            <td>
                                <form action="{{ route('dashboard.cheques.updateStatus', $cheque) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="pending" @selected($cheque->status == 'pending')>قيد الانتظار</option>
                                        <option value="collected" @selected($cheque->status == 'collected')>محصّل</option>
                                        <option value="bounced" @selected($cheque->status == 'bounced')>مرتجع</option>
                                    </select>
                                </form>
                            </td>
                            <td><a href="{{ route('dashboard.cheques.edit', $cheque) }}" class="btn btn-sm btn-icon btn-light-warning"><i class="la la-edit"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">لا توجد شيكات لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">{{ $cheques->links() }}</div>
    </div>
</div>
@endsection
