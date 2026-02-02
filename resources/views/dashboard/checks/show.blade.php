@extends('layouts.container')
@section('title', 'تفاصيل شيك: ' . $check->check_number)

@push('styles')
<style>
    .details-card .detail-item { display: flex; justify-content: space-between; padding: 0.8rem 0; border-bottom: 1px solid #f1f1f1; }
    .details-card .detail-item strong { color: #5e6278; }
    .details-card .detail-item span { color: #181c32; font-weight: 500; }
    .details-section-title { font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #eff2f5; }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b details-card">
    <div class="card-header">
        <h3 class="card-title">تفاصيل الشيك</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.checks.edit', $check->id) }}" class="btn btn-primary mr-2">تعديل</a>
            <a href="{{ route('dashboard.checks.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <h4 class="details-section-title">المعلومات الأساسية</h4>
                <div class="detail-item"><strong>رقم الشيك:</strong> <span>{{ $check->check_number }}</span></div>
                <div class="detail-item"><strong>بنك الشيك:</strong> <span>{{ $check->bank_name }}</span></div>
                <div class="detail-item"><strong>تاريخ التحرير:</strong> <span>{{ $check->issue_date->format('d-m-Y') }}</span></div>
                <div class="detail-item"><strong>تاريخ الاستحقاق:</strong> <span>{{ $check->due_date->format('d-m-Y') }}</span></div>
            </div>
            <div class="col-lg-6">
                <h4 class="details-section-title">المعلومات المالية</h4>
                <div class="detail-item"><strong>المبلغ:</strong> <span class="font-weight-bolder text-primary">{{ number_format($check->amount, 2) }} {{ $check->currency }}</span></div>
                @if($check->currency !== 'ILS')
                <div class="detail-item"><strong>سعر الصرف:</strong> <span>{{ number_format($check->exchange_rate, 4) }}</span></div>
                <div class="detail-item"><strong>القيمة بالشيكل:</strong> <span class="font-weight-bolder">{{ number_format($check->amount_ils, 2) }} ILS</span></div>
                @endif
            </div>
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="row">
            <div class="col-lg-6">
                <h4 class="details-section-title">تفاصيل الطرف</h4>
                <div class="detail-item"><strong>نوع الشيك:</strong> <span>{!! $check->type == 'receivable' ? '<span class="text-success">شيك قبض (وارد)</span>' : '<span class="text-danger">شيك دفع (صادر)</span>' !!}</span></div>
                <div class="detail-item"><strong>اسم الطرف:</strong> <span>{{ $check->party_name }}</span></div>
                <div class="detail-item"><strong>رقم الهاتف:</strong> <span>{{ $check->party_phone ?? 'N/A' }}</span></div>
            </div>
            <div class="col-lg-6">
                <h4 class="details-section-title">الربط والحسابات</h4>
                <div class="detail-item"><strong>المشروع المرتبط:</strong> <span>{{ $check->project->name ?? 'لا يوجد' }}</span></div>
                <div class="detail-item"><strong>حساب الإيداع:</strong> <span>{{ $check->depositBankAccount->account_name ?? 'N/A' }}</span></div>
                <div class="detail-item"><strong>حساب الدفع:</strong> <span>{{ $check->paymentBankAccount->account_name ?? 'N/A' }}</span></div>
            </div>
        </div>
        <div class="separator separator-dashed my-10"></div>
        <div class="row">
            <div class="col-12">
                <h4 class="details-section-title">ملاحظات</h4>
                <p>{{ $check->notes ?? 'لا توجد ملاحظات.' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
