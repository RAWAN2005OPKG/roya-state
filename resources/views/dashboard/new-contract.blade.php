@extends('layouts.container')
@section('title', 'إنشاء عقد استثماري جديد')

@section('styles')

<style>

    :root {
        --primary-color: #00aaff;
        --dark-bg-1: #ffffff;
        --dark-bg-2: #f8f9fa;
        --text-color: #333333;
        --text-muted: #666666;
        --border-color: #dee2e6;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', sans-serif;
    }

    body {
        background-color: var(--dark-bg-1);
        color: var(--text-color);
            }

    .main-content {
        max-width: 1100px;
        margin: 40px auto;
        padding: 20px;
    }

    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .page-header h1 {
        font-size: 2.5rem;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .page-header p {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    .form-container {
        background-color: var(--dark-bg-2);
        padding: 40px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
        display: inline-block;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--text-color);
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 12px;
        background-color: var(--dark-bg-1);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 170, 255, 0.1);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-submit {
        background: linear-gradient(45deg, var(--primary-color), #007bff);
        color: #fff;
        padding: 15px 35px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0, 170, 255, 0.2);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 170, 255, 0.3);
    }

    .btn-secondary {
        background-color: var(--border-color);
        color: var(--text-color);
        padding: 15px 35px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background-color: #e9ecef;
        text-decoration: none;
        color: var(--text-color);
    }

        /* الأقسام الديناميكية المحسنة */
    .dynamic-section {
        display: none;
        margin-top: 25px;
        padding: 25px;
        background-color: var(--dark-bg-1);
        border-radius: 8px;
        border: 1px solid var(--border-color);
        border-left: 4px solid var(--primary-color);
    }

    .dynamic-section.show {
        display: block;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-subtitle {
        font-size: 1.2rem; /* تم تعديل الحجم ليكون أكثر وضوحًا */
        color: var(--primary-color);
        margin-bottom: 20px;
        font-weight: 600;
    }

    .required-label::after { /* تم تغيير اسم الكلاس لتجنب التعارض مع .required في CSS الأصلي */
        content: ' *';
        color: var(--danger-color);
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

        .alert-error {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    @media (max-width: 768px) {
        .main-content {
            margin: 20px auto;
            padding: 15px;
        }

        .form-container {
            padding: 25px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-submit, .btn-secondary {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection

@section('content')

    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-file-signature"></i> إنشاء عقد استثماري للعقارات</h1>
            <p>أدخل كافة التفاصيل لتوثيق عقد استثماري جديد بشكل كامل ودقيق.</p>
        </div>

        <div class="form-container">
            <form id="contractForm">
                <!-- بيانات العقد -->
                <div class="form-section">
                    <h2 class="section-title">بيانات العقد</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contract_id" class="required-label">رقم العقد</label>
                            <input type="text" id="contract_id" name="contract_id" value="{{ old('contract_id') }}" required>
                            @error('contract_id')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="signingDate">تاريخ توقيع العقد <span class="required">*</span></label>
                            <input type="date" id="signingDate" required>
                        </div>
                        <div class="form-group">
                            <label for="status">حالة العقد</label>
                            <select id="status" name="status">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            </select>
                            @error('status')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- بيانات المستثمر -->
                <div class="form-section">
                    <h2 class="section-title">بيانات المستثمر</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="client_name" class="required-label">الاسم الكامل</label>
                            <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" required>
                            @error('client_name')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="client_email" class="required-label">البريد الإلكتروني</label>
                            <input type="email" id="client_email" name="client_email" value="{{ old('client_email') }}" required>
                            @error('client_email')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="client_phone" class="required-label">رقم الجوال</label>
                            <input type="tel" id="client_phone" name="client_phone" value="{{ old('client_phone') }}" required>
                            @error('client_phone')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="client_alt_phone">رقم جوال بديل (اختياري)</label>
                            <input type="tel" id="client_alt_phone" name="client_alt_phone" value="{{ old('client_alt_phone') }}">
                            @error('client_alt_phone')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="client_id_number" class="required-label">رقم الهوية</label>
                            <input type="text" id="client_id_number" name="client_id_number" value="{{ old('client_id_number') }}" required>
                            @error('client_id_number')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- بيانات العقار -->
                <div class="form-section">
                    <h2 class="section-title">بيانات العقار</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="property_type">نوع العقار</label>
                            <input type="text" id="property_type" name="property_type" value="{{ old('property_type') }}" placeholder="مثال: شقة سكنية">
                            @error('property_type')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="property_location">موقع العقار</label>
                            <input type="text" id="property_location" name="property_location" value="{{ old('property_location') }}" placeholder="المدينة، الحي">
                            @error('property_location')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- التفاصيل المالية -->
                <div class="form-section">
                    <h2 class="section-title">التفاصيل المالية</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="investment_amount" class="required-label">مبلغ الاستثمار</label>
                            <input type="number" id="investment_amount" name="investment_amount" value="{{ old('investment_amount') }}" min="0" step="0.01" required>
                            @error('investment_amount')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="duration_months" class="required-label">مدة الدفع (بالأشهر)</label>
                            <input type="number" id="duration_months" name="duration_months" value="{{ old('duration_months') }}" min="1" required>
                            @error('duration_months')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="apartment_price" class="required-label">سعر الشقة</label>
                            <input type="number" id="apartment_price" name="apartment_price" value="{{ old('apartment_price') }}" min="0" step="0.01" required>
                            @error('apartment_price')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="first_payment_date" class="required-label">تاريخ الدفعة الأولى</label>
                            <input type="date" id="first_payment_date" name="first_payment_date" value="{{ old('first_payment_date', now()->toDateString()) }}" required>
                            @error('first_payment_date')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="down_payment_initial" class="required-label">الدفعة الأولى اللازمة</label>
                            <select id="down_payment_initial" name="down_payment_initial" required>
                                <option value="">اختر المبلغ</option>
                                @foreach([100000, 150000, 200000, 250000, 300000, 350000, 400000, 450000, 500000, 550000, 600000, 650000, 700000, 750000, 800000, 850000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000, 1500000, 1600000, 1700000, 1800000, 1900000, 2000000] as $amount)
                                    <option value="{{ $amount }}" {{ old('down_payment_initial') == $amount ? 'selected' : '' }}>{{ number_format($amount, 0, '.', ',') }}</option>
                                @endforeach
                            </select>
                            @error('down_payment_initial')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="down_payment_other">الدفعات الأخرى</label>
                            <select id="down_payment_other" name="down_payment_other">
                                <option value="">اختر المبلغ</option>
                                @foreach([100000, 150000, 200000, 250000, 300000, 350000, 400000, 450000, 500000, 550000, 600000, 650000, 700000, 750000, 800000, 850000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000, 1500000, 1600000, 1700000, 1800000, 1900000, 2000000] as $amount)
                                    <option value="{{ $amount }}" {{ old('down_payment_other') == $amount ? 'selected' : '' }}>{{ number_format($amount, 0, '.', ',') }}</option>
                                @endforeach
                            </select>
                            @error('down_payment_other')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="profit_percentage">نسبة الربح</label>
                            <input type="number" id="profit_percentage" name="profit_percentage" value="{{ old('profit_percentage') }}" min="0" step="0.01">
                            @error('profit_percentage')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="remaining_amount">المبلغ المتبقي</label>
                            <input type="number" id="remaining_amount" name="remaining_amount" value="{{ old('remaining_amount') }}" min="0" step="0.01">
                            @error('remaining_amount')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- طريقة الدفع -->
                <div class="form-section">
                    <h2 class="section-title">طريقة الدفع</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="payment_method" class="required-label">طريقة الدفع</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">اختر طريقة الدفع</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                <option value="bank_transaction" {{ old('payment_method') == 'bank_transaction' ? 'selected' : '' }}>تحويل بنكي</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                            </select>
                            @error('payment_method')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- أقسام تفاصيل الدفع الديناميكية -->
                <!-- قسم تفاصيل الدفع النقدي -->
                <div class="form-section dynamic-section" id="cash_details_section">
                    <h3 class="section-subtitle">تفاصيل الدفع النقدي</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="cash_amount">المبلغ النقدي</label>
                            <input type="number" id="cash_amount" name="cash_amount" value="{{ old('cash_amount') }}" min="0" step="0.01">
                            @error('cash_amount')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="cash_receipt_number">رقم إيصال الدفع</label>
                            <input type="text" id="cash_receipt_number" name="cash_receipt_number" value="{{ old('cash_receipt_number') }}">
                            @error('cash_receipt_number')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- قسم تفاصيل التحويل البنكي -->
                <div class="form-section dynamic-section" id="bank_details_section">
                    <h3 class="section-subtitle">تفاصيل التحويل البنكي</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bank_name">اسم البنك</label>
                            <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                            @error('bank_name')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="account_number">رقم الحساب</label>
                            <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}">
                            @error('account_number')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="transaction_id">رقم العملية</label>
                            <input type="text" id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="transfer_date">تاريخ التحويل</label>
                            <input type="date" id="transfer_date" name="transfer_date" value="{{ old('transfer_date') }}">
                            @error('transfer_date')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- قسم تفاصيل الشيك -->
                <div class="form-section dynamic-section" id="check_details_section">
                    <h3 class="section-subtitle">تفاصيل الشيك</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="check_number">رقم الشيك</label>
                            <input type="text" id="check_number" name="check_number" value="{{ old('check_number') }}">
                            @error('check_number')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="check_amount">مبلغ الشيك</label>
                            <input type="number" id="check_amount" name="check_amount" value="{{ old('check_amount') }}" min="0" step="0.01">
                            @error('check_amount')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="check_holder">اسم حامل الشيك</label>
                            <input type="text" id="check_holder" name="check_holder" value="{{ old('check_holder') }}">
                            @error('check_holder')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="check_bank">بنك الشيك</label>
                            <input type="text" id="check_bank" name="check_bank" value="{{ old('check_bank') }}">
                            @error('check_bank')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="check_bank_branch">فرع بنك الشيك</label>
                            <input type="text" id="check_bank_branch" name="check_bank_branch" value="{{ old('check_bank_branch') }}">
                            @error('check_bank_branch')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="check_due_date">تاريخ استحقاق الشيك</label>
                            <input type="date" id="check_due_date" name="check_due_date" value="{{ old('check_due_date') }}">
                            @error('check_due_date')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="check_receipt_date">تاريخ استلام الشيك</label>
                            <input type="date" id="check_receipt_date" name="check_receipt_date" value="{{ old('check_receipt_date') }}">
                            @error('check_receipt_date')<div class="alert-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit"><i class="fas fa-save"></i> حفظ العقد</button>
                    <a href="{{ url()->previous() }}" class="btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </main>

    @endsection

@push('js')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.getElementById('payment_method');
        const cashDetailsSection = document.getElementById('cash_details_section');
        const bankDetailsSection = document.getElementById('bank_details_section');
        const checkDetailsSection = document.getElementById('check_details_section');

        function togglePaymentDetails() {
            const selectedMethod = paymentMethodSelect.value;

            // إخفاء جميع الأقسام أولاً
            cashDetailsSection.classList.remove('show');
            bankDetailsSection.classList.remove('show');
            checkDetailsSection.classList.remove('show');

            // إظهار القسم المناسب
            if (selectedMethod === 'cash') {
                cashDetailsSection.classList.add('show');
            } else if (selectedMethod === 'bank_transaction') {
                bankDetailsSection.classList.add('show');
            } else if (selectedMethod === 'check') {
                checkDetailsSection.classList.add('show');
            }
        }

        // الاستماع لتغيير طريقة الدفع
        paymentMethodSelect.addEventListener('change', togglePaymentDetails);

        // تشغيل الدالة عند تحميل الصفحة لإظهار القسم الصحيح بناءً على القيمة القديمة (old value)
        togglePaymentDetails();
    });
</script>
@endpush
