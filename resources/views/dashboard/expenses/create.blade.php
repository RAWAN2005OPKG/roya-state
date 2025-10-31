@extends('layouts.container')
@section('title', 'إضافة مصروف جديد')

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
    body { background-color: var(--light-bg); color: var(--text-color); direction: rtl; font-family: 'Cairo', 'Arial', sans-serif; }
    .main-content { width: 100%; max-width: 1400px; margin: 20px auto; padding: 20px; }
    .page-header { text-align: center; margin-bottom: 30px; padding: 30px; background-color: var(--white-bg); border-radius: 16px; box-shadow: var(--shadow-lg); }
    .page-header h1 { font-size: 2.5rem; color: var(--text-color); gap: 15px; }
    .form-container { background-color: var(--white-bg); padding: 30px; border-radius: 16px; margin-bottom: 30px; box-shadow: var(--shadow); }
    .container-title { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid var(--border-color); display: flex; align-items: center; gap: 10px; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
    .form-group { display: flex; flex-direction: column; }
    .form-group label { margin-bottom: 8px; font-weight: 600; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; border: 2px solid var(--border-color); border-radius: 8px; transition: border-color 0.3s ease, box-shadow 0.3s ease; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-color); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }
    .btn-submit { width: 100%; padding: 15px; border: none; border-radius: 8px; background-color: var(--primary-color); color: #fff; font-size: 1.2rem; font-weight: 700; cursor: pointer; grid-column: 1 / -1; margin-top: 20px; transition: all 0.3s ease; }
    .btn-submit:hover { background-color: var(--primary-hover); transform: translateY(-2px); }
    .hidden { display: none !important; }
    .dynamic-section { grid-column: 1 / -1; padding: 20px; background-color: var(--light-bg); border-radius: 12px; border: 2px solid var(--border-color); margin-top: 15px; }
    .dynamic-section h4 { color: var(--primary-color); margin-bottom: 15px; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> إضافة مصروف جديد</h1>
    </div>

    <!-- نموذج إضافة المصروف -->
    <div class="form-container">
        <h2 class="container-title">
            <i class="fas fa-money-bill-wave"></i> بيانات المصروف
        </h2>

        <form id="expenseForm" class="form-grid" action="{{ route('dashboard.expenses.store') }}" method="POST">
            @csrf


            <!-- الحقول الأساسية -->
            <div class="form-group"><label for="expenseDate">تاريخ الدفع</label><input type="date" id="expenseDate" name="date" required value="{{ old('date') }}"></div>
            <div class="form-group"><label for="receiptName">اسم الوصل</label><input type="text" id="receiptName" name="receipt_name" placeholder="اسم الوصل/الفاتورة" value="{{ old('receipt_name') }}"></div>
            <div class="form-group"><label for="receiptValueShekel">قيمة الوصل (شيكل)</label><input type="number" id="receiptValueShekel" name="receipt_value_shekel" min="0" step="0.01" placeholder="0.00" value="{{ old('receipt_value_shekel') }}"></div>
            <div class="form-group"><label for="costValueDollar">قيمة التكلفة (دولار $)</label><input type="number" id="costValueDollar" name="cost_value_dollar" min="0" step="0.01" placeholder="0.00" value="{{ old('cost_value_dollar') }}"></div>

            <div class="form-group"><label for="expensePayee">اسم المستفيد</label><input type="text" id="expensePayee" name="payee" placeholder="اسم الشخص أو الشركة" required value="{{ old('payee') }}"></div>
            <div class="form-group"><label for="expensePhone">رقم الجوال</label><input type="tel" id="expensePhone" name="phone" placeholder="0599123456" value="{{ old('phone') }}"></div>
            <div class="form-group"><label for="expenseJob">العمل/المهنة</label><input type="text" id="expenseJob" name="job" placeholder="مثال: مقاول، مهندس" value="{{ old('job') }}"></div>
            <div class="form-group"><label for="expenseIdNumber">رقم الهوية</label><input type="text" id="expenseIdNumber" name="id_number" placeholder="رقم الهوية الشخصية" value="{{ old('id_number') }}"></div>

            <div class="form-group"><label for="walidShare">وليد الخالص ابو حمزه (1/5)</label><input type="number" id="walidShare" name="walid_share_amount" min="0" step="0.01" placeholder="0.00" value="{{ old('walid_share_amount') }}"></div>
            <div class="form-group"><label for="mohammadKhalidShare">محمد وخالد (1/6)</label><input type="number" id="mohammadKhalidShare" name="mohammad_khalid_share_amount" min="0" step="0.01" placeholder="0.00" value="{{ old('mohammad_khalid_share_amount') }}"></div>

<div class="form-group">
    <label for="expenseProject">المشروع</label>
    <select id="expenseProject" name="project_id" required>
        <option value="">-- اختر المشروع --</option>
        <option value="0" @selected(old('project_id') == '0')>مصروف عام</option>

        @foreach ($projects as $project)
            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->project_name }}</option>
        @endforeach
    </select>
