@extends('layouts.container')
@section('title', 'تعديل بيانات المستثمر: ' . $investor->name)

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .investment-item {
        background-color: #fff;
        border: 1px solid #e9ecef;
        border-right: 4px solid #FFA800;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05 );
    }
    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.3rem + 2px) !important;
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit text-warning mr-2"></i>
            تعديل بيانات المستثمر: {{ $investor->name }}
        </h3>
    </div>
    <form action="{{ route('dashboard.investors.update', $investor->id) }}" method="POST" id="investor-form">
        @csrf
        @method('PUT')
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>يرجى تصحيح الأخطاء التالية:</strong>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <h4 class="mb-5 text-primary">1. البيانات الأساسية للمستثمر</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>اسم المستثمر <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $investor->name) }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label>الشركة</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company', $investor->company) }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>رقم الهوية</label>
                    <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $investor->id_number) }}">
                </div>
                <div class="col-md-6 form-group">
                    <label>رقم الجوال</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $investor->phone) }}">
                </div>
            </div>
            <div class="form-group">
                <label>ملاحظات</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $investor->notes) }}</textarea>
            </div>

            <hr class="my-10">

            <h4 class="mb-5 text-primary">2. المشاريع المستثمر فيها</h4>
            <div id="investments-container">
                {{-- سيتم ملء حقول الاستثمار هنا بواسطة JavaScript --}}
            </div>
            <button type="button" id="add-investment-btn" class="btn btn-warning btn-sm mt-3">
                <i class="fas fa-plus"></i> إضافة استثمار جديد
            </button>
        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ التعديلات</button>
            <a href="{{ route('dashboard.investors.show', $investor->id) }}" class="btn btn-secondary">إلغاء</a>
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
        let investmentIndex = 0;
        const projectsList = @json($projects);
        const exchangeRates = { 'USD': 3.75, 'JOD': 5.20, 'ILS': 1 };
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
            const exchangeRateInput = $(`#exchange_rate_${index}`);

            if (currency === 'ILS') {
                exchangeRateInput.val(1).closest('.form-group').hide();
            } else {
                exchangeRateInput.closest('.form-group').show();
                // إذا لم يكن هناك قيمة مسبقة، ضع القيمة الافتراضية
                if (!exchangeRateInput.val() || parseFloat(exchangeRateInput.val()) === 1) {
                    exchangeRateInput.val(exchangeRates[currency] || 1);
                }
            }
            const rate = parseFloat(exchangeRateInput.val()) || 1;
            $(`#amount_ils_display_${index}`).text((amount * rate).toFixed(2) + ' ILS');
        }

        function addInvestmentField(investment = {}) {
            const currentIndex = investmentIndex;
            let projectOptions = '<option value="">اختر مشروع...</option>';
            projectsList.forEach(p => {
                // التحقق من أن المشروع المختار هو المشروع الحالي في الحلقة
                const isSelected = investment.project_id == p.id ? 'selected' : '';
                projectOptions += `<option value="${p.id}" ${isSelected}>${p.name}</option>`;
            });

            const investmentHtml = `
                <div class="investment-item" data-index="${currentIndex}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>تفاصيل الاستثمار #${currentIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-investment-btn"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>المشروع <span class="text-danger">*</span></label>
                            <select name="projects[${currentIndex}][project_id]" class="form-control project-select" required>${projectOptions}</select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>نسبة الاستثمار (%)</label>
                            <input type="number" name="projects[${currentIndex}][investment_percentage]" class="form-control" step="0.01" value="${investment.investment_percentage || ''}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>المبلغ المستثمر <span class="text-danger">*</span></label>
                            <input type="text" id="invested_amount_formatted_${currentIndex}" class="form-control" value="${investment.invested_amount || ''}" required>
                            <input type="hidden" name="projects[${currentIndex}][invested_amount]" id="invested_amount_${currentIndex}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>العملة <span class="text-danger">*</span></label>
                            <select name="projects[${currentIndex}][currency]" id="currency_${currentIndex}" class="form-control currency-select" data-index="${currentIndex}" required>
                                <option value="USD" ${investment.currency === 'USD' ? 'selected' : ''}>دولار (USD)</option>
                                <option value="JOD" ${investment.currency === 'JOD' ? 'selected' : ''}>دينار (JOD)</option>
                                <option value="ILS" ${investment.currency === 'ILS' ? 'selected' : ''}>شيكل (ILS)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>سعر الصرف</label>
                            <input type="number" name="projects[${currentIndex}][exchange_rate]" id="exchange_rate_${currentIndex}" class="form-control exchange-rate" data-index="${currentIndex}" step="0.0001" value="${investment.exchange_rate || '1'}">
                            <small class="form-text text-muted">القيمة بالشيكل: <strong id="amount_ils_display_${currentIndex}">0.00 ILS</strong></small>
                        </div>
                    </div>
                </div>`;
            $('#investments-container').append(investmentHtml);

            // تفعيل Select2 على الحقل الجديد
            $(`select[name="projects[${currentIndex}][project_id]"]`).select2({ placeholder: "اختر مشروعاً..." });

            applyCleave($(`#invested_amount_formatted_${currentIndex}`)[0], currentIndex);
            calculateILS(currentIndex);
            investmentIndex++;
        }

        $('#add-investment-btn').on('click', () => addInvestmentField());
        $(document).on('click', '.remove-investment-btn', function() {
            if (confirm('هل أنت متأكد من حذف هذا الاستثمار؟')) {
                $(this).closest('.investment-item').remove();
            }
        });

        $(document).on('input change', '.currency-select, .exchange-rate', function() {
            calculateILS($(this).closest('.investment-item').data('index'));
        });
        $(document).on('input', 'input[id^="invested_amount_formatted_"]', function() {
            calculateILS($(this).closest('.investment-item').data('index'));
        });

        // --- [الأهم] جلب البيانات الحالية أو القديمة ---
        const oldProjects = @json(old('projects'));
        const currentInvestments = @json($investor->projects->map(function($p) {
            return [
                'project_id' => $p->id,
                'investment_percentage' => $p->pivot->investment_percentage,
                'invested_amount' => $p->pivot->invested_amount,
                'currency' => $p->pivot->currency,
                'exchange_rate' => $p->pivot->exchange_rate,
            ];
        }));

        // إذا كان هناك بيانات قديمة (بسبب خطأ تحقق)، استخدمها. وإلا، استخدم البيانات الحالية من قاعدة البيانات.
        const dataToLoad = oldProjects || currentInvestments;

        if (dataToLoad && dataToLoad.length > 0) {
            dataToLoad.forEach(inv => addInvestmentField(inv));
        } else {
            addInvestmentField(); // إضافة حقل فارغ إذا لم يكن هناك أي استثمارات
        }

        $('#investor-form').on('submit', function() {
            $('.investment-item').each(function() {
                const index = $(this).data('index');
                const cleave = cleaveInstances[index];
                if (cleave) {
                    $(`#invested_amount_${index}`).val(cleave.getRawValue());
                }
            });
            return true;
        });
    });
</script>
@endpush
