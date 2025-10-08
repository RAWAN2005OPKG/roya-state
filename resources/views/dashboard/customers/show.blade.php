@extends('layouts.container')
@section('title', 'تفاصيل العميل')

@section('styles')
    <style>
        .details-card { background-color: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .details-header { border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .detail-item { background-color: #f8fafc; padding: 15px; border-radius: 8px; }
        .detail-item strong { display: block; color: #4f46e5; margin-bottom: 5px; }
        .detail-item span { color: #1f2937; font-size: 1.1rem; }
    </style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="details-card" style="max-width: 1200px; margin: auto;">
        <div class="details-header d-flex justify-content-between align-items-center">
            <h2>تفاصيل العميل: {{ $customer->name }}</h2>
            <a href="{{ route('dashboard.customers.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>

        <div class="details-grid">
            <div class="detail-item"><strong>الاسم:</strong> <span>{{ $customer->name }}</span></div>
            <div class="detail-item"><strong>الهاتف:</strong> <span>{{ $customer->phone ?? 'غير مسجل' }}</span></div>
            <div class="detail-item"><strong>المشروع:</strong> <span>{{ $customer->project ?? 'غير محدد' }}</span></div>
            <div class="detail-item"><strong>الوحدة:</strong> <span>{{ $customer->unit }}</span></div>
            <div class="detail-item"><strong>قيمة الاتفاقية:</strong> <span>{{ number_format($customer->agreement_amount, 2) }} {{ $customer->currency }}</span></div>
            <div class="detail-item"><strong>طريقة الدفع:</strong> <span>{{ $customer->payment_method }}</span></div>
            <div class="detail-item"><strong>تاريخ الاستحقاق:</strong> <span>{{ $customer->due_date ? $customer->due_date->format('d-m-Y') : 'غير محدد' }}</span></div>
            <div class="detail-item"><strong>تاريخ الإنشاء:</strong> <span>{{ $customer->created_at->format('d-m-Y H:i') }}</span></div>
            @if($customer->contract_file)
                <div class="detail-item" style="grid-column: 1 / -1;">
                    <strong>ملف العقد:</strong>
                    <span><a href="{{ asset('storage/' . $customer->contract_file) }}" target="_blank" class="btn btn-info btn-sm">عرض الملف المرفق</a></span>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
