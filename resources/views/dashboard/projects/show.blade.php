
@extends('layouts.container')
@section('title', 'تفاصيل المشروع: ' . $project->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<style>
    /* تحسينات عامة للواجهة */
    .card-custom .card-header {
        border-bottom: 1px solid #ebedf2;
    }
    .card-custom .card-label {
        font-size: 1.25rem;
        font-weight: 600;
    }
    .tab-content {
        padding: 20px 0;
    }
    .table-borderless td, .table-borderless th {
        padding: 0.5rem 0;
    }
    .table-borderless td:first-child {
        width: 30%; /* تحديد عرض العمود الأول للعناوين */
        font-weight: 600;
        color: #3f4254;
    }
    /* تنسيق خاص للمخطط */
    #unitStatusChart {
        max-height: 300px;
    }
</style>
@endpush

@section('content' )
<div class="card card-custom gutter-b">
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

        {{-- شريط الإحصائيات العلوي (Cards) --}}
        <div class="row mb-5">
            <div class="col-md-3">
                <div class="card bg-light-primary shadow-sm p-5">
                    <span class="font-weight-bold text-muted">إجمالي الوحدات</span>
                    <h3 class="mt-1 text-primary">{{ $totalUnits }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-success shadow-sm p-5">
                    <span class="font-weight-bold text-muted">الوحدات المباعة</span>
                    <h3 class="mt-1 text-success">{{ $unitsSold }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-warning shadow-sm p-5">
                    <span class="font-weight-bold text-muted">المتبقي للبيع</span>
                    <h3 class="mt-1 text-warning">{{ $unitsAvailable }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light-info shadow-sm p-5">
                    <span class="font-weight-bold text-muted">نسبة الإنجاز</span>
                    <h3 class="mt-1 text-info">{{ $project->completion_percentage }}%</h3>
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
                        <h5 class="mb-4 text-primary">تفاصيل المشروع والجدولة</h5>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>اسم المشروع:</td>
                                <td>{{ $project->name }}</td>
                            </tr>
                            <tr>
                                <td>الموقع:</td>
                                <td>{{ $project->location ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>تاريخ البدء:</td>
                                <td>{{ $project->start_date->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <td>النهاية المتوقعة:</td>
                                <td>{{ $project->estimated_end_date ? $project->estimated_end_date->format('Y-m-d') : '-' }}</td>
                            </tr>
                            <tr>
                                <td>مدة البناء (شهر):</td>
                                <td>{{ $project->duration_months ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>الحالة الحالية:</td>
                                <td><span class="badge badge-light-{{ $project->status == 'in_progress' ? 'warning' : ($project->status == 'completed' ? 'success' : 'info') }}">{{ $project->status }}</span></td>
                            </tr>
                        </table>

                        <h5 class="mt-5 mb-3 text-primary">الجهات المسؤولة</h5>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>المقاول الرئيسي:</td>
                                <td>{{ $project->main_contractor ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>المهندس المعماري:</td>
                                <td>{{ $project->architect ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-4 text-primary">توزيع حالة الوحدات (مخطط)</h5>
                        <div style="max-width: 350px; margin: auto;">
                            <canvas id="unitStatusChart"></canvas>
                        </div>

                        @if($project->attachments)
                        <h5 class="mt-5 mb-3 text-primary">المرفقات</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($project->attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-dark-75 text-hover-primary">
                                        <i class="fas fa-file-alt mr-2"></i> {{ basename($attachment) }}
                                    </a>
                                    <span class="badge badge-light-primary badge-pill">تحميل</span>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="mb-3 text-primary">الوصف والملاحظات</h5>
                        <p class="text-dark-75">{{ $project->description ?? 'لا يوجد وصف تفصيلي.' }}</p>
                        <p class="text-muted font-italic">ملاحظات: {{ $project->notes ?? 'لا توجد ملاحظات إضافية.' }}</p>
                    </div>
                </div>
            </div>

            {{-- التبويب 2: الوحدات (مع DataTables) --}}
            <div class="tab-pane" id="tab_units" role="tabpanel">
                <h5 class="mb-4 text-primary">قائمة الوحدات في المشروع ({{ $totalUnits }} وحدة)</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="unitsTable">
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
                                <td><span class="badge badge-pill badge-{{ $unit->status == 'sold' ? 'danger' : ($unit->status == 'reserved' ? 'warning' : 'success') }}">{{ $unit->status }}</span></td>
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
                <table class="table table-bordered table-striped">
                    <tr>
                        <td class="font-weight-bold w-50">التكلفة الإجمالية المتوقعة:</td>
                        <td><span class="text-danger font-weight-bold">${{ number_format($project->estimated_cost_usd, 2) }}</span></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">إجمالي القيمة المتوقعة للوحدات:</td>
                        <td><span class="text-success font-weight-bold">${{ number_format($totalExpectedValue, 2) }}</span></td>
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
                <div class="alert alert-secondary">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    هذا القسم مخصص لعرض وإدارة عقود الشراء والبيع. سيتم تطويره لاحقاً.
                </div>
            </div>

            {{-- التبويب 5: المستثمرون --}}
            <div class="tab-pane" id="tab_investors" role="tabpanel">
                <h5 class="mb-4 text-primary">المستثمرون في المشروع</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="investorsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>اسم المستثمر</th>
                                <th>الشركة</th>
                                <th>نسبة الاستثمار</th>
                                <th>المبلغ المستثمر</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project->investors as $investor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $investor->name }}</td>
                                <td>{{ $investor->company ?? '-' }}</td>
                                <td><span class="badge badge-light-info">{{ number_format($investor->pivot->investment_percentage, 2) }}%</span></td>
                                <td><span class="text-success font-weight-bold">${{ number_format($investor->pivot->invested_amount, 2) }}</span></td>
                                <td>{{ $investor->pivot->notes ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">لا يوجد مستثمرون مسجلون لهذا المشروع.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- مكتبة Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
{{-- مكتبة DataTables --}}
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document ).ready(function() {
        // 1. تفعيل DataTables لجدول الوحدات والمستثمرين
        $('#unitsTable, #investorsTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json" // دعم اللغة العربية
            },
            "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "الكل"] ], // خيارات عرض الصفوف المطلوبة
            "pageLength": 10 // عدد الصفوف الافتراضي
        });

        // 2. إنشاء مخطط حالة الوحدات
        const unitsData = @json($project->units);
        const soldCount = unitsData.filter(unit => unit.status === 'sold').length;
        const reservedCount = unitsData.filter(unit => unit.status === 'reserved').length;
        const availableCount = unitsData.filter(unit => unit.status === 'available').length;

        const ctx = document.getElementById('unitStatusChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['مباعة', 'محجوزة', 'متاحة'],
                    datasets: [{
                        data: [soldCount, reservedCount, availableCount],
                        backgroundColor: [
                            '#F64E60', // Danger (Sold)
                            '#FFA800', // Warning (Reserved)
                            '#1BC5BD'  // Success (Available)
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' وحدة';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