</div>

            <div class="form-group"><label for="expenseAmount">المبلغ (الأساسي)</label><input type="number" id="expenseAmount" name="amount" min="0" step="0.01" placeholder="0.00" required value="{{ old('amount') }}"></div>
            <div class="form-group"><label for="walidPaidDollar">المدفوع من وليد (دولار $)</label><input type="number" id="walidPaidDollar" name="walid_paid_dollar" min="0" step="0.01" placeholder="0.00" value="{{ old('walid_paid_dollar') }}"></div>
            <div class="form-group"><label for="mohammadKhalidPaidDollar">المدفوع من محمد وخالد (دولار $)</label><input type="number" id="mohammadKhalidPaidDollar" name="mohammad_khalid_paid_dollar" min="0" step="0.01" placeholder="0.00" value="{{ old('mohammad_khalid_paid_dollar') }}"></div>
            <div class="form-group"><label for="walidPaidShekel">المدفوع من وليد (شيكل)</label><input type="number" id="walidPaidShekel" name="walid_paid_shekel" min="0" step="0.01" placeholder="0.00" value="{{ old('walid_paid_shekel') }}"></div>
            <div class="form-group"><label for="mohammadKhalidPaidShekel">المدفوع من محمد وخالد (شيكل)</label><input type="number" id="mohammadKhalidPaidShekel" name="mohammad_khalid_paid_shekel" min="0" step="0.01" placeholder="0.00" value="{{ old('mohammad_khalid_paid_shekel') }}"></div>
            <div class="form-group"><label for="remainingAmount">المتبقي (شيكل)</label><input type="number" id="remainingAmount" name="remaining_amount" step="0.01" placeholder="0.00" value="{{ old('remaining_amount') }}"></div>
            <div class="form-group"><label for="remainingAmountDollar">المتبقي ($)</label><input type="number" id="remainingAmountDollar" name="remaining_amount_dollar" step="0.01" placeholder="0.00" value="{{ old('remaining_amount_dollar') }}"></div>
            <div class="form-group"><label for="differenceInPayments">الفرق بين الدفعات</label><input type="number" id="differenceInPayments" name="difference_in_payments" step="0.01" placeholder="0.00" value="{{ old('difference_in_payments') }}"></div>
            <div class="form-group"><label for="totalPaidAmount">مجموع المبالغ المدفوعة</label><input type="number" id="totalPaidAmount" name="total_paid_amount" min="0" step="0.01" placeholder="0.00" value="{{ old('total_paid_amount') }}"></div>

            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" name="currency" required>
                    <option value="شيكل" @selected(old('currency') == 'شيكل')>شيكل</option>
                    <option value="دولار" @selected(old('currency') == 'دولار')>دولار</option>
                    <option value="دينار" @selected(old('currency') == 'دينار')>دينار</option>
                </select>
            </div>
            <div class="form-group">
                <label for="paymentMethod">طريقة الدفع</label>
                <select id="paymentMethod" name="payment_method" required>
                    <option value="">-- اختر طريقة الدفع --</option>
                    <option value="نقداً" @selected(old('payment_method') == 'نقداً')>نقداً</option>
                    <option value="تحويل بنكي" @selected(old('payment_method') == 'تحويل بنكي')>تحويل بنكي</option>
                    <option value="شيك" @selected(old('payment_method') == 'شيك')>شيك</option>
                </select>
            </div>
            <div class="form-group">
                <label for="paymentSource">مصدر الدفع</label>
                <select id="paymentSource" name="payment_source" required>
                    <option value="">-- اختر المصدر --</option>
                    <option value="خزينة" @selected(old('payment_source') == 'خزينة')>من الخزينة</option>
                    <option value="بنك" @selected(old('payment_source') == 'بنك')>من حساب بنكي</option>
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
                            <option value="محمد" @selected(old('cash_receiver') == 'محمد')>محمد</option>
                            <option value="خالد" @selected(old('cash_receiver') == 'خالد')>خالد</option>
                            <option value="أخرى" @selected(old('cash_receiver') == 'أخرى')>أخرى (حدد)</option>
                        </select>
                    </div>
                    <div class="form-group hidden" id="otherReceiverGroup"><label for="otherReceiver">اسم المستلم (أخرى)</label><input type="text" id="otherReceiver" name="cash_receiver_other" placeholder="اكتب اسم المستلم" value="{{ old('cash_receiver_other') }}"></div>
                    <div class="form-group"><label for="receiverJob">وظيفة المستلم</label><input type="text" id="receiverJob" name="receiver_job" placeholder="مثال: محاسب, مدير" value="{{ old('receiver_job') }}"></div>
                </div>
            </div>

            <!-- قسم تفاصيل البنك -->
            <div id="bankDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-university"></i> تفاصيل البنك</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="senderBank">البنك المرسل</label>
                        <select id="senderBank" name="sender_bank">
                            <option value="">-- اختر البنك --</option>
                            <option value="بنك القاهرة عمان" @selected(old('sender_bank') == 'بنك القاهرة عمان')>بنك القاهرة عمان</option>
                            <option value="بنك الصفا" @selected(old('sender_bank') == 'بنك الصفا')>بنك الصفا</option>
                            <option value="بنك فلسطين" @selected(old('sender_bank') == 'بنك فلسطين')>بنك فلسطين</option>
                            <option value="البنك العربي" @selected(old('sender_bank') == 'البنك العربي')>البنك العربي</option>
                            <option value="other" @selected(old('sender_bank') == 'other')>أخرى</option>
                        </select>
                    </div>
                    <div class="form-group hidden" id="otherSenderBankGroup"><label for="otherSenderBank">اسم البنك المرسل (أخرى)</label><input type="text" id="otherSenderBank" name="other_sender_bank" value="{{ old('other_sender_bank') }}"></div>
                    <div class="form-group"><label for="senderBranch">فرع البنك المرسل</label><input type="text" id="senderBranch" name="sender_branch" value="{{ old('sender_branch') }}"></div>
                    <div class="form-group"><label for="receiverBank">البنك المستقبل</label><input type="text" id="receiverBank" name="receiver_bank" value="{{ old('receiver_bank') }}"></div>
                    <div class="form-group"><label for="receiverBranch">فرع البنك المستقبل</label><input type="text" id="receiverBranch" name="receiver_branch" value="{{ old('receiver_branch') }}"></div>
                    <div class="form-group"><label for="transactionId">رقم التحويلة</label><input type="text" id="transactionId" name="transaction_id" value="{{ old('transaction_id') }}"></div>
                </div>
            </div>

            <!-- قسم تفاصيل الشيك -->
            <div id="checkDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-money-check"></i> تفاصيل الشيك</h4>
                <div class="form-grid">
                    <div class="form-group"><label for="checkNumber">رقم الشيك</label><input type="text" id="checkNumber" name="check_number" value="{{ old('check_number') }}"></div>
                    <div class="form-group"><label for="checkOwner">اسم مالك الشيك</label><input type="text" id="checkOwner" name="check_owner" value="{{ old('check_owner') }}"></div>
                    <div class="form-group"><label for="checkHolder">اسم حامل الشيك</label><input type="text" id="checkHolder" name="check_holder" value="{{ old('check_holder') }}"></div>
                    <div class="form-group"><label for="checkDueDate">تاريخ الاستحقاق</label><input type="date" id="checkDueDate" name="check_due_date" value="{{ old('check_due_date') }}"></div>
                    <div class="form-group"><label for="checkReceiveDate">تاريخ الاستلام</label><input type="date" id="checkReceiveDate" name="check_receive_date" value="{{ old('check_receive_date') }}"></div>
                </div>
            </div>

            <!-- تفاصيل -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="expenseDetails">تفاصيل</label>
                <textarea id="expenseDetails" name="details" rows="3" placeholder="تفاصيل المصروف...">{{ old('details') }}</textarea>
            </div>

            <!-- ملاحظات -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="expenseNotes">ملاحظات</label>
                <textarea id="expenseNotes" name="notes" rows="3" placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
            </div>

            {{-- تم تغيير نص الزر --}}
            <button type="submit" class="btn-submit"><i class="fas fa-plus-circle"></i> حفظ المصروف</button>
        </form>
    </div>
