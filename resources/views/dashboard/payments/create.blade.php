@extends('layouts.container')
@section('title', 'تسجيل قيد يومي (دفعة/قبض)')

@push('styles')
{{-- مكتبة لتحسين قوائم الاختيار وجعلها قابلة للبحث --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* تعديل ارتفاع حقل Select2 ليتناسب مع تصميم Bootstrap */
    .select2-container .select2-selection--single {
        height: calc(1.5em + 1.3rem + 2px ) !important;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
    }
    /* تصميم صندوق تفاصيل الدفع */
    .payment-details-box {
        border: 1px solid #ebedf2;
        padding: 20px;
        border-radius: 6px;
        margin-top: 10px;
        background-color: #f9f9f9;
    }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-money-bill-wave text-success mr-2"></i>
            تسجيل دفعة/قبض (قيد يومي)
        </h3>
    </div>
    <form action="{{ route('dashboard.payments.store') }}" method="POST" id="payment-form">
        @csrf
        <div class="card-body">

            {{-- عرض رسائل الخطأ --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p><strong>يرجى تصحيح الأخطاء التالية:</strong></p>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- ================== 1. تحديد الكيان ================== --}}
            <h4 class="mb-5 text-primary">1. تحديد الكيان</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>الكيان المستهدف <span class="text-danger">*</span></label>
                    <select name="payable_type" id="payable_type" class="form-control" required>
                        <option value="">اختر نوع الكيان...</option>
                        <option value="Client">عميل</option>
                        <option value="Investor">مستثمر</option>
                        <option value="Subcontractor">مقاول / مورد</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label>اسم الكيان <span class="text-danger">*</span></label>
                    <select name="payable_id" id="payable_id" class="form-control" required disabled>
                        {{-- سيتم ملء هذه القائمة عبر AJAX --}}
                    </select>
                </div>
            </div>

            {{-- صندوق عرض بيانات الكيان المختار --}}
            <div id="payable-info" class="alert alert-custom alert-light-primary d-none p-5 mt-2">
                <div class="alert-icon"><i class="flaticon-information"></i></div>
                <div class="alert-text">
                    <div class="row">
                        <div class="col-md-4"><strong>الاسم:</strong> <span id="info-name"></span></div>
                        <div class="col-md-4"><strong>الهوية:</strong> <span id="info-id_number"></span></div>
                        <div class="col-md-4"><strong>الجوال:</strong> <span id="info-phone"></span></div>
                        <div class="col-md-4 mt-3"><strong>إجمالي المستحق:</strong> <span id="info-total_due" class="font-weight-bold"></span> ILS</div>
                        <div class="col-md-4 mt-3"><strong>إجمالي المدفوع:</strong> <span id="info-total_paid" class="font-weight-bold text-success"></span> ILS</div>
                        <div class="col-md-4 mt-3"><strong>الرصيد الحالي:</strong> <span id="info-remaining_balance" class="font-weight-bolder text-danger"></span> ILS</div>
                    </div>
                </div>
            </div>

            <hr class="my-10">

            {{-- ================== 2. تفاصيل الدفعة ================== --}}
            <h4 class="mb-5 text-primary">2. تفاصيل الدفعة</h4>
            <div class="row">
                <div class="col-md-3 form-group"><label>تاريخ الدفعة <span class="text-danger">*</span></label><input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required></div>
                <div class="col-md-3 form-group"><label>نوع الحركة <span class="text-danger">*</span></label><select name="type" class="form-control" required><option value="in">قبض (إيراد)</option><option value="out">صرف (مصروف)</option></select></div>
                <div class="col-md-3 form-group"><label>المبلغ <span class="text-danger">*</span></label><input type="text" name="amount" id="amount" class="form-control" required></div>
                <div class="col-md-3 form-group"><label>العملة <span class="text-danger">*</span></label><select name="currency" id="currency" class="form-control" required><option value="ILS">شيكل</option><option value="USD">دولار</option><option value="JOD">دينار</option></select></div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group d-none" id="exchange-rate-group">
                    <label>سعر الصرف مقابل الشيكل <span class="text-danger">*</span></label>
                    <input type="number" name="exchange_rate" id="exchange_rate" class="form-control" step="0.0001" value="1">
                    <small class="form-text text-muted">القيمة بالشيكل: <strong id="amount-ils-display">0.00 ILS</strong></small>
                </div>
            </div>

            <hr class="my-10">

            {{-- ================== 3. طريقة الدفع ================== --}}
            <h4 class="mb-5 text-primary">3. طريقة الدفع</h4>
            <div class="form-group">
                <label for="method">طريقة الدفع <span class="text-danger">*</span></label>
                <select name="method" id="method" class="form-control" required>
                    <option value="cash">نقدي</option>
                    <option value="bank_transfer">تحويل بنكي</option>
                    <option value="check">شيك</option>
                </select>
            </div>
            {{-- هذا الصندوق سيتم ملؤه بتفاصيل طريقة الدفع عبر JavaScript --}}
            <div id="payment-details-container" class="payment-details-box"></div>

            <div class="form-group mt-5">
                <label>ملاحظات على القيد</label>
                <textarea name="notes" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-success mr-2">حفظ القيد</button>
            <a href="{{ route('dashboard.payments.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script>
$(document ).ready(function() {
    // ====================================================================
    // 0. تهيئة المكتبات والمتغيرات العامة
    // ====================================================================
    const exchangeRates = {'USD': 3.75, 'JOD': 5.20, 'ILS': 1};
    const bankAccounts = @json($bankAccounts ?? []);
    let cleaveAmount = new Cleave('#amount', { numeral: true, numeralThousandsGroupStyle: 'thousand' });
    $('#payable_id').select2({ placeholder: "ابحث بالاسم أو الرقم التعريفي...", allowClear: true });

    // ====================================================================
    // 1. منطق جلب الكيانات عند تغيير النوع
    // ====================================================================
    $('#payable_type').on('change', function() {
        const type = $(this).val();
        const payableSelect = $('#payable_id');

        payableSelect.empty().val(null).trigger('change').prop('disabled', true);
        $('#payable-info').addClass('d-none');

        if (!type) return;

        $.ajax({
            url: "{{ route('dashboard.getPayables') }}",
            data: { type: type },
            success: function(data) {
                payableSelect.append('<option></option>'); // لإظهار الـ placeholder
                data.forEach(item => {
                    payableSelect.append(`<option value="${item.id}">${item.name} (${item.unique_id})</option>`);
                });
                payableSelect.prop('disabled', false);
            },
            error: function() {
                alert('حدث خطأ أثناء جلب البيانات.');
            }
        });
    });

    // ====================================================================
    // 2. منطق جلب تفاصيل الكيان عند اختياره
    // ====================================================================
    $('#payable_id').on('change', function() {
        const id = $(this).val();
        const type = $('#payable_type').val();
        const infoBox = $('#payable-info');

        if (!id) {
            infoBox.addClass('d-none');
            return;
        }

        $.ajax({
            url: "{{ route('dashboard.getPayableDetails') }}",
            data: { type: type, id: id },
            success: function(details) {
                const formatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                $('#info-name').text(details.name);
                $('#info-id_number').text(details.id_number || '-');
                $('#info-phone').text(details.phone || '-');
                $('#info-total_due').text(formatter.format(details.total_due));
                $('#info-total_paid').text(formatter.format(details.total_paid));
                $('#info-remaining_balance').text(formatter.format(details.remaining_balance));
                infoBox.removeClass('d-none');
            },
            error: function() {
                infoBox.addClass('d-none');
                alert('حدث خطأ أثناء جلب تفاصيل الكيان.');
            }
        });
    });

    // ====================================================================
    // 3. منطق حساب العملة
    // ====================================================================
    function calculateILS() {
        const amount = parseFloat(cleaveAmount.getRawValue()) || 0;
        const currency = $('#currency').val();
        const exchangeRateGroup = $('#exchange-rate-group');
        const exchangeRateInput = $('#exchange_rate');

        if (currency === 'ILS') {
            exchangeRateGroup.addClass('d-none');
            exchangeRateInput.val(1);
        } else {
            exchangeRateGroup.removeClass('d-none');
            if (exchangeRateInput.val() == 1 || exchangeRateInput.val() == "") {
                exchangeRateInput.val(exchangeRates[currency] || 1);
            }
        }
        const exchangeRate = parseFloat(exchangeRateInput.val()) || 1;
        const formatter = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        $('#amount-ils-display').text(formatter.format(amount * exchangeRate) + ' ILS');
    }
    $('#amount, #currency, #exchange_rate').on('input change', calculateILS);
    calculateILS();

    // ====================================================================
    // 4. منطق رسم تفاصيل طريقة الدفع
    // ====================================================================
    function renderPaymentDetails(method) {
        const container = $('#payment-details-container');
        container.empty();
        let html = '';

        if (method === 'cash') {
            html = `<h5 class="text-success">تفاصيل الدفع النقدي</h5><div class="row"><div class="col-md-6 form-group"><label>من سلم المبلغ <span class="text-danger">*</span></label><input type="text" name="delivered_by" class="form-control" required></div><div class="col-md-6 form-group"><label>من استلم المبلغ <span class="text-danger">*</span></label><input type="text" name="received_by" class="form-control" required></div></div>`;
        } else if (method === 'check') {
            html = `<h5 class="text-warning">تفاصيل الشيك</h5><div class="row"><div class="col-md-4 form-group"><label>رقم الشيك <span class="text-danger">*</span></label><input type="text" name="check_number" class="form-control" required></div><div class="col-md-4 form-group"><label>تاريخ الاستحقاق <span class="text-danger">*</span></label><input type="date" name="due_date" class="form-control" value="{{ now()->toDateString() }}" required></div><div class="col-md-4 form-group"><label>اسم مالك الشيك <span class="text-danger">*</span></label><input type="text" name="check_owner" class="form-control" required></div></div>`;
        } else if (method === 'bank_transfer') {
            let accountOptions = '<option value="">اختر حساب...</option>';
            bankAccounts.forEach(acc => { accountOptions += `<option value="${acc.id}">${acc.account_number} - ${acc.bank ? acc.bank.name : 'N/A'}</option>`; });
            html = `<h5 class="text-info">تفاصيل التحويل البنكي</h5><div class="row"><div class="col-md-6 form-group"><label>من حساب <span class="text-danger">*</span></label><select name="sender_bank_account_id" class="form-control" required>${accountOptions}</select></div><div class="col-md-6 form-group"><label>إلى حساب <span class="text-danger">*</span></label><select name="receiver_bank_account_id" class="form-control" required>${accountOptions}</select></div></div><div class="form-group"><label>مرجع التحويل</label><input type="text" name="transaction_reference" class="form-control"></div>`;
        }
        container.html(html);
    }
    $('#method').on('change', function() { renderPaymentDetails($(this).val()); });
    renderPaymentDetails($('#method').val());

    // ====================================================================
    // 5. منطق تنظيف حقل المبلغ قبل إرسال النموذج
    // ====================================================================
    $('#payment-form').on('submit', function() {
        const rawValue = cleaveAmount.getRawValue();
        // تعيين القيمة الخام إلى حقل الإدخال المخفي أو مباشرة
        $('#amount').val(rawValue);
        return true;
    });
});
</script>
@endpush
