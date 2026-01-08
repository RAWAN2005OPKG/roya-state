@extends('layouts.container')
@section('title', 'تفاصيل المقاول: ' . $subcontractor->name)

@section('styles')
<style>
    /* يمكنكِ إضافة هذه الأنماط في ملف CSS الرئيسي لتجنب التكرار */
    .details-card { background-color: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .details-header { border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .detail-item { background-color: #f8fafc; padding: 15px; border-radius: 8px; }
    .detail-item strong { display: block; color: #4f46e5; margin-bottom: 5px; }
    .detail-item span { color: #1f2937; font-size: 1.1rem; }
    .contracts-section .card-title { font-size: 1.5rem; margin-bottom: 1rem; }
</style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="details-card" style="max-width: 1200px; margin: auto;">
        <div class="details-header">
            <h2>تفاصيل المقاول: {{ $subcontractor->name }}</h2>
            <a href="{{ route('dashboard.subcontractors.index') }}" class="btn btn-secondary">العودة للقائمة</a>
        </div>

        <!-- قسم بيانات المقاول الأساسية -->
        <div class="details-grid">
            <div class="detail-item"><strong>الاسم:</strong> <span>{{ $subcontractor->name }}</span></div>
            <div class="detail-item"><strong>نوع الخدمة:</strong> <span>{{ $subcontractor->service_type }}</span></div>
            <div class="detail-item"><strong>الهاتف:</strong> <span>{{ $subcontractor->phone ?? 'غير مسجل' }}</span></div>
            <div class="detail-item"><strong>مسؤول التواصل:</strong> <span>{{ $subcontractor->contact_person ?? 'غير محدد' }}</span></div>
        </div>

        <!-- قسم عقود المقاول (هذا هو الجزء الجديد والمهم) -->
        <div class="contracts-section mt-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="card-title">عقود المقاول ({{ $subcontractor->contracts->count() }})</h3>
                {{-- يمكنك إضافة زر لإنشاء عقد جديد لهذا المقاول مباشرة --}}
                <a href="{{ route('dashboard.contracts.create', ['type' => 'subcontractor', 'id' => $subcontractor->id]) }}" class="btn btn-primary btn-sm">إضافة عقد جديد لهذا المقاول</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم العقد</th>
                            <th>المشروع</th>
                            <th>قيمة العقد</th>
                            <th>تاريخ التوقيع</th>
                            <th>الحالة</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subcontractor->contracts as $contract)
                            <tr>
                                <td><strong>{{ $contract->contract_id }}</strong></td>
                                <td>{{ $contract->project->project_name ?? '-' }}</td>
                                <td>{{ number_format($contract->investment_amount, 2) }} {{ $contract->currency }}</td>
                                <td>{{ $contract->signing_date->format('Y-m-d') }}</td>
                                <td><span class="badge">{{ $contract->status }}</span></td>
                                <td>
                                    <a href="{{ route('dashboard.contracts.show', $contract->id) }}" title="عرض تفاصيل العقد">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 2rem;">لا توجد عقود مسجلة لهذا المقاول.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
