@extends('layouts.container')
@section('title', 'إضافة عميل جديد')

@push('styles')
<style>
    .unit-sale-item { border-right: 4px solid #1BC5BD; padding-right: 15px; }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-user-plus text-primary mr-2"></i> إضافة عميل جديد</h3></div>
    <form action="{{ route('dashboard.clients.store') }}" method="POST" id="client-form">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p><strong>يرجى تصحيح الأخطاء التالية:</strong></p>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <h4 class="mb-5 text-primary">1. بيانات العميل</h4>
            <div class="row">
                <div class="col-md-6 form-group"><label>اسم العميل <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-md-6 form-group"><label>رقم الجوال</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group"><label>رقم الهوية</label><input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}"></div>
                <div class="col-md-6 form-group"><label>العنوان</label><input type="text" name="address" class="form-control" value="{{ old('address') }}"></div>
            </div>
            <div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>

            <hr class="my-10">

            <h4 class="mb-5 text-primary">2. الوحدات المشتراة <small class="text-muted">(يجب اختيار وحدة واحدة على الأقل)</small></h4>
            <div id="units-sale-container"></div>
            <button type="button" id="add-unit-sale-btn" class="btn btn-success btn-sm mt-3"><i class="fas fa-plus"></i> إضافة عملية بيع</button>
        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ البيانات</button>
            <a href="{{ route('dashboard.clients.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script>
    $(document ).ready(function() {
        let saleIndex = 0;
        const availableUnits = @json($availableUnits);
        let cleaveInstances = {};

        function applyCleave(selector, index) {
            cleaveInstances[index] = new Cleave(selector, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        }

        function calculateILS(index) {
            const cleave = cleaveInstances[index];
            if (!cleave) return;
            const amount = parseFloat(cleave.getRawValue()) || 0;
            const currency = $(`#currency_${index}`).val();
            const exchangeRateGroup = $(`#exchange_rate_group_${index}`);
            const exchangeRateInput = $(`#exchange_rate_${index}`);
            let exchangeRate = parseFloat(exchangeRateInput.val()) || 1;

            if (currency === 'ILS') {
                exchangeRateGroup.hide();
                exchangeRateInput.val(1);
                exchangeRate = 1;
            } else {
                exchangeRateGroup.show();
            }
            $(`#amount_ils_display_${index}`).text((amount * exchangeRate).toFixed(2) + ' ILS');
        }

        function addUnitSaleField(unitData = {}) {
            const currentIndex = saleIndex;
            let unitOptions = '<option value="">اختر وحدة...</option>';
            availableUnits.forEach(unit => {
                const isSelected = unit.id == unitData.unit_id ? 'selected' : '';
                const projectName = unit.project ? unit.project.name : 'N/A';
                unitOptions += `<option value="${unit.id}" ${isSelected}>[${projectName}] - ${unit.unit_number}</option>`;
            });

            const saleHtml = `
                <div class="unit-sale-item border p-4 mb-4 rounded shadow-sm" data-index="${currentIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning">تفاصيل عملية البيع #${currentIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-sale-btn"><i class="fas fa-trash"></i> حذف</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>الوحدة المشتراة <span class="text-danger">*</span></label><select name="units[${currentIndex}][unit_id]" class="form-control" required>${unitOptions}</select></div>
                        <div class="col-md-6 form-group"><label>تاريخ البيع <span class="text-danger">*</span></label><input type="date" name="units[${currentIndex}][sale_date]" class="form-control" value="${unitData.sale_date || new Date().toISOString().slice(0, 10)}" required></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group"><label>مبلغ البيع <span class="text-danger">*</span></label><input type="text" name="units[${currentIndex}][sale_price]" id="sale_price_${currentIndex}" class="form-control sale-price-input" data-index="${currentIndex}" value="${unitData.sale_price || ''}" required></div>
                        <div class="col-md-4 form-group"><label>العملة <span class="text-danger">*</span></label>
                            <select name="units[${currentIndex}][currency]" id="currency_${currentIndex}" class="form-control currency-select" data-index="${currentIndex}" required>
                                <option value="ILS" ${unitData.currency === 'ILS' ? 'selected' : ''}>شيكل (ILS)</option>
                                <option value="USD" ${unitData.currency === 'USD' ? 'selected' : ''}>دولار (USD)</option>
                                <option value="JOD" ${unitData.currency === 'JOD' ? 'selected' : ''}>دينار (JOD)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="exchange_rate_group_${currentIndex}"><label>سعر الصرف <span class="text-danger">*</span></label><input type="number" name="units[${currentIndex}][exchange_rate]" id="exchange_rate_${currentIndex}" class="form-control exchange-rate-input" data-index="${currentIndex}" step="0.01" value="${unitData.exchange_rate || '1'}"><small class="form-text text-muted">القيمة بالشيكل: <strong id="amount_ils_display_${currentIndex}">0.00 ILS</strong></small></div>
                    </div>
                    <div class="form-group"><label>تفاصيل العقد</label><textarea name="units[${currentIndex}][contract_details]" class="form-control" rows="1">${unitData.contract_details || ''}</textarea></div>
                </div>`;
            $('#units-sale-container').append(saleHtml);
            applyCleave($(`#sale_price_${currentIndex}`)[0], currentIndex);
            calculateILS(currentIndex);
            saleIndex++;
        }

        $('#add-unit-sale-btn').on('click', function() { addUnitSaleField(); });
        $(document).on('click', '.remove-sale-btn', function() {
            const item = $(this).closest('.unit-sale-item');
            delete cleaveInstances[item.data('index')];
            item.remove();
        });
        $(document).on('input change', '.sale-price-input, .exchange-rate-input, .currency-select', function() {
            calculateILS($(this).data('index'));
        });

        // استعادة البيانات القديمة في حال فشل التحقق
        const oldUnits = @json(old('units')) || [];
        if (oldUnits.length > 0) {
            oldUnits.forEach(unit => addUnitSaleField(unit));
        } else {
            addUnitSaleField(); // إضافة حقل واحد افتراضي
        }

        $('#client-form').on('submit', function() {
            $('.sale-price-input').each(function() {
                const index = $(this).data('index');
                if (cleaveInstances[index]) {
                    $(this).val(cleaveInstances[index].getRawValue());
                }
            });
            return true;
        });
    });
</script>
@endpush
