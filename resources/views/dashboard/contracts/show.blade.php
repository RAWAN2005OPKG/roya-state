@extends('layouts.container')
@section('title', 'تفاصيل العقد: ' . $contract->contract_id)

@section('styles')
<style>
    .main-content { max-width: 900px; margin: 2rem auto; }
    .card { background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .card-header { padding: 1.5rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center; }
    .card-title { font-size: 1.5rem; font-weight: 700; margin: 0; }
    .card-body { padding: 1.5rem; }
    .detail-group { margin-bottom: 2rem; }
    .detail-group h5 { color: #4f46e5; font-size: 1.1rem; margin-bottom: 1rem; padding-bottom: 5px; border-bottom: 2px solid #4f46e5; display: inline-block; }
    .detail-item { display: flex; justify-content: space-between; padding: 0.75rem 0; border-bottom: 1px solid #f1f1f1; }
    .detail-item strong { color: #555; }
    .detail-item span { color: #777; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">تفاصيل العقد: {{ $contract->contract_id }}</h2>
            <a href="{{ route('dashboard.contracts.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>
        <div class="card-body">
            <!-- قسم صاحب العقد -->
            <div class="detail-group">
                <h5>صاحب العقد</h5>
                <div class="detail-item"><strong>الاسم:</strong> <span>{{ $contract->contractable->name ?? 'غير محدد' }}</span></div>
                <div class="detail-item"><strong>نوع العقد:</strong>
                    <span>
                        @if($contract->contractable_type == \App\Models\Customer::class) عقد عميل
                        @elseif($contract->contractable_type == \App\Models\Investor::class) عقد استثمار
                        @else عقد مقاول @endif
                    </span>
                </div>
            </div>

            <!-- قسم تفاصيل العقد -->
            <div class="detail-group">
                <h5>تفاصيل العقد</h5>
                <div class="detail-item"><strong>المشروع المرتبط:</strong> <span>{{ $contract->project->project_name ?? 'لا يوجد' }}</span></div>
                <div class="detail-item"><strong>تاريخ التوقيع:</strong> <span>{{ $contract->signing_date->format('d-m-Y') }}</span></div>
                <div class="detail-item"><strong>قيمة العقد:</strong> <span>{{ number_format($contract->investment_amount, 2) }} {{ $contract->currency }}</span></div>
                <div class="detail-item"><strong>الحالة:</strong> <span>{{ $contract->status }}</span></div>
            </div>

            <!-- قسم الشروط والمرفقات -->
            @if($contract->terms || $contract->attachment)
            <div class="detail-group">
                <h5>الشروط والمرفقات</h5>
                @if($contract->terms)
                    <div class="detail-item"><strong>شروط العقد:</strong> <span>{{ $contract->terms }}</span></div>
                @endif
                @if($contract->attachment)
                    <div class="detail-item"><strong>الملف المرفق:</strong> <span><a href="{{ Storage::url($contract->attachment) }}" target="_blank">عرض الملف</a></span></div>
                @endif
            </div>
            @endif
        </div>
    </div>
</main>
@endsection
