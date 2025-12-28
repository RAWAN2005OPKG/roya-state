@extends('layouts.container')
@section('title', 'تعديل العقد: ' . $contract->contract_id)

@push('styles')
<style>
    .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e9ecef; }
    .form-section-title { font-size: 1.3rem; color: #4f46e5; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4f46e5; }
    .hidden-section { display: none; }
</style>
@endpush

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 900px; margin: auto;">
        <div class="card-header"><h3 class="card-title">تعديل العقد: {{ $contract->contract_id }}</h3></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            {{-- ✅ تم التعديل هنا --}}
            <form action="{{ route('dashboard.contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- باقي النموذج يبقى كما هو بدون تغيير --}}
                @php
                    $contract_type = '';
                    if ($contract->contractable_type == \App\Models\Customer::class) $contract_type = 'customer';
                    elseif ($contract->contractable_type == \App\Models\Investor::class) $contract_type = 'investor';
                    elseif ($contract->contractable_type == \App\Models\Subcontractor::class) $contract_type = 'subcontractor';
                @endphp

                <div class="form-section">
                    <h4 class="form-section-title">1. تحديد صاحب العقد</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="contract_type">نوع العقد *</label>
                            <select name="contract_type" id="contract_type" class="form-control" required>
                                <option value="customer" @selected(old('contract_type', $contract_type) == 'customer')>عقد بيع (عميل)</option>
                                <option value="investor" @selected(old('contract_type', $contract_type) == 'investor')>عقد استثمار</option>
                                <option value="subcontractor" @selected(old('contract_type', $contract_type) == 'subcontractor')>عقد مقاولة/خدمة</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <div id="contractable_selector"></div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">2. تفاصيل العقد الأساسية</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>رقم العقد *</label><input type="text" name="contract_id" class="form-control" value="{{ old('contract_id', $contract->contract_id) }}" required></div>
                        <div class="col-md-6 form-group mb-3"><label>تاريخ التوقيع *</label><input type="date" name="signing_date" class="form-control" value="{{ old('signing_date', $contract->signing_date->format('Y-m-d')) }}" required></div>
                        <div class="col-md-6 form-group mb-3"><label>قيمة العقد *</label><input type="number" name="investment_amount" class="form-control" value="{{ old('investment_amount', $contract->investment_amount) }}" step="0.01" required></div>
                        <div class="col-md-6 form-group mb-3"><label>العملة *</label><select name="currency" class="form-control" required><option value="ILS" @selected(old('currency', $contract->currency) == 'ILS')>شيكل</option><option value="USD" @selected(old('currency', $contract->currency) == 'USD')>دولار</option><option value="JOD" @selected(old('currency', $contract->currency) == 'JOD')>دينار</option></select></div>
                        <div class="col-md-6 form-group mb-3"><label>المشروع المرتبط</label><select name="project_id" class="form-control">
                            <option value="">-- لا يوجد --</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id', $contract->project_id) == $project->id)>{{ $project->project_name }}</option>
                            @endforeach
                        </select></div>
                         <div class="col-md-6 form-group mb-3"><label>الحالة *</label><select name="status" class="form-control" required>
                            <option value="active" @selected(old('status', $contract->status) == 'active')>نشط</option>
                            <option value="draft" @selected(old('status', $contract->status) == 'draft')>مسودة</option>
                            <option value="completed" @selected(old('status', $contract->status) == 'completed')>مكتمل</option>
                            <option value="cancelled" @selected(old('status', $contract->status) == 'cancelled')>ملغي</option>
                        </select></div>
                    </div>
                </div>

                <div id="customer-fields" class="form-section hidden-section">
                    <h4 class="form-section-title">3. تفاصيل عقد البيع</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>رقم الشقة/الوحدة</label><input type="text" name="customer_unit_number" class="form-control" value="{{ old('customer_unit_number', $details['customer_unit_number'] ?? '') }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>تاريخ التسليم المتوقع</label><input type="date" name="customer_delivery_date" class="form-control" value="{{ old('customer_delivery_date', $details['customer_delivery_date'] ?? '') }}"></div>
                    </div>
                </div>

                <div id="investor-fields" class="form-section hidden-section">
                    <h4 class="form-section-title">4. تفاصيل عقد الاستثمار</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>نسبة الربح المتوقعة (%)</label><input type="number" name="investor_profit_percentage" class="form-control" value="{{ old('investor_profit_percentage', $details['investor_profit_percentage'] ?? '') }}" step="0.01"></div>
                        <div class="col-md-6 form-group mb-3"><label>مدة الاستثمار (بالأشهر)</label><input type="number" name="investor_duration" class="form-control" value="{{ old('investor_duration', $details['investor_duration'] ?? '') }}"></div>
                    </div>
                </div>

                <div id="subcontractor-fields" class="form-section hidden-section">
                    <h4 class="form-section-title">5. تفاصيل عقد المقاولة</h4>
                    <div class="row">
                        <div class="col-12 form-group mb-3"><label>وصف نطاق العمل</label><textarea name="subcontractor_scope" class="form-control" rows="3">{{ old('subcontractor_scope', $details['subcontractor_scope'] ?? '') }}</textarea></div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">6. الشروط والمرفقات</h4>
                    <div class="row">
                        <div class="col-12 form-group mb-3"><label>شروط وأحكام العقد</label><textarea name="terms" class="form-control" rows="4">{{ old('terms', $contract->terms) }}</textarea></div>
                        <div class="col-12 form-group mb-3">
                            <label>تغيير ملف العقد (PDF, JPG, PNG)</label><input type="file" name="attachment" class="form-control">
                            @if($contract->attachment)
                                <a href="{{ Storage::url($contract->attachment) }}" target="_blank">عرض الملف الحالي</a>
                            @endif
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">تحديث العقد</button>
            </form>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const contractTypeSelect = document.getElementById('contract_type');
    const contractableSelectorDiv = document.getElementById('contractable_selector');
    const oldContractableId = "{{ old('contractable_id', $contract->contractable_id) }}";
    const oldContractType = "{{ old('contract_type', $contract_type) }}";

    const templates = {
        customer: `<label for="contractable_id">اختر العميل *</label><select name="contractable_id" class="form-control" required>@foreach($customers as $item)<option value="{{ $item->id }}" \${oldContractType === 'customer' && oldContractableId == '{{ $item->id }}' ? 'selected' : ''}>{{ $item->name }}</option>@endforeach</select>`,
        investor: `<label for="contractable_id">اختر المستثمر *</label><select name="contractable_id" class="form-control" required>@foreach($investors as $item)<option value="{{ $item->id }}" \${oldContractType === 'investor' && oldContractableId == '{{ $item->id }}' ? 'selected' : ''}>{{ $item->name }}</option>@endforeach</select>`,
        subcontractor: `<label for="contractable_id">اختر المقاول *</label><select name="contractable_id" class="form-control" required>@foreach($subcontractors as $item)<option value="{{ $item->id }}" \${oldContractType === 'subcontractor' && oldContractableId == '{{ $item->id }}' ? 'selected' : ''}>{{ $item->name }}</option>@endforeach</select>`,
    };

    function updateForm() {
        const selectedType = contractTypeSelect.value;
        contractableSelectorDiv.innerHTML = templates[selectedType] || '';
        document.getElementById('customer-fields').style.display = selectedType === 'customer' ? 'block' : 'none';
        document.getElementById('investor-fields').style.display = selectedType === 'investor' ? 'block' : 'none';
        document.getElementById('subcontractor-fields').style.display = selectedType === 'subcontractor' ? 'block' : 'none';
    }
    updateForm();
    contractTypeSelect.addEventListener('change', updateForm);
});
</script>
@endsection