</main>
@endsection

@section('script')
<script>

document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const paymentSourceSelect = document.getElementById('paymentSource');
    const cashReceiverSelect = document.getElementById('cashReceiver');
    const senderBankSelect = document.getElementById('senderBank');

    function updateDynamicSections() {
        const paymentMethod = paymentMethodSelect.value;
        const paymentSource = paymentSourceSelect.value;

        // إخفاء الكل أولاً
        document.getElementById('cashDetailsSection').classList.add('hidden');
        document.getElementById('bankDetailsSection').classList.add('hidden');
        document.getElementById('checkDetailsSection').classList.add('hidden');

        // إظهار الأقسام بناءً على طريقة الدفع
        if (paymentMethod === 'نقداً') {
            document.getElementById('cashDetailsSection').classList.remove('hidden');
        }
        if (paymentMethod === 'شيك') {
            document.getElementById('checkDetailsSection').classList.remove('hidden');
        }
        // إظهار تفاصيل البنك إذا كانت طريقة الدفع تحويل بنكي أو مصدر الدفع بنك
        if (paymentMethod === 'تحويل بنكي' || paymentSource === 'بنك') {
            document.getElementById('bankDetailsSection').classList.remove('hidden');
        }
    }

    function toggleOtherReceiverField() {
        document.getElementById('otherReceiverGroup').classList.toggle('hidden', cashReceiverSelect.value !== 'أخرى');
    }

    function toggleOtherSenderBankField() {
        document.getElementById('otherSenderBankGroup').classList.toggle('hidden', senderBankSelect.value !== 'other');
    }

    paymentMethodSelect.addEventListener('change', updateDynamicSections);
    paymentSourceSelect.addEventListener('change', updateDynamicSections);
    cashReceiverSelect.addEventListener('change', toggleOtherReceiverField);
    senderBankSelect.addEventListener('change', toggleOtherSenderBankField);

    // التشغيل الأولي عند تحميل الصفحة
    updateDynamicSections();
    toggleOtherReceiverField();
    toggleOtherSenderBankField();
});
</script>
@endsection
