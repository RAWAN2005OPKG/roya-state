@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
<style>
        :root { --dark-bg-1:rgb(255, 255, 255); --rgb(68, 168, 135)2:rgb(135, 235, 165); --text-color:rgb(0, 0, 0); --border-color:rgb(130, 133, 125), 125); --primary: #3498db; --success: #2ecc71; --danger: #e74c3c; }
        body { background-color: var(--dark-bg-1 ); color: var(--text-color); font-family: 'Cairo', sans-serif; padding: 15px; margin: 0; }
        .container { max-width: 1200px; margin: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .page-header h1 { font-size: 1.8rem; margin: 0; }
        .balance-card { background-color: var(--primary); color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 20px; }
        .form-container, .table-container { background-color: var(--dark-bg-2); padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        input, select, button { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--dark-bg-1); color: var(--text-color); font-size: 1rem; box-sizing: border-box; }
        button { background-color: var(--primary); cursor: pointer; grid-column: 1 / -1; margin-top: 10px; font-weight: bold; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
        .table-wrapper { overflow-x: auto; }
        .form-group.full-width { grid-column: 1 / -1; }
        .hidden { display: none; }
        .toggle-btn { background-color:rgb(0, 0, 0); color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        @media (max-width: 768px) {
            body { padding: 10px; }
            .page-header h1 { font-size: 1.5rem; }
            th, td { padding: 8px; }
        }
    </style>
@endsection


@section('content')

        <div class="page-header">
            <h1><i class="fas fa-landmark"></i> الحسابات البنكية</h1>
        </div>
        <div class="balance-card"><h2>الرصيد البنكي الحالي: <span id="currentBankBalance">0</span></h2></div>

        <div class="form-container">
            <h3>تسجيل حركة بنكية جديدة</h3>
            <form id="bankForm"  action="{{ route('dashboard.prbancasccheq') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group"><label for="bank_date">تاريخ الحركة</label><input type="date" id="bank_date" name="date" required></div>
                <div class="form-group"><label for="bank_day">اسم العميل</label><input type="text" id="bank_day" name="client_name"></div>
                <div class="form-group"><label for="client_phone">رقم الجوال</label><input type="text" id="client_phone" name="client_phone"></div>
                <div class="form-group"><label for="payer_id_number">رقم الهوية</label><input type="text" id="payer_id_number" name="payer_id_number"></div>
                <div class="form-group"><label for="bank_type">نوع الحركة</label><select id="bank_type" name="type" required><option value="deposit">إيداع</option><option value="withdrawal">سحب نقدي</option><option value="transfer">حوالة بنكية</option><option value="personal_withdrawal">مسحوبات شخصية</option></select></div>
                <div class="form-group"><label for="bank_amount">المبلغ</label><input type="number" id="bank_amount" name="amount" step="0.01" required></div>
                <div class="form-group"><label for="bank_operator_role">وظيفته</label><input type="text" id="bank_operator_role" name="operator_role" placeholder="مثال: مدير مالي"></div>
                <div class="form-group"><label for="bank_operator">القائم بالعملية</label><input type="text" id="bank_operator" name="operator" placeholder="اسم الموظف/المدير" required></div>
                <div class="form-group"><label for="bank_currency">العملة</label><select id="bank_currency" name="currency" required><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select></div>
                <div class="form-group"><label for="bank_project_name">اسم المشروع</label><input type="text" id="bank_project_name" name="project_name" placeholder="اسم المشروع المرتبط بالحركة"></div>
                <div class="form-group"><label for="bank_source">مصدر المبلغ</label><input type="text" id="bank_source" name="source" placeholder="مثال: العميل س، حوالة واردة"></div>
                <div class="form-group"><label for="transfer_details">تفاصيل الحوالة</label><input type="text" id="transfer_details" name="transfer_details"></div>
                <!-- البنك المرسل -->
                <div class="form-group">
                    <label for="senderBank">البنك المرسل</label>
                    <select id="senderBank" name="payer_bank_name">
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
                    <input type="text" id="otherSenderBank" name="other_bank_name" placeholder="اكتب اسم البنك">
                </div>

                <div class="form-group " id="senderBranchGroup">
                    <label for="senderBranch">فرع البنك المرسل</label>
                    <input type="text" id="senderBranch" name="payer_bank_number" placeholder="اكتب رقم/فرع الحساب">
                </div>

                <!-- البنك المستقبل -->
                <div class="form-group">
                    <label for="receiverBank">البنك المستقبل</label>
                    <select id="receiverBank" name="beneficiary_bank_name">
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
                    <input type="text" id="otherReceiverBank" name="other_bank_name" placeholder="اكتب اسم البنك">
                </div>

                <div class="form-group " id="receiverBranchGroup">
                    <label for="receiverBranch">فرع البنك المستقبل</label>
                    <input type="text" id="receiverBranch" name="beneficiary_bank_number" placeholder="اكتب رقم/فرع الحساب">
                </div>

                <div class="form-group">
                    <label for="transactionId">رقم التحويلة</label>
                    <input type="text" id="transactionId" name="transfer_number" placeholder="أدخل رقم التحويلة">
                </div>
                <div class="form-group full-width">
                    <label for="details">التفاصيل</label>
                    <input type="text" id="details" name="details" placeholder="تفاصيل إضافية">
                </div>
                <div class="form-group full-width">
                    <label for="notes">ملاحظات</label>
                    <input type="text" id="notes" name="notes" placeholder="ملاحظات">
                </div>
            </div>
        </div>

        <button type="submit">حفظ الحركة</button>
    </form>
</div>



@endsection


@section('script')
 <script>
        const bankLogKey = 'bank_log';
        const dateFormatKey = 'date_format_preference';
        const getDB = (key) => JSON.parse(localStorage.getItem(key)) || [];
        const setDB = (key, data) => localStorage.setItem(key, JSON.stringify(data));

        let currentDateFormat = localStorage.getItem(dateFormatKey) || 'ar-EG';

        const formatCurrency = (num, currency) => new Intl.NumberFormat('en-US').format(num || 0) + ` ${currency}`;
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const locale = `${currentDateFormat}-u-nu-latn`;
            return new Date(dateString + 'T00:00:00Z').toLocaleDateString(locale, {
                year: 'numeric', month: 'short', day: 'numeric', timeZone: 'UTC'
            });
        }

        function renderBankLog() {
            const log = getDB(bankLogKey).sort((a, b) => new Date(b.date) - new Date(a.date));
            const body = document.getElementById('bankLogBody');
            body.innerHTML = '';

            log.forEach(item => {
                const amount = parseFloat(item.amount);
                const isDeposit = item.type === 'deposit';

                let typeText = 'سحب';
                if(isDeposit) typeText = 'إيداع';
                if(item.type === 'transfer') typeText = 'حوالة';
                if(item.type === 'personal_withdrawal') typeText = 'مسحوبات شخصية';

                body.innerHTML += `<tr style="color:${isDeposit ? 'var(--success)' : 'var(--danger)'};">
                <td>${item.payment_name || '-'}</td>
                <td>${item.payment_first_installment || '-'}</td>
                <td>${item.payment_full_amount || '-'}</td>
                <td>${item.payment_remaining || '-'}</td>
                <td>${item.transfer_details || '-'}</td>
                <td>${item.transfer_number || '-'}</td>
                <td>${item.beneficiary_name || '-'}</td>
                <td>${item.beneficiary_bank_name || '-'}</td>
                <td>${item.beneficiary_bank_number || '-'}</td>
                <td>${item.cheque_number || '-'}</td>
                <td>${item.cheque_owner_name || '-'}</td>
                <td>${item.payer_bank_name || '-'}</td>
                <td>${item.payer_bank_number || '-'}</td>
                <td>${item.payer_id_number || '-'}</td>
                <td>${item.client_name || '-'}</td>
                <td>${item.client_phone || '-'}</td>
                </tr>`;
            });

            const totalBalance = log.reduce((s, i) => s + (i.type === 'deposit' ? parseFloat(i.amount) : -parseFloat(i.amount)), 0);
            document.getElementById('currentBankBalance').textContent = new Intl.NumberFormat('en-US').format(totalBalance || 0) + ' (متعدد العملات)';
        }

        document.getElementById('bank_name').addEventListener('change', function() {
            document.getElementById('otherBankNameGroup').style.display = this.value === 'other' ? 'block' : 'none';
        });

        document.getElementById('bankForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const log = getDB(bankLogKey);
           log.push({
    id: Date.now(),
    date: document.getElementById('bank_date').value,
    day: document.getElementById('bank_day').value,
    type: document.getElementById('bank_type').value,
    amount: document.getElementById('bank_amount').value,
    currency: document.getElementById('bank_currency').value,
    projectName: document.getElementById('bank_project_name').value,
    source: document.getElementById('bank_source').value,
    beneficiary: document.getElementById('bank_beneficiary').value,
    operator: document.getElementById('bank_operator').value,
    operator_role: document.getElementById('bank_operator_role').value,
    bankName: document.getElementById('bank_name').value,
    otherBankName: document.getElementById('otherBankName').value,
    details: document.getElementById('bank_details').value,
    notes: document.getElementById('bank_notes').value,
    payment_name: document.getElementById('payment_name').value,
    payment_first_installment: document.getElementById('payment_first_installment').value,
    payment_full_amount: document.getElementById('payment_full_amount').value,
    payment_remaining: document.getElementById('payment_remaining').value,
    transfer_details: document.getElementById('transfer_details').value,
    transfer_number: document.getElementById('transfer_number').value,
    beneficiary_name: document.getElementById('beneficiary_name').value,
    beneficiary_bank_name: document.getElementById('beneficiary_bank_name').value,
    beneficiary_bank_number: document.getElementById('beneficiary_bank_number').value,
    cheque_number: document.getElementById('cheque_number').value,
    cheque_owner_name: document.getElementById('cheque_owner_name').value,
    payer_bank_name: document.getElementById('payer_bank_name').value,
    payer_bank_number: document.getElementById('payer_bank_number').value,
    payer_id_number: document.getElementById('payer_id_number').value,
    client_name: document.getElementById('client_name').value,
    client_phone: document.getElementById('client_phone').value,
});

            setDB(bankLogKey, log);
            this.reset();
            document.getElementById('otherBankNameGroup').style.display = 'none';
            renderBankLog();
            Swal.fire('تم', 'تم تسجيل الحركة البنكية بنجاح', 'success');
        });

        document.getElementById('date-toggle').addEventListener('click', () => {
            currentDateFormat = currentDateFormat === 'ar-EG' ? 'en-GB' : 'ar-EG';
            localStorage.setItem(dateFormatKey, currentDateFormat);
            document.getElementById('date-toggle').textContent = currentDateFormat === 'ar-EG' ? 'عرض التاريخ بالإنجليزية' : 'عرض التاريخ بالعربية';
            renderBankLog();
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('date-toggle').textContent = currentDateFormat === 'ar-EG' ? 'عرض التاريخ بالإنجليزية' : 'عرض التاريخ بالعربية';
            document.getElementById('bank_date').valueAsDate = new Date();
            renderBankLog();
        });
    </script>
@endsection
