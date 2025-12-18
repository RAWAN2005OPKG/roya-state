@extends('layouts.container')
@section('title', 'مرجوعات المشتريات')
@section('content')
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title"><h3 class="card-label">مرجوعات المشتريات</h3></div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.purchase-returns.create') }}" class="btn btn-primary font-weight-bolder"><i class="fas fa-plus"></i> إضافة مرجوع مشتريات</a>
        </div>
    </div>
    <div class="card-body">
        {{-- جدول عرض مرجوعات المشتريات --}}
        <div class="table-responsive">
            <table class="table table-head-custom table-hover">
                <thead>
                    <tr>
                        <th>رقم المرتجع</th>
                        <th>المورد</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- هنا يتم عرض البيانات من الكنترولر --}}
                    <tr><td colspan="5" class="text-center p-5 text-muted">لا توجد مرجوعات مشتريات لعرضها.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
