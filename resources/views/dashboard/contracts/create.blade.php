@extends('layouts.container')
@section('title', 'إضافة عقد جديد')

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
        <div class="card-header"><h3 class="card-title">نموذج إضافة عقد جديد</h3></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <form action="{{ route('dashboard.contracts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-section">
                    <h4 class="form-section-title">1. تحديد صاحب العقد</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="contract_type">نوع العقد *</label>
                            <select name="contract_type" id="contract_type" class="form-control" required>
                                <option value="">-- اختر نوع العقد --</option>
                                <option value="customer" @selected(old('contract_type') == 'customer')>عقد بيع (عميل)</option>
                                <option value="investor" @selected(old('contract_type') == 'investor')>عقد استثمار</option>
                                <option value="subcontractor" @selected(old('contract_type') == 'subcontractor')>عقد مقاولة/خدمة</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <div id="contractable_selector">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">2. تفاصيل العقد الأساسية</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>رقم العقد *</label><input type="text" name="contract_id" class="form-control" value="{{ old('contract_id') }}" required></div>
                        <div class="col-md-6 form-group mb-3"><label>تاريخ التوقيع *</label><input type="date" name="signing_date" class="form-control" value="{{ old('signing_date', date('Y-m-d')) }}" required></div>
                        <div class="col-md-6 form-group mb-3"><label>قيمة العقد الإجمالية *</label><input type="number" name="investment_amount" class="form-control" value="{{ old('investment_amount') }}" step="0.01" required></div>
                        <div class="col-md-6 form-group mb-3"><label>العملة *</label><select name="currency" class="form-control" required><option value="ILS">شيكل</option><option value="USD">دولار</option><option value="JOD">دينار</option></select></div>
                        <div class="col-md-6 form-group mb-3">
                            <label>المشروع المرتبط (اختياري)</label>
                            <select name="project_id" class="form-control">
                                <option value="">-- لا يوجد --</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="col-md-6 form-group mb-3">
                            <label>الحالة *</label>
                            <select name="status" class="form-control" required>
                                <option value="active" @selected(old('status', 'active') == 'active')>نشط</option>
                                <option value="draft" @selected(old('status') == 'draft')>مسودة</option>
                                <option value="completed">مكتمل</option>
                                <option value="cancelled">ملغي</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="customer-fields" class="form-section hidden-section">
                    <h4 class="form-section-title">3. تفاصيل عقد البيع</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>رقم الشقة/الوحدة</label><input type="text" name="customer_unit_number" class="form-control" value="{{ old('customer_unit_number') }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>تاريخ التسليم المتوقع</label><input type="date" name="customer_delivery_date" class="form-control" value="{{ old('customer_delivery_date') }}"></div>
                    </div>
                </div>

                <div id="investor-fields" class="form-section hidden-section">
                    <h4 class="form-section-title">4. تفاصيل عقد الاستثمار</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>نسبة الربح المتوقعة (%)</label><input type="number" name="investor_profit_percentage" class="form-control" value="{{ old('investor_profit_percentage') }}" step="0.01"></div>
                        <div class="col-md-6 form-group mb-3"><label>مدة الاستثمار (بالأشهر)</label><input type="number" name="investor_duration" class="form-control" value="{{ old('investor_duration') }}"></div>
                    </div>
                </div>

                <div id="subcontractor-fields" class="form-section hidden-section">
                    <h4 class="form-section-title">5. تفاصيل عقد المقاولة</h4>
                    <div class="row">
                        <div class="col-md-12 form-group mb-3"><label>وصف نطاق العمل</label><textarea name="subcontractor_scope" class="form-control" rows="3">{{ old('subcontractor_scope') }}</textarea></div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">6. الشروط والمرفقات</h4>
                    <div class="row">
                        <div class="col-12 form-group mb-3"><label>شروط وأحكام العقد</label><textarea name="terms" class="form-control" rows="4">{{ old('terms') }}</textarea></div>
                        <div class="col-12 form-group mb-3"><label>إرفاق ملف العقد (PDF, JPG, PNG)</label><input type="file" name="attachment" class="form-control"></div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">حفظ العقد</button>
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
    const customerFields = document.getElementById('customer-fields');
    const investorFields = document.getElementById('investor-fields');
    const subcontractorFields = document.getElementById('subcontractor-fields');

    const templates = {
        customer: `
            <label for="contractable_id">اختر العميل *</label>
            <select name="contractable_id" class="form-control" required>
                <option value="">-- يرجى اختيار العميل --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @selected(old('contractable_id') == $customer->id && old('contract_type') == 'customer')>{{ $customer->name }}</option>
                @endforeach
            </select>
        `,
        investor: `
            <label for="contractable_id">اختر المستثمر *</label>
            <select name="contractable_id" class="form-control" required>
                <option value="">-- يرجى اختيار المستثمر --</option>
                @foreach($investors as $investor)
                    <option value="{{ $investor->id }}" @selected(old('contractable_id') == $investor->id && old('contract_type') == 'investor')>{{ $investor->name }}</option>
                @endforeach
            </select>
        `,
        subcontractor: `
            <label for="contractable_id">اختر المقاول *</label>
            <select name="contractable_id" class="form-control" required>
                <option value="">-- يرجى اختيار المقاول --</option>
                @foreach($subcontractors as $subcontractor)
                    <option value="{{ $subcontractor->id }}" @selected(old('contractable_id') == $subcontractor->id && old('contract_type') == 'subcontractor')>{{ $subcontractor->name }}</option>
                @endforeach
            </select>
        `,
    };

    function updateForm() {
        const selectedType = contractTypeSelect.value;
        contractableSelectorDiv.innerHTML = templates[selectedType] || '';
        customerFields.style.display = selectedType === 'customer' ? 'block' : 'none';
        investorFields.style.display = selectedType === 'investor' ? 'block' : 'none';
        subcontractorFields.style.display = selectedType === 'subcontractor' ? 'block' : 'none';
    }
    updateForm();
    contractTypeSelect.addEventListener('change', updateForm);
});
</script>
@endsection
