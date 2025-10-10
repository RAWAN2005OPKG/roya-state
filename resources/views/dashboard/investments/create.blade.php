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
                    <label for="project">المشروع *</label>
                    <input type="text" id="project" name="project" value="{{ old('project') }}" required>
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
                <h5 class="form-section-title" style="border-color: #4f46e5;">طرق الدفع</h5>
                <div class="payment-methods-group">
                    <label><input type="checkbox" name="payment_methods[]" value="cash" id="payment_cash" @if(is_array(old('payment_methods')) && in_array('cash', old('payment_methods'))) checked @endif> كاش</label>
                    <label><input type="checkbox" name="payment_methods[]" value="check" id="payment_check" @if(is_array(old('payment_methods')) && in_array('check', old('payment_methods'))) checked @endif> شيك</label>
                    <label><input type="checkbox" name="payment_methods[]" value="bank_transaction" id="payment_bank" @if(is_array(old('payment_methods')) && in_array('bank_transaction', old('payment_methods'))) checked @endif> تحويل بنكي</label>
                </div>
            </div>

            <div class="form-section">
                <h5 class="form-section-title" style="border-color: #4f46e5;">الدفعات</h5>
                <div class="form-grid">
                    <div class="form-group"><label>دفعة أولى</label><input type="number" name="down_payment_initial" value="{{ old('down_payment_initial') }}" step="0.01"></div>
                    <div class="form-group"><label>دفعة أخرى</label><input type="number" name="down_payment_other" value="{{ old('down_payment_other') }}" step="0.01"></div>
                    <div class="form-group"><label>تاريخ أول دفعة</label><input type="date" name="first_payment_date" value="{{ old('first_payment_date') }}"></div>
                    <div class="form-group"><label>المبلغ المتبقي</label><input type="number" name="remaining_amount" value="{{ old('remaining_amount') }}" step="0.01"></div>
                </div>
            </div>

            {{-- تفاصيل الدفع النقدي --}}
            <div id="cash-details" class="form-section hidden">
                <h5 class="form-section-title" style="border-color: #10b981;">تفاصيل الدفع النقدي</h5>
                <div class="form-grid">
                    <div class="form-group"><label>من استلم المبلغ</label><input type="text" name="cash_receiver" value="{{ old('cash_receiver') }}"></div>
                    <div class="form-group"><label>وظيفة المستلم</label><input type="text" name="cash_receiver_job" value="{{ old('cash_receiver_job') }}"></div>
                    <div class="form-group"><label>تاريخ الاستلام</label><input type="date" name="cash_receipt_date" value="{{ old('cash_receipt_date') }}"></div>
                </div>
            </div>

            {{-- تفاصيل التحويل البنكي --}}
            <div id="bank-details" class="form-section hidden">
                <h5 class="form-section-title" style="border-color: #3b82f6;">تفاصيل التحويل البنكي</h5>
                <div class="form-grid">
                    <div class="form-group"><label>البنك المرسل</label><input type="text" name="sender_bank" value="{{ old('sender_bank') }}"></div>
                    <div class="form-group"><label>البنك المستقبل</label><input type="text" name="receiver_bank" value="{{ old('receiver_bank') }}"></div>
                    <div class="form-group"><label>رقم مرجع التحويل</label><input type="text" name="transaction_reference" value="{{ old('transaction_reference') }}"></div>
                    <div class="form-group"><label>تاريخ التحويل</label><input type="date" name="transaction_date" value="{{ old('transaction_date') }}"></div>
                </div>
            </div>

            {{-- تفاصيل الشيك --}}
            <div id="check-details" class="form-section hidden">
                <h5 class="form-section-title" style="border-color: #f59e0b;">تفاصيل الشيك</h5>
                <div class="form-grid">
                    <div class="form-group"><label>رقم الشيك</label><input type="text" name="check_number" value="{{ old('check_number') }}"></div>
                    <div class="form-group"><label>اسم مالك الشيك</label><input type="text" name="check_owner" value="{{ old('check_owner') }}"></div>
                    <div class="form-group"><label>البنك المسحوب عليه</label><input type="text" name="check_bank" value="{{ old('check_bank') }}"></div>
                    <div class="form-group"><label>تاريخ الاستحقاق</label><input type="date" name="check_due_date" value="{{ old('check_due_date') }}"></div>
                </div>
            </div>

            <button type="submit" class="btn-submit">حفظ الاستثمار</button>
        </form>
    </div>
</main>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cashCheckbox = document.getElementById('payment_cash');
        const bankCheckbox = document.getElementById('payment_bank');
        const checkCheckbox = document.getElementById('payment_check');

        const cashDetails = document.getElementById('cash-details');
        const bankDetails = document.getElementById('bank-details');
        const checkDetails = document.getElementById('check-details');

        const togglePaymentDetails = () => {
            cashDetails.classList.toggle('hidden', !cashCheckbox.checked);
            bankDetails.classList.toggle('hidden', !bankCheckbox.checked);
            checkDetails.classList.toggle('hidden', !checkCheckbox.checked);
        };

        [cashCheckbox, bankCheckbox, checkCheckbox].forEach(checkbox => {
            checkbox.addEventListener('change', togglePaymentDetails);
        });

        togglePaymentDetails(); // لتفعيل الحالة عند تحميل الصفحة
    });
</script>
@endsection
