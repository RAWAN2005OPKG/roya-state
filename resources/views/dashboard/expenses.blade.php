@extends('layouts.container')
@section('title', 'سجل المصروفات الشامل')

{{-- ================================================================= --}}
{{--  قسم الأنماط (CSS) - لا حاجة لتغييره                      --}}
{{-- ================================================================= --}}
@section('styles')
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #3730a3;
        --light-bg: #f8fafc;
        --white-bg: #ffffff;
        --text-color: #1f2937;
        --text-muted: #6b7280;
        --border-color: #e5e7eb;
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    body {
        background-color: var(--light-bg);
        color: var(--text-color);
        direction: rtl;
        font-family: 'Cairo', 'Arial', sans-serif;
    }
    .main-content {
        width: 100%;
        max-width: 1400px;
        margin: 20px auto;
        padding: 20px;
    }
    .page-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 30px;
        background-color: var(--white-bg);
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
    }
    .page-header h1 {
        font-size: 2.5rem;
        color: var(--text-color);
        gap: 15px;
    }
    .form-container {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
    }
    .container-title {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        margin-bottom: 8px;
        font-weight: 600;
    }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .btn-submit {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        background-color: var(--primary-color);
        color: #fff;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        grid-column: 1 / -1;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
    }
    .hidden {
        display: none !important;
    }
    .dynamic-section {
        grid-column: 1 / -1;
        padding: 20px;
        background-color: var(--light-bg);
        border-radius: 12px;
        border: 2px solid var(--border-color);
        margin-top: 15px;
    }
    .dynamic-section h4 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }
</style>
@endsection

{{-- ================================================================= --}}
{{--  قسم المحتوى (HTML) - تم إصلاح الأخطاء وإضافة الحقول المفقودة --}}
{{-- ================================================================= --}}
@section('content')
<main class="main-content">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1><i class="fas fa-file-invoice-dollar"></i> سجل المصروفات الشامل</h1>
    </div>

    <!-- نموذج تسجيل مصروف جديد -->
    <div class="form-container">
        <h2 class="container-title">
            <i class="fas fa-plus-circle"></i> تسجيل مصروف جديد
        </h2>

        <form id="expenseForm" class="form-grid" action="{{ route('dashboard.expenses.store') }}" method="POST">
            @csrf
            <!-- الحقول الأساسية -->
            <div class="form-group"><label for="expenseDate">تاريخ الدفع</label><input type="date" id="expenseDate" name="date" required></div>
            <div class="form-group"><label for="expensePayee">اسم المستفيد</label><input type="text" id="expensePayee" name="payee" placeholder="اسم الشخص أو الشركة" required></div>
            <div class="form-group"><label for="expensePhone">رقم الجوال</label><input type="tel" id="expensePhone" name="phone" placeholder="0599123456"></div>
            <div class="form-group"><label for="expenseJob">العمل/المهنة</label><input type="text" id="expenseJob" name="job" placeholder="مثال: مقاول، مهندس"></div>
            <div class="form-group"><label for="expenseIdNumber">رقم الهوية</label><input type="text" id="expenseIdNumber" name="id_number" placeholder="رقم الهوية الشخصية"></div>
            <div class="form-group">
                <label for="expenseProject">المشروع</label>
                <select id="expenseProject" name="project_id" required>
                    <option value="">-- اختر المشروع --</option>
                    <option value="0">مصروف عام</option>
                    {{-- سيتم ملء باقي المشاريع عبر الجافاسكريبت --}}
                </select>
            </div>
            <div class="form-group">
                <label for="mainCategory">بند المصروف</label>
                <input type="text" id="mainCategory" name="main_category" placeholder="مثال: مواد بناء، أجور عمال" required>
            </div>
            <div class="form-group"><label for="expenseAmount">المبلغ</label><input type="number" id="expenseAmount" name="amount" min="0" step="0.01" placeholder="0.00" required></div>
            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" name="currency" required>
                    <option value="شيكل">شيكل</option>
                    <option value="دولار">دولار</option>
                    <option value="دينار">دينار</option>
                </select>
            </div>
            <div class="form-group">
                <label for="paymentMethod">طريقة الدفع</label>
                <select id="paymentMethod" name="payment_method" required>
                    <option value="">-- اختر طريقة الدفع --</option>
                    <option value="نقداً">نقداً</option>
                    <option value="تحويل بنكي">تحويل بنكي</option>
                    <option value="شيك">شيك</option>
                </select>
            </div>
            <div class="form-group">
                <label for="paymentSource">مصدر الدفع</label>
                <select id="paymentSource" name="payment_source" required>
                    <option value="">-- اختر المصدر --</option>
                    <option value="خزينة">من الخزينة</option>
                    <option value="بنك">من حساب بنكي</option>
                </select>
            </div>

            <!-- قسم تفاصيل الدفع النقدي -->
            <div id="cashDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-money-bill-wave"></i> تفاصيل الدفع النقدي</h4>
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

            <!-- قسم تفاصيل البنك -->
            <div id="bankDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-university"></i> تفاصيل البنك</h4>

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
            <div id="checkDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-money-check"></i> تفاصيل الشيك</h4>
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

            <!-- ملاحظات -->
            <div class="form-group" style="grid-column: 1 / -1;"><label for="expenseNotes">ملاحظات</label><textarea id="expenseNotes" name="notes" rows="3" placeholder="أي ملاحظات إضافية..."></textarea></div>

            <button type="submit" class="btn-submit"><i class="fas fa-save"></i> حفظ المصروف</button>
        </form>
    </div>
