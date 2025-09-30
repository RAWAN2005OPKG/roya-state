@extends('layouts.container')
@section('title', 'صندوق سندات القبض والصرف')

@section('styles')
<style>
    :root { --bg-primary: #f8f9fa; --bg-secondary: #ffffff; --border-color: #dee2e6; --text-primary: #212529; --text-secondary: #6c757d; --primary: #007bff; --success: #28a745; --danger: #dc3545; }
    body { background-color: var(--bg-primary); color: var(--text-primary); font-family: 'Cairo', sans-serif; }
    .container { max-width: 1400px; margin: auto; padding: 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; }
    .forms-container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; }
    .form-section { background-color: var(--bg-secondary); padding: 25px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
    .form-section h3 { margin-top: 0; padding-bottom: 10px; font-size: 1.5rem; }
    .receipt-form h3 { color: var(--success); border-bottom: 2px solid var(--success); }
    .payment-form h3 { color: var(--danger); border-bottom: 2px solid var(--danger); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .form-group { margin-bottom: 10px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: 600; }
    .form-group label.required::after { content: ' *'; color: var(--danger); }
    input, select, textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background-color: #fff; color: var(--text-primary); font-size: 1rem; box-sizing: border-box; }
    .btn-submit { cursor: pointer; grid-column: 1 / -1; margin-top: 15px; font-weight: bold; color: white; border: none; padding: 15px; border-radius: 8px; font-size: 1.1rem; transition: background-color 0.3s; }
    .receipt-form .btn-submit { background-color: var(--success); }
    .payment-form .btn-submit { background-color: var(--danger); }
    .auto-field { background-color: #e9ecef; }
    .contact-info { background-color: #e9ecef; padding: 8px; border-radius: 6px; margin-top: 5px; font-size: 0.9rem; }
    .dynamic-section { border-top: 1px solid var(--border-color); margin-top: 15px; padding-top: 15px; grid-column: 1 / -1; }
    .hidden { display: none; }
    @media (max-width: 992px) { .forms-container { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-exchange-alt"></i> سندات القبض والصرف</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">حدث خطأ!</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="forms-container">
        <!-- نموذج سند القبض -->
        <div class="form-section receipt-form">
            <h3><i class="fas fa-plus-circle"></i> سند قبض</h3>
            <form method="POST" action="{{ route('dashboard.receipt-vouchers.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required">التاريخ</label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="form-group">
                        <label>رقم السند</label>
                        <input type="text" class="auto-field" value="تلقائي" readonly>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="required">تم الاستلام من</label>
                        <select name="contact_id" class="contact-selector" data-form-type="receipt" required>
                            <option value="">اختر جهة الاتصال...</option>
                        </select>
                        <div class="contact-info" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="required">المبلغ</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="required">العملة</label>
                        <select name="currency" required>
                            <option value="ILS" @selected(old('currency') == 'ILS')>شيكل</option>
                            <option value="USD" @selected(old('currency') == 'USD')>دولار</option>
                            <option value="JOD" @selected(old('currency') == 'JOD')>دينار</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="required">طريقة الدفع</label>
                        <select name="payment_method" class="payment-method-selector" data-form-type="receipt" required>
                            <option value="">-- اختر --</option>
                            <option value="cash" @selected(old('payment_method') == 'cash')>نقداً</option>
                            <option value="bank_transaction" @selected(old('payment_method') == 'bank_transaction')>تحويل بنكي</option>
                            <option value="check" @selected(old('payment_method') == 'check')>شيك</option>
                        </select>
                    </div>
                    <div class="dynamic-section-container" data-form-type="receipt"></div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="required">مقابل</label>
                        <select name="purpose" class="purpose-selector" data-form-type="receipt" required>
                            <option value="project_payment" @selected(old('purpose') == 'project_payment')>دفعة من مشروع</option>
                            <option value="other" @selected(old('purpose') == 'other')>أخرى</option>
                        </select>
                    </div>
                    <div class="form-group project-group" style="grid-column: 1 / -1;">
                        <label class="required">اسم المشروع</label>
                        <select name="project_id" class="project-selector">
                            <option value="">اختر المشروع...</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>وصف الغرض</label>
                        <textarea name="purpose_description" rows="2">{{ old('purpose_description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="required">اسم المستلم (القابض)</label>
                        <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> حفظ سند القبض</button>
            </form>
        </div>

        <!-- نموذج سند الصرف -->
        <div class="form-section payment-form">
            <h3><i class="fas fa-minus-circle"></i> سند صرف</h3>
            <form method="POST" action="{{ route('dashboard.payment-vouchers.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required">التاريخ</label>
                        <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->toDateString()) }}" required>
                    </div>
                    <div class="form-group">
                        <label>رقم السند</label>
                        <input type="text" class="auto-field" value="تلقائي" readonly>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="required">صرف إلى</label>
                        <select name="contact_id" class="contact-selector" data-form-type="payment" required>
                            <option value="">اختر جهة الاتصال...</option>
                        </select>
                        <div class="contact-info" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                        <label class="required">المبلغ</label>
                        <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="required">العملة</label>
                        <select name="currency" required>
                            <option value="ILS" @selected(old('currency') == 'ILS')>شيكل</option>
                            <option value="USD" @selected(old('currency') == 'USD')>دولار</option>
                            <option value="JOD" @selected(old('currency') == 'JOD')>دينار</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="required">طريقة الدفع</label>
                        <select name="payment_method" class="payment-method-selector" data-form-type="payment" required>
                            <option value="">-- اختر --</option>
                            <option value="cash" @selected(old('payment_method') == 'cash')>نقداً</option>
                            <option value="bank_transaction" @selected(old('payment_method') == 'bank_transaction')>تحويل بنكي</option>
                            <option value="check" @selected(old('payment_method') == 'check')>شيك</option>
                        </select>
                    </div>
                    <div class="dynamic-section-container" data-form-type="payment"></div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="required">سبب الصرف</label>
                        <textarea name="purpose_description" rows="2" required>{{ old('purpose_description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>اسم المشروع (اختياري)</label>
                        <select name="project_id" class="project-selector">
                            <option value="">اختر المشروع...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">اسم المسلّم (الصارف)</label>
                        <input type="text" name="receiver_name" value="{{ old('receiver_name') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit"><i class="fas fa-save"></i> حفظ سند الصرف</button>
            </form>
        </div>
    </div>
</div>

                    <!-- قسم الدفع النقدي -->
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

                            <div class="form-group hidden" id="otherReceiverGroup">
                                <label for="otherReceiver">اسم المستلم (أخرى)</label>
                                <input type="text" id="otherReceiver" name="cash_receiver_other" placeholder="اكتب اسم المستلم">
                            </div>

                            <div class="form-group">
                                <label for="receiverJob">وظيفة المستلم</label>
                                <input type="text" id="receiverJob" name="cash_receiver_job" placeholder="مثال: محاسب، مدير">
                            </div>
                        </div>
                    </div>

                    <!-- قسم تفاصيل البنك -->
                    <div id="bankDetailsSection" class="dynamic-section hidden">
                        <h4><i class="fas fa-university"></i> تفاصيل البنك</h4>
                        <div class="form-grid">
                            <!-- البنك المرسل -->
                            <div class="form-group">
                                <label for="senderBank">البنك المرسل</label>
                                <select id="senderBank" name="sender_bank">
                                    <option value="">-- اختر البنك المرسل --</option>
                                    <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                    <option value="بنك الصفا">بنك الصفا</option>
                                    <option value="بنك فلسطين">بنك فلسطين</option>
                                    <option value="البنك العربي">البنك العربي</option>
                                    <option value="other">أخرى (حدد)</option>
                                </select>
                            </div>

                            <div class="form-group hidden" id="otherSenderBankGroup">
                                <label for="otherSenderBank">اسم البنك المرسل (أخرى)</label>
                                <input type="text" id="otherSenderBank" name="sender_bank_other" placeholder="اكتب اسم البنك">
                            </div>

                            <div class="form-group">
                                <label for="senderBranch">فرع البنك المرسل</label>
                                <input type="text" id="senderBranch" name="sender_branch" placeholder="اكتب اسم الفرع">
                            </div>

                            <!-- البنك المستقبل -->
                            <div class="form-group">
                                <label for="receiverBank">البنك المستقبل</label>
                                <select id="receiverBank" name="receiver_bank">
                                    <option value="">-- اختر البنك المستقبل --</option>
                                    <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                    <option value="بنك الصفا">بنك الصفا</option>
                                    <option value="بنك فلسطين">بنك فلسطين</option>
                                    <option value="البنك العربي">البنك العربي</option>
                                    <option value="other">أخرى (حدد)</option>
                                </select>
                            </div>

                            <div class="form-group hidden" id="otherReceiverBankGroup">
                                <label for="otherReceiverBank">اسم البنك المستقبل (أخرى)</label>
                                <input type="text" id="otherReceiverBank" name="receiver_bank_other" placeholder="اكتب اسم البنك">
                            </div>

                            <div class="form-group">
                                <label for="receiverBranch">فرع البنك المستقبل</label>
                                <input type="text" id="receiverBranch" name="receiver_branch" placeholder="اكتب اسم الفرع">
                            </div>

                            <div class="form-group">
                                <label for="transactionId">رقم التحويلة</label>
                                <input type="text" id="transactionId" name="transaction_id" placeholder="أدخل رقم التحويلة">
                            </div>
                        </div>
                    </div>

                    <!-- قسم تفاصيل الشيك -->
                    <div id="checkDetailsSection" class="dynamic-section hidden">
                        <h4><i class="fas fa-money-check"></i> تفاصيل الشيك</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="checkNumber">رقم الشيك</label>
                                <input type="text" id="checkNumber" name="check_number" placeholder="رقم أو اسم الشيك">
                            </div>

                            <div class="form-group">
                                <label for="checkOwner">اسم صاحب الشيك</label>
                                <input type="text" id="checkOwner" name="check_owner" placeholder="اسم صاحب الشيك">
                            </div>

                            <div class="form-group">
                                <label for="checkHolder">مالك الشيك</label>
                                <input type="text" id="checkHolder" name="check_holder" placeholder="اسم مالك الشيك">
                            </div>

                            <div class="form-group">
                                <label for="checkDueDate">تاريخ الاستحقاق</label>
                                <input type="date" id="checkDueDate" name="check_due_date">
                            </div>

                            <div class="form-group">
                                <label for="checkReceiveDate">تاريخ الاستلام</label>
                                <input type="date" id="checkReceiveDate" name="check_receive_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const CONTACTS_API_URL = '{{ route("api.contacts") }}';
    const PROJECTS_API_URL = '{{ route("api.projects") }}';

    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            console.error('خطأ في جلب البيانات:', error);
            return [];
        }
    }

    function populateSelectors(selectorClass, data, textField, valueField) {
        const selectors = document.querySelectorAll(`.${selectorClass}`);
        selectors.forEach(selector => {
            const oldValue = selector.dataset.oldValue;
            selector.innerHTML = '<option value="">اختر...</option>';
            data.forEach(item => {
                const option = new Option(`${item[textField]} (${item.type || ''})`, item[valueField]);
                if (item.phone) option.dataset.phone = item.phone;
                if (item.id_number) option.dataset.idNumber = item.id_number;
                if (oldValue == item[valueField]) {
                    option.selected = true;
                }
                selector.add(option);
            });
        });
    }

    fetchData(CONTACTS_API_URL).then(data => populateSelectors('contact-selector', data, 'name', 'id'));
    fetchData(PROJECTS_API_URL).then(data => populateSelectors('project-selector', data, 'name', 'id'));

    function handleContactChange(event) {
        const selector = event.target;
        const selectedOption = selector.options[selector.selectedIndex];
        const infoDiv = selector.closest('.form-group').querySelector('.contact-info');
        if (selector.value) {
            const phone = selectedOption.dataset.phone || 'N/A';
            const idNumber = selectedOption.dataset.idNumber || 'N/A';
            infoDiv.innerHTML = `<strong>الهوية:</strong> ${idNumber}
         <strong>الهاتف:</strong> ${phone}`;
            infoDiv.style.display = 'block';
        } else {
            infoDiv.style.display = 'none';
        }
    }

    function handlePaymentMethodChange(event) {
        const selector = event.target;
        const formType = selector.dataset.formType;
        const container = document.querySelector(`.dynamic-section-container[data-form-type="${formType}"]`);
        container.innerHTML = '';
        const templateId = `${selector.value}-details-template`.replace('_', '-');
        const template = document.getElementById(templateId);
        if (template) {
            container.appendChild(template.content.cloneNode(true));
        }
    }

    function handlePurposeChange(event) {
        const selector = event.target;
        const projectGroup = selector.closest('form').querySelector('.project-group');
        const projectSelect = projectGroup.querySelector('select');
        projectGroup.style.display = (selector.value === 'project_payment') ? 'block' : 'none';
        projectSelect.required = (selector.value === 'project_payment');
    }

    document.querySelectorAll('.contact-selector').forEach(s => s.addEventListener('change', handleContactChange));
    document.querySelectorAll('.payment-method-selector').forEach(s => s.addEventListener('change', handlePaymentMethodChange));
    document.querySelectorAll('.purpose-selector').forEach(s => s.addEventListener('change', handlePurposeChange));

    // Trigger change on load for old values
    document.querySelectorAll('.payment-method-selector, .purpose-selector').forEach(s => {
        if(s.value) s.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection
