@extends('layouts.container')
@section('title', 'مردودات المبيعات')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}">
@endpush

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-undo"></i> مردودات المبيعات</h1>
        <div class="header-actions">
            <button class="btn btn-primary"><i class="fas fa-plus"></i> إضافة مردود مبيعات</button>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card"><div class="label">إجمالي قيمة المردودات</div><div class="value text-warning">ج.م 0.00</div></div>
        <div class="kpi-card"><div class="label">عدد المردودات</div><div class="value">0</div></div>
    </div>

    <div class="table-container">
        <div class="table-controls">
            <form action="#" method="GET" class="search-form">
                <input type="text" name="search" class="form-control" placeholder="ابحث برقم الإشعار أو العميل...">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>

        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم المردود</th>
                        <th>العميل</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th class="no-print">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="text-center" style="padding: 2rem;">لا توجد مردودات مبيعات لعرضها.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
