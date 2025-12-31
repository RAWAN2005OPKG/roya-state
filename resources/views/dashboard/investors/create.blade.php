@extends('layouts.container')
@section('title', 'إضافة مستثمر جديد')

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-handshake text-warning mr-2"></i> إضافة مستثمر جديد</h3></div>
    <form action="{{ route('dashboard.investors.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <h4 class="mb-5 text-primary">1. بيانات المستثمر</h4>
            <div class="row">
                <div class="col-md-6 form-group"><label>اسم المستثمر <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                <div class="col-md-6 form-group"><label>الشركة</label><input type="text" name="company" class="form-control" value="{{ old('company') }}"></div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group"><label>رقم الهوية</label><input type="text" name="id_number" class="form-control" value="{{ old('id_number') }}"></div>
                <div class="col-md-6 form-group"><label>رقم الجوال</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            </div>
            <div class="form-group"><label>ملاحظات</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>

            <hr class="my-10">

            <h4 class="mb-5 text-primary">2. المشاريع المستثمر فيها</h4>
            <div id="investments-container"></div>
            <button type="button" id="add-investment-btn" class="btn btn-warning btn-sm mt-3"><i class="fas fa-plus"></i> إضافة استثمار</button>
        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ المستثمر</button>
            <a href="{{ route('dashboard.investors.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document ).ready(function() {
        let investmentIndex = 0;
        const projectsList = @json($projects);

        // أسعار صرف وهمية (يمكنك استبدالها بـ API حقيقي لاحقاً)
        const exchangeRates = {
            'USD': 3.75,
            'JOD': 5.20,
            'ILS': 1
        };

        function calculateILS(index) {
            const amount = parseFloat($(`#invested_amount_${index}`).val()) || 0;
            const currency = $(`#currency_${index}`).val();
            const exchangeRateGroup = $(`#exchange_rate_group_${index}`);
            const exchangeRateInput = $(`#exchange_rate_${index}`);

            if (currency === 'ILS') {
                exchangeRateGroup.hide();
                exchangeRateInput.val(1);
            } else {
                exchangeRateGroup.show();
                // إذا لم يغير المستخدم سعر الصرف، نضع السعر الافتراضي
                if(exchangeRateInput.val() == 1) {
                    exchangeRateInput.val(exchangeRates[currency] || 1);
                }
            }

            const exchangeRate = parseFloat(exchangeRateInput.val()) || 1;
            const amountILS = (amount * exchangeRate).toFixed(2);
            $(`#amount_ils_display_${index}`).text(amountILS + ' ILS');
        }

        function addInvestmentField() {
            const currentIndex = investmentIndex;
            let projectOptions = '<option value="">اختر مشروع...</option>';
            projectsList.forEach(project => {
                projectOptions += `<option value="${project.id}">${project.name} (${project.location})</option>`;
            });

            const investmentHtml = `
                <div class="border p-4 mb-4 rounded shadow-sm" style="border-right: 4px solid #FFA800;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning">تفاصيل الاستثمار #${currentIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-investment-btn"><i class="fas fa-trash"></i> حذف</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group"><label>المشروع <span class="text-danger">*</span></label><select name="projects[${currentIndex}][project_id]" class="form-control" required>${projectOptions}</select></div>
                        <div class="col-md-6 form-group"><label>نسبة الاستثمار (%)</label><input type="number" name="projects[${currentIndex}][investment_percentage]" class="form-control" step="0.01" min="0" max="100"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>المبلغ المستثمر <span class="text-danger">*</span></label>
                            <input type="number" name="projects[${currentIndex}][invested_amount]" id="invested_amount_${currentIndex}" data-index="${currentIndex}" class="form-control investment-amount" step="0.01" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>العملة <span class="text-danger">*</span></label>
                            <select name="projects[${currentIndex}][currency]" id="currency_${currentIndex}" data-index="${currentIndex}" class="form-control currency-select" required>
                                <option value="ILS">شيكل (ILS)</option>
                                <option value="USD">دولار (USD)</option>
                                <option value="JOD">دينار (JOD)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group" id="exchange_rate_group_${currentIndex}">
                            <label>سعر الصرف مقابل الشيكل <span class="text-danger">*</span></label>
                            <input type="number" name="projects[${currentIndex}][exchange_rate]" id="exchange_rate_${currentIndex}" data-index="${currentIndex}" class="form-control exchange-rate" step="0.0001" value="1">
                            <small class="form-text text-muted">القيمة بالشيكل: <strong id="amount_ils_display_${currentIndex}">0.00 ILS</strong></small>
                        </div>
                    </div>
                    <div class="form-group"><label>ملاحظات</label><textarea name="projects[${currentIndex}][notes]" class="form-control" rows="1"></textarea></div>
                </div>`;
            $('#investments-container').append(investmentHtml);
            calculateILS(currentIndex); // قم بالحساب عند الإضافة
            investmentIndex++;
        }

        $('#add-investment-btn').on('click', addInvestmentField);
        $(document).on('click', '.remove-investment-btn', function() { $(this).closest('.border').remove(); });

        // عند تغيير المبلغ أو العملة أو سعر الصرف، أعد الحساب
        $(document).on('input change', '.investment-amount, .currency-select, .exchange-rate', function() {
            const index = $(this).data('index');
            calculateILS(index);
        });

        addInvestmentField(); // إضافة حقل واحد عند تحميل الصفحة
    });
</script>
@endpush
