@extends('layouts.container')
@section('title', 'عرض المشروع: ' . $project->project_name)

@section('styles')
<style>
    .details-card { background-color: #fff; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .details-header { border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
    .section-title { font-size: 1.5rem; color: #4f46e5; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #4f46e5; display: inline-block; }
    .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .detail-item { background-color: #f8fafc; padding: 15px; border-radius: 8px; border-left: 4px solid #4f46e5; }
    .detail-item strong { display: block; color: #6b7280; margin-bottom: 5px; font-size: 0.9rem; }
    .detail-item span { color: #1f2937; font-size: 1.1rem; font-weight: 600; }
</style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="details-card" style="max-width: 1400px; margin: auto;">
        <div class="details-header">
            <h2><i class="fas fa-project-diagram text-primary"></i> تفاصيل المشروع: {{ $project->project_name }}</h2>
            <div>
                <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> تعديل</a>
                <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary">العودة للقائمة</a>
            </div>
        </div>

        <!-- قسم معلومات المشروع الأساسية -->
        <h3 class="section-title">المعلومات الأساسية</h3>
        <div class="details-grid">
            <div class="detail-item"><strong>اسم المشروع:</strong> <span>{{ $project->project_name }}</span></div>
            <div class="detail-item"><strong>العنوان:</strong> <span>{{ $project->project_title ?? '-' }}</span></div>
            <div class="detail-item"><strong>تاريخ البدء:</strong> <span>{{ $project->start_date ? $project->start_date->format('Y-m-d') : '-' }}</span></div>
            <div class="detail-item"><strong>تاريخ الانتهاء:</strong> <span>{{ $project->end_date ? $project->end_date->format('Y-m-d') : '-' }}</span></div>
            <div class="detail-item"><strong>الميزانية:</strong> <span>{{ number_format($project->budget, 2) }} {{ $project->currency }}</span></div>
            <div class="detail-item"><strong>الحالة:</strong> <span>{{ $project->project_status ?? '-' }}</span></div>
        </div>

        <!-- قسم العملاء -->
        <h3 class="section-title mt-5">العملاء ({{ $project->customers->count() }})</h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>اسم العميل</th><th>الهاتف</th><th>تحكم</th></tr></thead>
                <tbody>
                    @forelse($project->customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td><a href="{{ route('dashboard.customers.show', $customer->id) }}" title="عرض"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">لا يوجد عملاء مرتبطون بهذا المشروع.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- قسم المستثمرين -->
        <h3 class="section-title mt-5">المستثمرون ({{ $project->investors->count() }})</h3>
        <div class="table-responsive">
            <table class="table table-hover">
                 <thead><tr><th>اسم المستثمر</th><th>الهاتف</th><th>إجمالي الاستثمار</th><th>تحكم</th></tr></thead>
                <tbody>
                    @forelse($project->investors as $investor)
                        <tr>
                            <td>{{ $investor->name }}</td>
                            <td>{{ $investor->phone ?? '-' }}</td>
                            {{-- يمكنك هنا حساب إجمالي استثمارات هذا المستثمر في هذا المشروع تحديدًا --}}
                            <td>...</td> 
                            <td><a href="{{-- route('dashboard.investors.show', $investor->id) --}}" title="عرض"><i class="fas fa-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">لا يوجد مستثمرون مرتبطون بهذا المشروع.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
