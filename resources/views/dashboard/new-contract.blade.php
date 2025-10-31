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
        --danger-color: #dc3545;
    }
    body {
        background-color: var(--dark-bg-1);
        color: var(--text-color);
        font-family: 'Cairo', sans-serif;
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
    }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
    }
    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }
    .btn-submit {
        background: linear-gradient(45deg, var(--primary-color), #007bff);
        color: #fff;
        padding: 15px 35px;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
    }
    .btn-secondary {
        background-color: var(--border-color);
        color: var(--text-color);
        padding: 15px 35px;
        border-radius: 8px;
        text-decoration: none;
    }
    .required-label::after {
        content: ' *';
        color: var(--danger-color);
    }
    .alert-danger {
        color: var(--danger-color);
        font-size: 0.9rem;
        margin-top: 5px;
    }

    /* --- الأنماط الجديدة للأقسام الديناميكية --- */
    .dynamic-section {
        grid-column: 1 / -1; /* لجعل القسم يمتد على عرض النموذج بالكامل */
        padding: 25px;
        background-color: var(--dark-bg-1);
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
        margin-top: 15px;
    }
    .section-subtitle {
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-bottom: 20px;
        font-weight: 600;
    }
    /* كلاس الإخفاء القوي */
    .hidden {
        display: none !important;
    }
</style>
@endsection

{{-- =================================================================== --}}
{{--  قسم المحتوى (HTML) - تم إضافة كلاس "hidden" للأقسام الديناميكية   --}}
{{-- =================================================================== --}}
@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-file-signature"></i> إنشاء عقد استثماري للعقارات</h1>
        <p>أدخل كافة التفاصيل لتوثيق عقد استثماري جديد بشكل كامل ودقيق.</p>
    </div>

    <div class="form-container">
        <form id="contractForm" action="YOUR_FORM_ACTION_URL" method="POST">
            @csrf

            {{-- ... كل حقول النموذج الأخرى تبقى كما هي ... --}}
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
                        <input type="date" id="signingDate" name="signing_date" value="{{ old('signing_date', now()->toDateString()) }}" required>
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

            <!-- قسم تفاصيل الدفع النقدي -->
            <div class="dynamic-section hidden" id="cash_details_section">
                <h3 class="section-subtitle">تفاصيل الدفع النقدي</h3>
                      <div class="form-grid">
                    <div class="form-group">
                        <label for="cashReceiver">من استلم المبلغ</label>
                        <select id="cashReceiver" name="cash_receiver">
                            <option value="">-- اختر المستلم --</option>
                            <option value="محمد">محمد</option>
                            <option value="خالد">خالد</option>
                            <option value="أخرى">أخرى (حدد)</option>
                        </select>
                    </div>
                    <div class="form-group hidden" id="otherReceiverGroup"><label for="otherReceiver">اسم المستلم (أخرى)</label><input type="text" id="otherReceiver" name="cash_receiver_other" placeholder="اكتب اسم المستلم"></div>
                    <div class="form-group"><label for="receiverJob">وظيفة المستلم</label><input type="text" id="receiverJob" name="receiver_job" placeholder="مثال: محاسب، مدير"></div>
                </div>
            </div>

            <!-- قسم تفاصيل التحويل البنكي -->
            <div class="dynamic-section hidden" id="bank_details_section">
                <h3 class="section-subtitle">تفاصيل التحويل البنكي</h3>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="senderBank">البنك المرسل</label>
                        <select id="senderBank" name="sender_bank"><option value="">-- اختر البنك --</option><option value="بنك القاهرة عمان">بنك القاهرة عمان</option><option value="بنك الصفا">بنك الصفا</option><option value="بنك فلسطين">بنك فلسطين</option><option value="البنك العربي">البنك العربي</option><option value="other">أخرى</option></select>
                    </div>
                    <div class="form-group hidden" id="otherSenderBankGroup"><label for="otherSenderBank">اسم البنك المرسل (أخرى)</label><input type="text" id="otherSenderBank" name="other_sender_bank"></div>
                    <div class="form-group"><label for="transactionId">رقم التحويلة</label><input type="text" id="transactionId" name="transaction_id"></div>

               <div class="form-group">
                        <label for="senderBank">البنك المستقبل</label>
                        <select id="senderBank" name="sender_bank"><option value="">-- اختر البنك --</option><option value="بنك القاهرة عمان">بنك القاهرة عمان</option><option value="بنك الصفا">بنك الصفا</option><option value="بنك فلسطين">بنك فلسطين</option><option value="البنك العربي">البنك العربي</option><option value="other">أخرى</option></select>
                    </div>
                    <div class="form-group hidden" id="otherSenderBankGroup"><label for="otherSenderBank">اسم البنك المرسل (أخرى)</label><input type="text" id="otherSenderBank" name="other_sender_bank"></div>
                    <div class="form-group"><label for="transactionId">رقم التحويلة</label><input type="text" id="transactionId" name="transaction_id"></div>
                </div>
                </div>
            </div>

            <!-- قسم تفاصيل الشيك -->
            <div class="dynamic-section hidden" id="check_details_section">
                <h3 class="section-subtitle">تفاصيل الشيك</h3>
                   <div class="form-grid">
                     <div class="form-group"><label for="checkDueDate">تاريخ الاستحقاق</label><input type="date" id="checkDueDate" name="check_due_date"></div>
                    <div class="form-group"><label for="checkNumber">رقم الشيك</label><input type="text" id="checkNumber" name="check_number"></div>
                    <div class="form-group"><label for="checkOwner">اسم مالك الشيك</label><input type="text" id="checkOwner" name="check_owner"></div>
                    <div class="form-group"><label for="checkOwner">اسم صاحب الشيك</label><input type="text" id="checkOwner" name="check_owner"></div>
                    <div class="form-group"><label for="checkOwner">  من الذي استلم المبلغ</label><input type="text" id="checkOwner" name="check_owner"></div>
                    <div class="form-group"><label for="checkOwner">وظيفته</label><input type="text" id="checkOwner" name="check_owner"></div>
                    <div class="form-group"><label for="checkDueDate">تاريخ الاستلام</label><input type="date" id="checkDueDate" name="check_due_date"></div>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- 1. تعريف العناصر ---
    const paymentMethodSelect = document.getElementById('payment_method');
    const cashSection = document.getElementById('cash_details_section');
    const bankSection = document.getElementById('bank_details_section');
    const checkSection = document.getElementById('check_details_section');

    // --- 2. الدالة الرئيسية لتحديث الأقسام ---
    function updatePaymentSections() {
        const selectedMethod = paymentMethodSelect.value;

        // الخطوة الأولى: إخفاء جميع الأقسام دائمًا
        cashSection.classList.add('hidden');
        bankSection.classList.add('hidden');
        checkSection.classList.add('hidden');

        // الخطوة الثانية: إظهار القسم المناسب فقط
        if (selectedMethod === 'cash') {
            cashSection.classList.remove('hidden');
        } else if (selectedMethod === 'bank_transaction') {
            bankSection.classList.remove('hidden');
        } else if (selectedMethod === 'check') {
            checkSection.classList.remove('hidden');
        }
    }

    // --- 3. ربط الحدث ---
    // التأكد من وجود العنصر قبل ربط الحدث لتجنب الأخطاء
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', updatePaymentSections);
    }

    // --- 4. التشغيل الأولي عند تحميل الصفحة ---
    // هذا يضمن أن الحالة الأولية للنموذج صحيحة (مهم عند استخدام old() في Laravel)
    updatePaymentSections();
});
</script>
@endsection
