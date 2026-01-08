@extends('layouts.container')
@section('title', 'إضافة عميل جديد')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .unit-sale-item { border-right: 4px solid #1BC5BD; padding-right: 15px; }
    .select2-container .select2-selection--single { height: calc(1.5em + 1.3rem + 2px ) !important; display: flex; align-items: center; }
    .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; background-color: #f8f9fa; padding: 1rem; border-radius: 8px; margin-top: 1rem; }
    .details-grid div { font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-user-plus text-primary mr-2"></i> إضافة عميل جديد</h3></div>
    <form action="{{ route('dashboard.clients.store') }}" method="POST" id="client-form">
        @csrf
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            <h4 class="mb-5 text-primary">1. بيانات العميل</h4>
            <div class="row">
                <div class="col-md-6 form-group"><label>اسم العميل <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-md-6 form-group"><label>رقم الجوال</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group"><label>رقم الهوية</label><input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}"></div>
                <div class="col-md-6 form-group"><label>العنوان</label><input type="text" name="address" class="form-control" value="{{ old('address') }}"></div>
            </div>

            <hr class="my-10">

            <h4 class="mb-5 text-primary">2. الوحدات المشتراة</h4>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document ).ready(function() {
        let saleIndex = 0;
        const availableUnits = @json($availableUnits);
        let cleaveInstances = {};

        function applyCleave(selector, index) {
            cleaveInstances[index] = new Cleave(selector, { numeral: true, numeralThousandsGroupStyle: 'thousand' });
        }

        function calculateILS(index) {
            const cleave = cleaveInstances[index];
            if (!cleave) return;
            const amount = parseFloat(cleave.getRawValue()) || 0;
            const currency = $(`#currency_${index}`).val();
            const exchangeRateInput = $(`#exchange_rate_${index}`);
            let exchangeRate = parseFloat(exchangeRateInput.val()) || 1;
            if (currency === 'ILS') { exchangeRateInput.val(1).closest('.form-group').hide(); exchangeRate = 1; }
            else { exchangeRateInput.closest('.form-group').show(); }
            $(`#amount_ils_display_${index}`).text((amount * exchangeRate).toFixed(2) + ' ILS');
        }

        // --- [الجديد] دالة لعرض تفاصيل الوحدة المختارة ---
        function showUnitDetails(selectElement) {
            const selectedUnitId = $(selectElement).val();
            const detailsContainer = $(selectElement).closest('.unit-sale-item').find('.unit-details-display');
            if (selectedUnitId) {
                const unit = availableUnits.find(u => u.id == selectedUnitId);
                if (unit) {
                    const detailsHtml = `
                        <div><strong>رقم الشقة:</strong> ${unit.unit_number}</div>
                        <div><strong>الطابق:</strong> ${unit.floor || 'N/A'}</div>
                        <div><strong>موقف سيارة:</strong> ${unit.has_parking ? 'نعم' : 'لا'}</div>
                        <div><strong>التشطيب:</strong> ${unit.finish_type === 'finished' ? 'مشطب' : 'عظم'}</div>
                    `;
                    detailsContainer.html(detailsHtml).show();
                }
            } else {
                detailsContainer.hide().empty();
            }
        }

        function addUnitSaleField(unitData = {}) {
            const currentIndex = saleIndex;
            let unitOptions = '<option value="">اختر وحدة...</option>';
            availableUnits.forEach(unit => {
                const isSelected = unit.id == unitData.unit_id ? 'selected' : '';
                unitOptions += `<option value="${unit.id}" ${isSelected}>[${unit.project.name}] - ${unit.unit_number}</option>`;
            });

            const saleHtml = `
                <div class="unit-sale-item border p-4 mb-4 rounded shadow-sm" data-index="${currentIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>تفاصيل عملية البيع #${currentIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-sale-btn"><i class="fas fa-trash"></i> حذف</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>الوحدة المشتراة <span class="text-danger">*</span></label>
                            <select name="units[${currentIndex}][unit_id]" class="form-control unit-select" required>${unitOptions}</select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>تاريخ البيع <span class="text-danger">*</span></label>
                            <input type="date" name="units[${currentIndex}][sale_date]" class="form-control" value="${unitData.sale_date || new Date().toISOString().slice(0, 10)}" required>
                        </div>
                    </div>

                    {{-- [الجديد] حاوية لعرض تفاصيل الوحدة --}}
                    <div class="details-grid unit-details-display" style="display: none;"></div>

                    <div class="row mt-3">
                        <div class="col-md-4 form-group">
                            <label>مبلغ البيع <span class="text-danger">*</span></label>
                            <input type="text" id="sale_price_formatted_${currentIndex}" class="form-control" value="${unitData.sale_price || ''}" required>
                            <input type="hidden" name="units[${currentIndex}][sale_price]" id="sale_price_${currentIndex}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>العملة <span class="text-danger">*</span></label>
                            <select name="units[${currentIndex}][currency]" id="currency_${currentIndex}" class="form-control currency-select" data-index="${currentIndex}" required>
                                <option value="USD" ${unitData.currency === 'USD' ? 'selected' : ''}>دولار (USD)</option>
                                <option value="JOD" ${unitData.currency === 'JOD' ? 'selected' : ''}>دينار (JOD)</option>
                                <option value="ILS" ${unitData.currency === 'ILS' ? 'selected' : ''}>شيكل (ILS)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>سعر الصرف <span class="text-danger">*</span></label>
                            <input type="number" name="units[${currentIndex}][exchange_rate]" id="exchange_rate_${currentIndex}" class="form-control exchange-rate-input" data-index="${currentIndex}" step="0.01" value="${unitData.exchange_rate || '3.75'}">
                            <small class="form-text text-muted">القيمة بالشيكل: <strong id="amount_ils_display_${currentIndex}">0.00 ILS</strong></small>
                        </div>
                    </div>
                </div>`;
            $('#units-sale-container').append(saleHtml);

            const newSelect = $(`select[name="units[${currentIndex}][unit_id]"]`);
            newSelect.select2({ placeholder: "اختر وحدة أو ابحث عنها..." });

            applyCleave($(`#sale_price_formatted_${currentIndex}`)[0], currentIndex);
            calculateILS(currentIndex);
            showUnitDetails(newSelect[0]); // عرض التفاصيل للبيانات القديمة إن وجدت
            saleIndex++;
        }

        $('#add-unit-sale-btn').on('click', () => addUnitSaleField());
        $(document).on('click', '.remove-sale-btn', function() { $(this).closest('.unit-sale-item').remove(); });

        // ربط الأحداث
        $(document).on('input change', '.exchange-rate-input, .currency-select', function() { calculateILS($(this).closest('.unit-sale-item').data('index')); });
        $(document).on('input', '.sale-price-input-formatted', function() { calculateILS($(this).closest('.unit-sale-item').data('index')); });
        $(document).on('change', '.unit-select', function() { showUnitDetails(this); });

        // استعادة البيانات القديمة
        const oldUnits = @json(old('units')) || [];
        oldUnits.length > 0 ? oldUnits.forEach(u => addUnitSaleField(u)) : addUnitSaleField();

        // معالجة الإرسال
        $('#client-form').on('submit', function() {
            $('.unit-sale-item').each(function() {
                const index = $(this).data('index');
                if (cleaveInstances[index]) { $(`#sale_price_${index}`).val(cleaveInstances[index].getRawValue()); }
            });
            return true;
        });
    });
</script>
@endpush