</main>
@endsection

{{-- ================================================================= --}}
{{--  قسم الجافاسكريبت (JavaScript) - الكود النهائي والمبسط         --}}
{{-- ================================================================= --}}
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- تعريف العناصر ---
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const paymentSourceSelect = document.getElementById('paymentSource');
    const cashReceiverSelect = document.getElementById('cashReceiver');
    const senderBankSelect = document.getElementById('senderBank');
    const expenseForm = document.getElementById('expenseForm');

    // --- الدوال ---

    /**
     * الدالة الرئيسية لتحديث وإظهار الأقسام بناءً على طريقة ومصدر الدفع.
     */
    function updateDynamicSections() {
        const paymentMethod = paymentMethodSelect.value;
        const paymentSource = paymentSourceSelect.value;

        // إخفاء جميع الأقسام كخطوة أولى
        document.getElementById('cashDetailsSection').classList.add('hidden');
        document.getElementById('bankDetailsSection').classList.add('hidden');
        document.getElementById('checkDetailsSection').classList.add('hidden');

        // إظهار قسم "نقداً"
        if (paymentMethod === 'نقداً') {
            document.getElementById('cashDetailsSection').classList.remove('hidden');
        }

        // إظهار قسم "الشيك"
        if (paymentMethod === 'شيك') {
            document.getElementById('checkDetailsSection').classList.remove('hidden');
        }

        // إظهار قسم "البنك"
        if (paymentMethod === 'تحويل بنكي' || paymentSource === 'بنك') {
            document.getElementById('bankDetailsSection').classList.remove('hidden');
        }
    }

    /**
     * إظهار/إخفاء حقل "اسم المستلم (أخرى)"
     */
    function toggleOtherReceiverField() {
        const otherReceiverGroup = document.getElementById('otherReceiverGroup');
        otherReceiverGroup.classList.toggle('hidden', cashReceiverSelect.value !== 'أخرى');
    }

    /**
     * إظهار/إخفاء حقل "اسم البنك المرسل (أخرى)"
     */
    function toggleOtherSenderBankField() {
        const otherSenderBankGroup = document.getElementById('otherSenderBankGroup');
        otherSenderBankGroup.classList.toggle('hidden', senderBankSelect.value !== 'other');
    }

    /**
     * تحميل المشاريع من LocalStorage (مثال توضيحي)
     * في التطبيق الحقيقي، يجب أن تأتي هذه البيانات من الكونترولر.
     */
    function loadProjects() {
        // هذا مثال فقط. في تطبيقك الفعلي، ستمرر المشاريع من الكونترولر
        // ولن تحتاج لهذه الدالة على الأغلب.
        const projects = [
            { id: 1, name: 'مشروع بناء البرج' },
            { id: 2, name: 'مشروع تطوير الموقع' }
        ];
        const projectSelect = document.getElementById('expenseProject');
        projects.forEach(project => {
            const option = document.createElement('option');
            option.value = project.id;
            option.textContent = project.name;
            projectSelect.appendChild(option);
        });
    }

    // --- ربط الأحداث ---
    paymentMethodSelect.addEventListener('change', updateDynamicSections);
    paymentSourceSelect.addEventListener('change', updateDynamicSections);
    cashReceiverSelect.addEventListener('change', toggleOtherReceiverField);
    senderBankSelect.addEventListener('change', toggleOtherSenderBankField);

    expenseForm.addEventListener('submit', function(e) {
        // هذه الدالة الآن فارغة لتسمح للنموذج بالإرسال بشكل طبيعي إلى لارافيل
        // يمكنك إضافة كود التحقق من الحقول هنا إذا أردت قبل الإرسال
        console.log('النموذج قيد الإرسال إلى الخادم...');
    });

    // --- الإعدادات الأولية عند تحميل الصفحة ---
    document.getElementById('expenseDate').valueAsDate = new Date();
    loadProjects(); // استدعاء دالة تحميل المشاريع
    updateDynamicSections(); // استدعاء للتأكد من الحالة الصحيحة عند التحميل
});
</script>
@endsection
