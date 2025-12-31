@extends('layouts.container')
@section('title', 'إضافة مقاول/مورد جديد')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-hard-hat text-dark mr-2"></i> إضافة مقاول/مورد جديد</h3></div>
    {{-- الخطوة 1: إضافة id للنموذج --}}
    <form action="{{ route('dashboard.subcontractors.store') }}" method="POST" id="subcontractor-form">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif

            <h4 class="mb-5 text-primary">1. بيانات المقاول/المورد</h4>
            <div class="row">
                <div class="col-md-6 form-group"><label>الاسم <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-md-6 form-group"><label>التخصص <span class="text-danger">*</span></label><input type="text" name="specialization" class="form-control" value="{{ old('specialization') }}" placeholder="مثال: بناء، كهرباء، توريد أسمنت" required></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group"><label>رقم الهوية/الشركة</label><input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}"></div>
                <div class="col-md-6 form-group"><label>رقم الجوال</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            </div>
            <div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>

            <hr class="my-10">

            <h4 class="mb-5 text-primary">2. العقودات مع المشاريع</h4>
            <div id="contracts-container"></div>
            <button type="button" id="add-contract-btn" class="btn btn-dark btn-sm mt-3"><i class="fas fa-plus"></i> إضافة عقد</button>
        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ</button>
            <a href="{{ route('dashboard.subcontractors.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- الخطوة 2: إضافة مكتبة Cleave.js --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>

<script>
    $(document ).ready(function() {
        let contractIndex = 0;
        const projectsList = @json($projects);
        const exchangeRates = {'USD': 3.75, 'JOD': 5.20, 'ILS': 1};

        // الخطوة 3: مصفوفة لتخزين كائنات Cleave
        let cleaveInstances = {};

        // دالة لتطبيق Cleave على حقل معين
        function applyCleave(selector, index) {
            cleaveInstances[index] = new Cleave(selector, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        }

        function calculateILS(index) {
            // الحصول على القيمة الخام من كائن Cleave
            const cleave = cleaveInstances[index];
            const amount = cleave ? parseFloat(cleave.getRawValue()) || 0 : 0;

            const currency = $(`#currency_${index}`).val();
            const exchangeRateGroup = $(`#exchange_rate_group_${index}`);
            const exchangeRateInput = $(`#exchange_rate_${index}`);

            if (currency === 'ILS') {
                exchangeRateGroup.hide();
                exchangeRateInput.val(1);
            } else {
                exchangeRateGroup.show();
                if(exchangeRateInput.val() == 1) {
                    exchangeRateInput.val(exchangeRates[currency] || 1);
                }
            }

            const exchangeRate = parseFloat(exchangeRateInput.val()) || 1;
            $(`#amount_ils_display_${index}`).text((amount * exchangeRate).toFixed(2) + ' ILS');
        }

        function addContractField() {
            const currentIndex = contractIndex;
            let projectOptions = '<option value="">اختر مشروع...</option>';
            projectsList.forEach(project => {
                projectOptions += `<option value="${project.id}">${project.name} (${project.location})</option>`;
            });

            const contractHtml = `
                <div class="border p-4 mb-4 rounded shadow-sm contract-item" style="border-right: 4px solid #3699FF;" data-index="${currentIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary">تفاصيل العقد #${currentIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-contract-btn"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>المشروع <span class="text-danger">*</span></label><select name="contracts[${currentIndex}][project_id]" class="form-control" required>${projectOptions}</select></div>
                        <div class="col-md-6 form-group"><label>تاريخ العقد <span class="text-danger">*</span></label><input type="date" name="contracts[${currentIndex}][contract_date]" class="form-control" value="{{ now()->toDateString() }}" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>قيمة العقد <span class="text-danger">*</span></label>
                            {{-- تغيير type إلى text ليقبل الفواصل --}}
                            <input type="text" name="contracts[${currentIndex}][contract_value]" id="contract_value_${currentIndex}" data-index="${currentIndex}" class="form-control contract-value" required>
                        </div>
                        <div class="col-md-4 form-group"><label>العملة <span class="text-danger">*</span></label>
                            <select name="contracts[${currentIndex}][currency]" id="currency_${currentIndex}" data-index="${currentIndex}" class="form-control currency-select" required>
                                <option value="ILS">شيكل (ILS)</option>
                                <option value="USD">دولار (USD)</option>
                                <option value="JOD">دينار (JOD)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="exchange_rate_group_${currentIndex}">
                            <label>سعر الصرف <span class="text-danger">*</span></label><input type="number" name="contracts[${currentIndex}][exchange_rate]" id="exchange_rate_${currentIndex}" data-index="${currentIndex}" class="form-control exchange-rate" step="0.0001" value="1">
                            <small class="form-text text-muted">القيمة بالشيكل: <strong id="amount_ils_display_${currentIndex}">0.00 ILS</strong></small>
                        </div>
                    </div>
                    <div class="form-group"><label>تفاصيل العقد</label><textarea name="contracts[${currentIndex}][contract_details]" class="form-control" rows="1"></textarea></div>
                </div>`;
            $('#contracts-container').append(contractHtml);

            // تطبيق Cleave على الحقل الجديد
            applyCleave($(`#contract_value_${currentIndex}`)[0], currentIndex);

            calculateILS(currentIndex);
            contractIndex++;
        }

        $('#add-contract-btn').on('click', addContractField);

        $(document).on('click', '.remove-contract-btn', function() {
            const item = $(this).closest('.contract-item');
            const indexToRemove = item.data('index');
            // حذف كائن Cleave من الذاكرة
            delete cleaveInstances[indexToRemove];
            item.remove();
        });

        $(document).on('input change', '.contract-value, .currency-select, .exchange-rate', function() {
            calculateILS($(this).data('index'));
        });

        addContractField();

        // الخطوة 4: الكود الجديد لتنظيف البيانات قبل الإرسال
        $('#subcontractor-form').on('submit', function() {
            $('.contract-value').each(function() {
                const index = $(this).data('index');
                const cleave = cleaveInstances[index];
                if (cleave) {
                    // تحديث قيمة الحقل لتكون القيمة الرقمية الصافية
                    $(this).val(cleave.getRawValue());
                }
            });
            return true; // استكمال إرسال النموذج
        });
    });
</script>
@endpush
