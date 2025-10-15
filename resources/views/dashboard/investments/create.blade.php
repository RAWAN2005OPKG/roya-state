@extends('layouts.container')
@section('title', 'إضافة استثمار جديد')

@section('styles')
<style>
    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 16px;
        max-width: 900px;
        margin: 40px auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .btn-submit {
        background-color: #4f46e5;
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 20px;
        transition: background-color 0.3s;
    }

    .btn-submit:hover {
        background-color: #4338ca;
    }

    .form-errors {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 1.2rem;
        color: #4f46e5;
        margin-top: 20px;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #e5e7eb;
        grid-column: 1 / -1;
    }

    .form-section {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        background-color: #f9fafb;
    }

    .form-section-title {
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 5px;
        border-bottom: 2px solid transparent;
        display: inline-block;
    }

    .hidden {
        display: none !important;
    }

    .payment-methods-group label {
        margin-right: 15px;
        font-weight: 500;
    }

    .payment-methods-group input {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="form-container">
        <h2 style="font-size: 1.8rem; color: #4f46e5; margin-bottom: 25px;">إضافة استثمار جديد</h2>

        @if ($errors->any())
        <div class="form-errors">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
        @endif

        <form action="{{ route('dashboard.investments.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <h3 class="section-title">البيانات الأساسية</h3>

                <div class="form-group">
                    <label for="investor_id">المستثمر *</label>
                    <select id="investor_id" name="investor_id" required>
                        <option value="">-- اختر المستثمر --</option>
                        @foreach ($investors as $investor)
                            <option value="{{ $investor->id }}" {{ old('investor_id') == $investor->id ? 'selected' : '' }}>
                                {{ $investor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">تاريخ الاستثمار *</label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                  <div class="form-group">
                    <label for="project_id">المشروع *</label>
                    <select id="project_id" name="project_id" required>
                        <option value="project_id">-- اختر المشروع --</option>
                        @foreach ($projects as $projects)
                            <option value="{{ $projects->id }}" {{ old('projects') == $projects->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label for="type">نوع الاستثمار (شقة/أرض)</label>
                    <input type="text" id="type" name="type" value="{{ old('type') }}">
                </div>

                <div class="form-group">
                    <label for="amount">المبلغ *</label>
                    <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="currency">العملة *</label>
                    <select id="currency" name="currency" required>
                        <option value="شيكل" @selected(old('currency') == 'شيكل')>شيكل</option>
                        <option value="دولار" @selected(old('currency') == 'دولار')>دولار</option>
                        <option value="دينار" @selected(old('currency') == 'دينار')>دينار</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="share_percentage">نسبة الحصة (%)</label>
                    <input type="number" id="share_percentage" name="share_percentage" value="{{ old('share_percentage') }}" step="0.01">
                </div>

                <div class="form-group">
                    <label for="status">حالة الاستثمار</label>
                    <select id="status" name="status">
                        <option value="active" @selected(old('status') == 'active')>نشط</option>
                        <option value="completed" @selected(old('status') == 'completed')>مكتمل</option>
                        <option value="cancelled" @selected(old('status') == 'cancelled')>ملغي</option>
                    </select>
                </div>
            </div>
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


            <button type="submit" class="btn-submit">حفظ الاستثمار</button>
        </form>
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
