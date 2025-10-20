@extends('layouts.container')
@section('title', 'عرض المشروع')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-project-diagram"></i> {{ $project->project_name }}</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> العودة للمشاريع</a>
            <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> تعديل المشروع</a>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-body">
            <h4 class="mb-3">معلومات المشروع الأساسية</h4>
            <div class="row mb-3">
                <div class="col-md-4"><strong>اسم المشروع:</strong> {{ $project->project_name }}</div>
                <div class="col-md-4"><strong>عنوان المشروع:</strong> {{ $project->project_title }}</div>
                <div class="col-md-4"><strong>تاريخ الإنشاء:</strong> {{ $project->due_date?->format('Y-m-d') ?? '-' }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>المالك:</strong> {{ $project->owner_name ?? '-' }}</div>
                <div class="col-md-4"><strong>العملة:</strong> {{ strtoupper($project->currency ?? 'USD') }}</div>
                <div class="col-md-4"><strong>سعر الشقة:</strong> {{ number_format($project->apartment_price ?? 0, 2) }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>الدفعة الأولى:</strong> {{ number_format($project->down_payment ?? 0, 2) }}</div>
                <div class="col-md-4"><strong>حالة المشروع:</strong>
                    <span class="badge {{ $project->project_status == 'ready_finished' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $project->project_status ?? '-' }}
                    </span>
                </div>
                <div class="col-md-4"><strong>الميزانية:</strong> {{ number_format($project->budget ?? 0, 2) }}</div>
            </div>

            @if($project->project_media)
                <div class="row mb-3">
                    <div class="col-12">
                        @if(pathinfo($project->project_media, PATHINFO_EXTENSION) == 'mp4')
                            <video src="{{ asset('storage/'.$project->project_media) }}" controls class="w-100"></video>
                        @else
                            <img src="{{ asset('storage/'.$project->project_media) }}" alt="صورة المشروع" class="img-fluid">
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- استثمارات المشروع --}}
    <div class="card card-custom">
        <div class="card-body">
            <h4 class="mb-3">الاستثمارات</h4>
            @if($project->investments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>المستثمر</th>
                                <th>المبلغ المستثمر</th>
                                <th>طريقة الدفع</th>
                                <th>تاريخ الاستثمار</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->investments as $investment)
                                <tr>
                                    <td>{{ $investment->investor->name ?? '-' }}</td>
                                    <td>{{ number_format($investment->amount ?? 0, 2) }}</td>
                                    <td>{{ $investment->payment_method ?? '-' }}</td>
                                    <td>{{ $investment->date?->format('Y-m-d') ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $investment->status == 'active' ? 'bg-success' : ($investment->status == 'completed' ? 'bg-primary' : 'bg-secondary') }}">
                                            {{ $investment->status == 'active' ? 'نشط' : ($investment->status == 'completed' ? 'مكتمل' : 'ملغي') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="1">إجمالي الاستثمار</th>
                                <th colspan="4">{{ number_format($project->totalInvested() ?? 0, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-muted">لا توجد استثمارات في هذا المشروع بعد.</p>
            @endif
        </div>
    </div>
</main>
@endsection

@section('styles')
<style>
.main-content {
    padding: 20px;
    background-color: #f8f9fa;
}
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.card-custom {
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}
.badge {
    font-size: 0.9rem;
    padding: 5px 10px;
}
</style>
@endsection
