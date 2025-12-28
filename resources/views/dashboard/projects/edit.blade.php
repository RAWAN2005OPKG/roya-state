
@extends('layouts.container')
@section('title', 'تعديل المشروع: ' . $project->name)

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit text-warning mr-2"></i>
            تعديل المشروع: {{ $project->name }}
        </h3>
    </div>
    <form action="{{ route('dashboard.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- مهم جداً لتحديد طريقة التحديث --}}
        <div class="card-body">

            {{-- رسائل التنبيه --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. تفاصيل المشروع الأساسية --}}
            <h4 class="mb-5 text-primary">1. البيانات الأساسية للمشروع</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name">اسم المشروع <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $project->name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="location">الموقع</label>
                    <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $project->location) }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="start_date">تاريخ البدء <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label for="estimated_end_date">تاريخ الانتهاء المتوقع</label>
                    <input type="date" name="estimated_end_date" id="estimated_end_date" class="form-control" value="{{ old('estimated_end_date', $project->estimated_end_date ? $project->estimated_end_date->format('Y-m-d') : '') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="duration_months">مدة البناء (بالشهور)</label>
                    <input type="number" name="duration_months" id="duration_months" class="form-control" value="{{ old('duration_months', $project->duration_months) }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="main_contractor">المقاول الرئيسي</label>
                    <input type="text" name="main_contractor" id="main_contractor" class="form-control" value="{{ old('main_contractor', $project->main_contractor) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="architect">المهندس المعماري</label>
                    <input type="text" name="architect" id="architect" class="form-control" value="{{ old('architect', $project->architect) }}">
                </div>
                <div class="col-md-4 form-group">
                    <label for="estimated_cost_usd">التكلفة المتوقعة ($)</label>
                    <input type="number" name="estimated_cost_usd" id="estimated_cost_usd" class="form-control" value="{{ old('estimated_cost_usd', $project->estimated_cost_usd) }}" step="0.01">
                </div>
            </div>
            <div class="form-group">
                <label for="description">وصف المشروع</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="notes">ملاحظات إضافية</label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes', $project->notes) }}</textarea>
            </div>
            <div class="form-group">
                <label for="attachments">مرفقات المشروع (يمكن إضافة مرفقات جديدة)</label>
                <input type="file" name="attachments[]" id="attachments" class="form-control-file" multiple>
                @if($project->attachments)
                    <p class="mt-2">المرفقات الحالية:
                        @foreach($project->attachments as $attachment)
                            <span class="badge badge-light-primary">{{ basename($attachment) }}</span>
                        @endforeach
                    </p>
                @endif
            </div>

            <hr class="my-10">

            {{-- 2. الوحدات العقارية --}}
            <h4 class="mb-5 text-primary">2. الوحدات العقارية <small class="text-muted">(سيتم استبدال الوحدات القديمة بالجديدة)</small></h4>
            <div id="units-container">
                {{-- سيتم ملء الحقول بواسطة JavaScript --}}
            </div>
            <button type="button" id="add-unit-btn" class="btn btn-success btn-sm mt-3">
                <i class="fas fa-plus"></i> إضافة وحدة جديدة
            </button>

            <hr class="my-10">

            {{-- 3. المستثمرون --}}
            <h4 class="mb-5 text-primary">3. المستثمرون <small class="text-muted">(سيتم مزامنة المستثمرين)</small></h4>
            <div id="investors-container">
                {{-- سيتم ملء الحقول بواسطة JavaScript --}}
            </div>
            <button type="button" id="add-investor-btn" class="btn btn-info btn-sm mt-3">
                <i class="fas fa-user-plus"></i> إضافة مستثمر
            </button>

        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ التعديلات</button>
            <a href="{{ route('dashboard.projects.show', $project->id) }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document ).ready(function() {
        let unitIndex = 0;
        let investorIndex = 0;
        const investorsList = @json($investors);
        const projectUnits = @json($projectUnits);
        const projectInvestors = @json($projectInvestors);

        // دالة إضافة وحدة جديدة
        function addUnitField(unit = null) {
            const unitHtml = `
                <div class="unit-item border p-4 mb-4 rounded" data-index="${unitIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning">تفاصيل الوحدة #${unitIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-unit-btn">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>رقم الوحدة <span class="text-danger">*</span></label>
                            <input type="text" name="units[${unitIndex}][unit_number]" class="form-control" value="${unit ? unit.unit_number : ''}" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>نوع الوحدة <span class="text-danger">*</span></label>
                            <select name="units[${unitIndex}][unit_type]" class="form-control" required>
                                <option value="apartment" ${unit && unit.unit_type === 'apartment' ? 'selected' : ''}>شقة</option>
                                <option value="villa" ${unit && unit.unit_type === 'villa' ? 'selected' : ''}>فيلا</option>
                                <option value="office" ${unit && unit.unit_type === 'office' ? 'selected' : ''}>مكتب</option>
                                <option value="land" ${unit && unit.unit_type === 'land' ? 'selected' : ''}>أرض</option>
                                <option value="commercial" ${unit && unit.unit_type === 'commercial' ? 'selected' : ''}>تجاري</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>المساحة (م²)<span class="text-danger">*</span></label>
                            <input type="number" name="units[${unitIndex}][area_sqm]" class="form-control" step="0.01" value="${unit ? unit.area_sqm : ''}" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>السعر المتوقع ($)<span class="text-danger">*</span></label>
                            <input type="number" name="units[${unitIndex}][expected_price_usd]" class="form-control" step="0.01" value="${unit ? unit.expected_price_usd : ''}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>الطابق</label>
                            <input type="number" name="units[${unitIndex}][floor_number]" class="form-control" value="${unit ? unit.floor_number : ''}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>الحالة</label>
                            <select name="units[${unitIndex}][status]" class="form-control">
                                <option value="available" ${unit && unit.status === 'available' ? 'selected' : ''}>متاحة</option>
                                <option value="reserved" ${unit && unit.status === 'reserved' ? 'selected' : ''}>محجوزة</option>
                                <option value="sold" ${unit && unit.status === 'sold' ? 'selected' : ''}>مباعة</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>المواصفات</label>
                        <textarea name="units[${unitIndex}][specifications]" class="form-control" rows="2">${unit ? unit.specifications : ''}</textarea>
                    </div>
                </div>
            `;
            $('#units-container').append(unitHtml);
            unitIndex++;
        }

        // دالة إضافة مستثمر جديد
        function addInvestorField(investor = null) {
            let options = '<option value="">اختر مستثمر...</option>';
            investorsList.forEach(inv => {
                const selected = investor && investor.id === inv.id ? 'selected' : '';
                options += `<option value="${inv.id}" ${selected}>${inv.name} (${inv.company || 'لا توجد شركة'})</option>`;
            });

            const investorHtml = `
                <div class="investor-item border p-4 mb-4 rounded" data-index="${investorIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning">تفاصيل المستثمر #${investorIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-investor-btn">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>اختيار المستثمر <span class="text-danger">*</span></label>
                            <select name="investors[${investorIndex}][investor_id]" class="form-control" required>
                                ${options}
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>نسبة الاستثمار (%) <span class="text-danger">*</span></label>
                            <input type="number" name="investors[${investorIndex}][investment_percentage]" class="form-control" step="0.01" min="0.01" max="100" value="${investor ? investor.pivot.investment_percentage : ''}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>المبلغ المستثمر فعلياً ($)</label>
                            <input type="number" name="investors[${investorIndex}][invested_amount]" class="form-control" step="0.01" value="${investor ? investor.pivot.invested_amount : 0}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>ملاحظات خاصة بالمستثمر في هذا المشروع</label>
                        <textarea name="investors[${investorIndex}][notes]" class="form-control" rows="1">${investor ? investor.pivot.notes : ''}</textarea>
                    </div>
                </div>
            `;
            $('#investors-container').append(investorHtml);
            investorIndex++;
        }

        // ملء حقول الوحدات الموجودة
        if (projectUnits.length > 0) {
            projectUnits.forEach(unit => addUnitField(unit));
        } else {
            addUnitField(); // إضافة حقل فارغ إذا لم توجد وحدات
        }

        // ملء حقول المستثمرين الموجودين
        if (projectInvestors.length > 0) {
            projectInvestors.forEach(investor => addInvestorField(investor));
        } else {
            addInvestorField(); // إضافة حقل فارغ إذا لم يوجد مستثمرون
        }

        // عند النقر على زر إضافة وحدة
        $('#add-unit-btn').on('click', () => addUnitField());

        // عند النقر على زر إضافة مستثمر
        $('#add-investor-btn').on('click', () => addInvestorField());

        // عند النقر على زر حذف وحدة
        $(document).on('click', '.remove-unit-btn', function() {
            $(this).closest('.unit-item').remove();
        });

        // عند النقر على زر حذف مستثمر
        $(document).on('click', '.remove-investor-btn', function() {
            $(this).closest('.investor-item').remove();
        });
    });
</script>
@endpush
