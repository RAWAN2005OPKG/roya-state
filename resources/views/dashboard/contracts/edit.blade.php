@extends('layouts.container')
@section('title', 'تعديل العقد')

@section('styles')
    <style>
        .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e9ecef; }
        .form-section-title { font-size: 1.3rem; color: #4f46e5; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4f46e5; }
        .hidden { display: none; }
        .payment-methods-group { display: flex; gap: 25px; align-items: center; flex-wrap: wrap; padding: 10px 0; }
        .payment-methods-group label { display: flex; align-items: center; gap: 8px; font-size: 1.1rem; cursor: pointer; }
        .payment-methods-group input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
    </style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 1100px; margin: auto;">
        <div class="card-header">
            <h3 class="card-title">تعديل العقد رقم: {{ $contract->contract_id }}</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>خطأ!</strong> يرجى مراجعة الحقول التالية:


                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <form action="{{ route('dashboard.contracts.update', $contract->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h4 class="form-section-title">1. معلومات العقد الأساسية</h4>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>رقم العقد *</label><input type="text" name="contract_id" class="form-control" value="{{ old('contract_id', $contract->contract_id) }}" required></div>
                        <div class="col-md-4 form-group mb-3"><label>تاريخ التوقيع *</label><input type="date" name="signing_date" class="form-control" value="{{ old('signing_date', $contract->signing_date->format('Y-m-d')) }}" required></div>
                        <div class="col-md-4 form-group mb-3">
                            <label>الحالة *</label>
                            <select name="status" class="form-control" required>
                                <option value="active" @selected(old('status', $contract->status) == 'active')>نشط</option>
                                <option value="draft" @selected(old('status', $contract->status) == 'draft')>مسودة</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">2. بيانات العميل</h4>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>اسم العميل *</label><input type="text" name="client_name" class="form-control" value="{{ old('client_name', $contract->client_name) }}" required></div>
                        <div class="col-md-4 form-group mb-3"><label>رقم الهوية</label><input type="text" name="client_id_number" class="form-control" value="{{ old('client_id_number', $contract->client_id_number) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>رقم الجوال *</label><input type="text" name="client_phone" class="form-control" value="{{ old('client_phone', $contract->client_phone) }}" required></div>
                        <div class="col-md-4 form-group mb-3"><label>جوال بديل</label><input type="text" name="client_alt_phone" class="form-control" value="{{ old('client_alt_phone', $contract->client_alt_phone) }}"></div>
                        <div class="col-md-8 form-group mb-3"><label>البريد الإلكتروني</label><input type="email" name="client_email" class="form-control" value="{{ old('client_email', $contract->client_email) }}"></div>
                    </div>
                </div>

                <div class="form-section">
                    <h4 class="form-section-title">3. تفاصيل العقار والاستثمار</h4>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>نوع العقار</label><input type="text" name="property_type" class="form-control" value="{{ old('property_type', $contract->property_type) }}"></div>
                        <div class="col-md-8 form-group mb-3"><label>موقع العقار</label><input type="text" name="property_location" class="form-control" value="{{ old('property_location', $contract->property_location) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>قيمة الاستثمار *</label><input type="number" name="investment_amount" class="form-control" value="{{ old('investment_amount', $contract->investment_amount) }}" step="0.01" required></div>
                        <div class="col-md-4 form-group mb-3"><label>سعر الشقة</label><input type="number" name="apartment_price" class="form-control" value="{{ old('apartment_price', $contract->apartment_price) }}" step="0.01"></div>
                        <div class="col-md-4 form-group mb-3"><label>مدة العقد (بالأشهر)</label><input type="number" name="duration_months" class="form-control" value="{{ old('duration_months', $contract->duration_months) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>نسبة الربح (%)</label><input type="number" name="profit_percentage" class="form-control" value="{{ old('profit_percentage', $contract->profit_percentage) }}" step="0.01"></div>
                    </div>
                </div>

               <div class="form-section">
                    <h4 class="form-section-title">4. تفاصيل الدفع</h4>
                    <div class="row">
                        <div class="col-12 form-group mb-3">
                            <label>طرق الدفع المستخدمة *</label>
                            <div class="payment-methods-group">
                                {{-- هذا الكود يفترض أن payment_method هو نص مثل "cash,check" --}}
                                @php
                                    $paymentMethods = explode(',', old('payment_method', $contract->payment_method));
                                @endphp
                                <label>
                                    <input type="checkbox" name="payment_methods[]" value="cash" id="payment_cash" @if(in_array('cash', $paymentMethods)) checked @endif>
                                    كاش
                                </label>
                                <label>
                                    <input type="checkbox" name="payment_methods[]" value="check" id="payment_check" @if(in_array('check', $paymentMethods)) checked @endif>
                                    شيك
                                </label>
                                <label>
                                    <input type="checkbox" name="payment_methods[]" value="bank_transaction" id="payment_bank" @if(in_array('bank_transaction', $paymentMethods)) checked @endif>
                                    تحويل بنكي
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
@extends('layouts.container')
@section('title', 'تعديل العقد')

@section('styles')
    {{-- نفس أنماط CSS السابقة --}}
    <style>
        .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e9ecef; }
        .form-section-title { font-size: 1.3rem; color: #4f46e5; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4f46e5; }
        .hidden { display: none; }
        .payment-methods-group { display: flex; gap: 25px; align-items: center; flex-wrap: wrap; padding: 10px 0; }
        .payment-methods-group label { display: flex; align-items: center; gap: 8px; font-size: 1.1rem; cursor: pointer; }
        .payment-methods-group input[type="checkbox"] { width: 20px; height: 20px; cursor: pointer; }
    </style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 1100px; margin: auto;">
        <div class="card-header">
            <h3 class="card-title">تعديل العقد رقم: {{ $contract->contract_id }}</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <form action="{{ route('dashboard.contracts.update', $contract->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- ... (الأقسام 1، 2، 3 تبقى كما هي) ... --}}
                <div class="form-section">
                    <h4 class="form-section-title">1. معلومات العقد الأساسية</h4>
                    {{-- ... حقول معلومات العقد ... --}}
                </div>
                <div class="form-section">
                    <h4 class="form-section-title">2. بيانات العميل</h4>
                    {{-- ... حقول بيانات العميل ... --}}
                </div>
                <div class="form-section">
                    <h4 class="form-section-title">3. تفاصيل العقار والاستثمار</h4>
                    {{-- ... حقول تفاصيل العقار ... --}}
                </div>


                {{-- القسم الرابع: تفاصيل الدفع (تم تعديله ليشمل الحقول الجديدة) --}}
                <div class="form-section">
                    <h4 class="form-section-title">4. تفاصيل الدفع</h4>
                    <div class="row">
                        {{-- طرق الدفع --}}
                        <div class="col-12 form-group mb-4">
                            <label>طرق الدفع المستخدمة *</label>
                            <div class="payment-methods-group">
                                @php
                                    $paymentMethods = explode(',', old('payment_method', $contract->payment_method));
                                @endphp
                                <label>
                                    <input type="checkbox" name="payment_methods[]" value="cash" id="payment_cash" @if(in_array('cash', $paymentMethods)) checked @endif> كاش
                                </label>
                                <label>
                                    <input type="checkbox" name="payment_methods[]" value="check" id="payment_check" @if(in_array('check', $paymentMethods)) checked @endif> شيك
                                </label>
                                <label>
                                    <input type="checkbox" name="payment_methods[]" value="bank_transaction" id="payment_bank" @if(in_array('bank_transaction', $paymentMethods)) checked @endif> تحويل بنكي
                                </label>
                            </div>
                        </div>

                        {{-- هنا تم وضع الحقول التي طلبتها --}}
                        <div class="col-md-6 form-group mb-3">
                            <label>دفعة أولى</label>
                            <input type="number" name="down_payment_initial" class="form-control" value="{{ old('down_payment_initial', $contract->down_payment_initial) }}" step="0.01">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>دفعة أخرى</label>
                            <input type="number" name="down_payment_other" class="form-control" value="{{ old('down_payment_other', $contract->down_payment_other) }}" step="0.01">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>تاريخ أول دفعة</label>
                            <input type="date" name="first_payment_date" class="form-control" value="{{ old('first_payment_date', $contract->first_payment_date ? $contract->first_payment_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>المبلغ المتبقي</label>
                            <input type="number" name="remaining_amount" class="form-control" value="{{ old('remaining_amount', $contract->remaining_amount) }}" step="0.01">
                        </div>
                    </div>
                </div>
                <div id="cash-details" class="form-section hidden">
                    <h5 class="form-section-title" style="font-size: 1.1rem; border-color: #10b981;">تفاصيل الدفع النقدي</h5>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>من استلم المبلغ</label><input type="text" name="cash_receiver" class="form-control" value="{{ old('cash_receiver', $contract->cash_receiver) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>وظيفة المستلم</label><input type="text" name="cash_receiver_job" class="form-control" value="{{ old('cash_receiver_job', $contract->cash_receiver_job) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>تاريخ الاستلام</label><input type="date" name="cash_receipt_date" class="form-control" value="{{ old('cash_receipt_date', $contract->cash_receipt_date ? $contract->cash_receipt_date->format('Y-m-d') : '') }}"></div>
                    </div>
                </div>

                <div id="bank-details" class="form-section hidden">
                    <h5 class="form-section-title" style="font-size: 1.1rem; border-color: #3b82f6;">تفاصيل التحويل البنكي</h5>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>البنك المرسل</label><input type="text" name="sender_bank" class="form-control" value="{{ old('sender_bank', $contract->sender_bank) }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>البنك المستقبل</label><input type="text" name="receiver_bank" class="form-control" value="{{ old('receiver_bank', $contract->receiver_bank) }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>رقم مرجع التحويل</label><input type="text" name="transaction_reference" class="form-control" value="{{ old('transaction_reference', $contract->transaction_reference) }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>تاريخ التحويل</label><input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $contract->transaction_date ? $contract->transaction_date->format('Y-m-d') : '') }}"></div>
                    </div>
                </div>

                <div id="check-details" class="form-section hidden">
                    <h5 class="form-section-title" style="font-size: 1.1rem; border-color: #f59e0b;">تفاصيل الشيك</h5>
                    <div class="row">
                        <div class="col-md-4 form-group mb-3"><label>رقم الشيك</label><input type="text" name="check_number" class="form-control" value="{{ old('check_number', $contract->check_number) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>اسم مالك الشيك</label><input type="text" name="check_owner" class="form-control" value="{{ old('check_owner', $contract->check_owner) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>البنك المسحوب عليه</label><input type="text" name="check_bank" class="form-control" value="{{ old('check_bank', $contract->check_bank) }}"></div>
                        <div class="col-md-4 form-group mb-3"><label>تاريخ الاستحقاق</label><input type="date" name="check_due_date" class="form-control" value="{{ old('check_due_date', $contract->check_due_date ? $contract->check_due_date->format('Y-m-d') : '') }}"></div>
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
            const cashCheckbox = document.getElementById('payment_cash');
            const bankCheckbox = document.getElementById('payment_bank');
            const checkCheckbox = document.getElementById('payment_check');

            const cashDetails = document.getElementById('cash-details');
            const bankDetails = document.getElementById('bank-details');
            const checkDetails = document.getElementById('check-details');

            function togglePaymentDetails() {
                cashDetails.classList.toggle('hidden', !cashCheckbox.checked);
                bankDetails.classList.toggle('hidden', !bankCheckbox.checked);
                checkDetails.classList.toggle('hidden', !checkCheckbox.checked);
            }

            cashCheckbox.addEventListener('change', togglePaymentDetails);
            bankCheckbox.addEventListener('change', togglePaymentDetails);
            checkCheckbox.addEventListener('change', togglePaymentDetails);

            // استدعاء عند التحميل لإظهار الأقسام الصحيحة بناءً على البيانات المحفوظة
            togglePaymentDetails();
        });
    </script>
@endsection