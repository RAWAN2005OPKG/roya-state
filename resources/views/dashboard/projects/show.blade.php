{{-- مسار الملف: resources/views/dashboard/projects/show.blade.php --}}

@extends('layouts.container')
@section('title', 'تفاصيل المشروع: ' . $project->name)

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">
                <i class="fas fa-city text-primary mr-2"></i>
                {{ $project->name }}
                <small class="text-muted d-block">{{ $project->location }}</small>
            </h3>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.projects.edit', $project->id) }}" class="btn btn-warning btn-sm mr-2">
                <i class="la la-edit"></i> تعديل المشروع
            </a>
            <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary btn-sm">
                <i class="la la-list"></i> قائمة المشاريع
            </a>
        </div>
    </div>
    <div class="card-body">

        {{-- شريط الإحصائيات العلوي --}}
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="card bg-light-primary shadow-sm p-3">
                    <span class="font-weight-bold">إجمالي الوحدات</span>
                    <h3 class="mt-1">{{ $totalUnits }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-success shadow-sm p-3">
                    <span class="font-weight-bold">الوحدات المباعة</span>
                    <h3 class="mt-1">{{ $unitsSold }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-warning shadow-sm p-3">
                    <span class="font-weight-bold">المتبقي للبيع</span>
                    <h3 class="mt-1">{{ $unitsAvailable }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-info shadow-sm p-3">
                    <span class="font-weight-bold">نسبة الإنجاز</span>
                    <h3 class="mt-1">{{ $project->completion_percentage }}%</h3>
                </div>
            </div>
        </div>

        {{-- نظام التبويبات (Tabs) --}}
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab_overview">
                    <i class="fas fa-info-circle"></i> نظرة عامة
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_units">
                    <i class="fas fa-home"></i> الوحدات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_finance">
                    <i class="fas fa-dollar-sign"></i> المالية
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_contracts">
                    <i class="fas fa-file-signature"></i> العقود
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab_investors">
                    <i class="fas fa-users"></i> المستثمرون
                </a>
            </li>
        </ul>

        <div class="tab-content mt-5">

            {{-- التبويب 1: نظرة عامة --}}
            <div class="tab-pane active" id="tab_overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-4 text-primary">تفاصيل المشروع</h5>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="font-weight-bold">الوصف:</td>
                                <td>{{ $project->description ?? 'لا يوجد وصف' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">المقاول الرئيسي:</td>
                                <td>{{ $project->main_contractor ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">المهندس المعماري:</td>
                                <td>{{ $project->architect ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">الحالة الحالية:</td>
                                <td><span class="badge badge-light-{{ $project->status == 'in_progress' ? 'warning' : ($project->status == 'completed' ? 'success' : 'info') }}">{{ $project->status }}</span></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ملاحظات:</td>
                                <td>{{ $project->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-4 text-primary">تقدم المشروع والجدولة</h5>
                        <div class="progress mb-3" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->completion_percentage }}%;" aria-valuenow="{{ $project->completion_percentage }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $project->completion_percentage }}% إنجاز
                            </div>
                        </div>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="font-weight-bold">البداية:</td>
                                <td>{{ $project->start_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">النهاية المتوقعة:</td>
                                <td>{{ $project->estimated_end_date ? $project->estimated_end_date->format('Y-m-d') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">مدة البناء (شهر):</td>
                                <td>{{ $project->duration_months ?? '-' }}</td>
                            </tr>
                        </table>

                        @if($project->attachments)
                        <h5 class="mt-5 mb-3 text-primary">المرفقات</h5>
                        <ul class="list-group">
                            @foreach($project->attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ Storage::url($attachment) }}" target="_blank">
                                        <i class="fas fa-file-alt mr-2"></i> {{ basename($attachment) }}
                                    </a>
                                    <span class="badge badge-light-primary badge-pill">تحميل</span>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>

            {{-- التبويب 2: الوحدات --}}
            <div class="tab-pane" id="tab_units" role="tabpanel">
                <h5 class="mb-4 text-primary">قائمة الوحدات في المشروع ({{ $totalUnits }} وحدة)</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الوحدة</th>
                                <th>النوع</th>
                                <th>الطابق</th>
                                <th>المساحة (م²)</th>
                                <th>السعر المتوقع ($)</th>
                                <th>الحالة</th>
                                <th>المواصفات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->units as $unit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $unit->unit_number }}</td>
                                <td>{{ $unit->unit_type }}</td>
                                <td>{{ $unit->floor_number ?? '-' }}</td>
                                <td>{{ number_format($unit->area_sqm, 2) }}</td>
                                <td>${{ number_format($unit->expected_price_usd, 2) }}</td>
                                <td><span class="badge badge-{{ $unit->status == 'sold' ? 'danger' : ($unit->status == 'reserved' ? 'warning' : 'success') }}">{{ $unit->status }}</span></td>
                                <td>{{ $unit->specifications ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">لا توجد وحدات مسجلة لهذا المشروع.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- التبويب 3: المالية --}}
            <div class="tab-pane" id="tab_finance" role="tabpanel">
                <h5 class="mb-4 text-primary">ملخص مالي</h5>
                <table class="table table-bordered">
                    <tr>
                        <td class="font-weight-bold w-50">التكلفة الإجمالية المتوقعة:</td>
                        <td>${{ number_format($project->estimated_cost_usd, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">إجمالي القيمة المتوقعة للوحدات:</td>
                        <td>${{ number_format($totalExpectedValue, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">إجمالي قيمة الوحدات المباعة:</td>
                        <td>${{ number_format(0, 2) }} <small class="text-muted">(يجب حسابها من عقود البيع)</small></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">المصروفات الفعلية:</td>
                        <td>${{ number_format(0, 2) }} <small class="text-muted">(يجب جلبها من جدول المصروفات)</small></td>
                    </tr>
                </table>
            </div>

            {{-- التبويب 4: العقود --}}
            <div class="tab-pane" id="tab_contracts" role="tabpanel">
                <h5 class="mb-4 text-primary">قائمة العقود المرتبطة بالمشروع</h5>
                <p class="text-muted">هنا ستظهر قائمة بعقود الشراء (المقاولين) وعقود البيع (العملاء) المرتبطة بهذا المشروع.</p>
                {{-- يمكنك إضافة جدول هنا لجلب وعرض العقود --}}
            </div>

            {{-- التبويب 5: المستثمرون --}}
            <div class="tab-pane" id="tab_investors" role="tabpanel">
                <h5 class="mb-4 text-primary">المستثمرون في المشروع</h5>
                @forelse($project->investors as $investor)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-success">{{ $investor->name }}</h6>
                        <p class="card-text">
                            <strong>نسبة الاستثمار:</strong> {{ number_format($investor->pivot->investment_percentage, 2) }}%

                            <strong>المبلغ المستثمر فعلياً:</strong> ${{ number_format($investor->pivot->invested_amount, 2) }}

                            <strong>ملاحظات:</strong> {{ $investor->pivot->notes ?? 'لا يوجد' }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">لا يوجد مستثمرون مسجلون لهذا المشروع.</div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // تفعيل التبويبات إذا كنت تستخدم Bootstrap أو مكتبة القالب
    $(function () {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // يمكنك إضافة منطق هنا عند التبديل بين التبويبات
        })
    })
</script>
@endpush
