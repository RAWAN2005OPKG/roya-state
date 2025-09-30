@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
{{-- يمكنك إضافة أي تنسيقات CSS خاصة بهذه الصفحة هنا إذا احتجت --}}
<style>
        :root { --dark-bg-1:rgb(255, 255, 255); --dark-bg-2:rgb(205, 216, 238); --text-color:rgb(0, 0, 0); --border-color: #e9dddd; --primary:rgb(5, 56, 122); --success: #2ecc71; --danger: #e74c3c;}
        body { background-color: var(--dark-bg-1 ); color: var(--text-color); font-family: 'Cairo', sans-serif; padding: 15px; margin: 0; }
        .container { max-width: 1400px; margin: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .page-header h1 { font-size: 1.8rem; margin: 0; }
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .kpi-card { background-color: var(--dark-bg-2); padding: 20px; border-radius: 12px; text-align: center; }
        .form-container, .table-container { background-color: var(--dark-bg-2); padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        input, select, button { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--dark-bg-1); color: var(--text-color); font-size: 1rem; box-sizing: border-box; }
        button { background-color: var(--primary); color: #1e1e1e; font-weight: bold; cursor: pointer; grid-column: 1 / -1; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
        .table-wrapper { overflow-x: auto; }
        .status-in_wallet { color: #f1c40f; font-weight: bold; }
        .status-cashed { color: var(--success); font-weight: bold; }
        .status-returned { color: var(--danger); font-weight: bold; }
        .toggle-btn { background-color: #34495e; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
        @media (max-width: 768px) {
            body { padding: 10px; }
            .page-header h1 { font-size: 1.5rem; }
            th, td { padding: 8px; }
        }
    </style>
@endsection


@section('content')


    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-money-check-alt"></i> محفظة الشيكات</h1>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card"><h3>شيكات في المحفظة</h3><h2 id="inWalletTotal">0</h2></div>
            <div class="kpi-card"><h3>شيكات تم صرفها</h3><h2 id="cashedTotal">0</h2></div>
            <div class="kpi-card"><h3>شيكات مرتجعة</h3><h2 id="returnedTotal">0</h2></div>
        </div>

        <div class="form-container">
             <h3>إضافة شيك جديد</h3>
             <form id="chequeForm"  action="{{ route('dashboard.prbancasccheq') }}" method="POST" enctype="multipart/form-data">
                @csrf
               <div class="form-group"><label for="cheque_date">تاريخ تحرير الشيك</label><input type="date" id="cheque_date" name="cheque_date"></div>
               <div class="form-group"><label for="cheque_due_date">تاريخ الاستحقاق</label><input type="date" id="cheque_due_date" name="due_date"></div>
               <div class="form-group"><label for="cheque_number">رقم الشيك</label><input type="text" id="cheque_number" name="cheque_number"></div>
               <div class="form-group"><label for="transfer_number">رقم الحوالة</label><input type="text" id="transfer_number" name="transfer_number"></div>
               <div class="form-group"><label for="cheque_type">نوع الشيك</label><select id="cheque_type" name="type" required><option value="incoming">شيك وارد</option><option value="outgoing">شيك صادر</option></select></div>
               <div class="form-group"><label for="owner_name">اسم مالك الشيك</label><input type="text" id="owner_name" name="owner_name"></div>
               <div class="form-group"><label for="holder_name">اسم صاحب الشيك</label><input type="text" id="holder_name" name="holder_name"></div>
               <div class="form-group"><label for="payer_id_number">رقم الهوية</label><input type="text" id="payer_id_number" name="payer_id_number"></div>
               <div class="form-group"><label for="client_phone">رقم الجوال</label><input type="text" id="client_phone" name="client_phone"></div>
               <div class="form-group"><label for="beneficiary_name">اسم المستفيد</label><input type="text" id="beneficiary_name" name="beneficiary_name"></div>
               <div class="form-group"><label for="cheque_project_name">اسم المشروع</label><input type="text" id="cheque_project_name" name="project_name" placeholder="المشروع المرتبط بالشيك"></div>
               <div class="form-group"><label for="cheque_currency">العملة</label><select id="cheque_currency" name="currency" required><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select></div>
               <div class="form-group"><label for="cheque_amount">المبلغ</label><input type="number" id="cheque_amount" name="amount" step="0.01" required></div>
               <div class="form-group"><label for="bank_name">اختر البنك</label><select id="bank_name" name="bank_name"><option value="">-- اختر  --</option><option value="بنك القاهرة عمان">بنك القاهرة عمان</option><option value="بنك الصفا">بنك الصفا</option><option value="بنك فلسطين">بنك فلسطين</option><option value="البنك العربي">البنك العربي</option><option value="other">أخرى (حدد)</option></select></div>
               <div class="form-group hidden" id="otherBankNameGroup"><label for="otherBankName">اسم البنك (أخرى)</label><input type="text" id="otherBankName" name="other_bank_name" placeholder="اكتب اسم البنك هنا"></div>
               <div class="form-group"><label for="bank_branch">اسم فرع البنك</label><input type="text" id="bank_branch" name="bank_branch"></div>
               <div class="form-group"><label for="account_number">رقم الحساب</label><input type="text" id="account_number" name="account_number"></div>
               <div class="form-group"><label for="cheque_operator">القائم بالعملية</label><input type="text" id="cheque_operator" name="operator"></div>
               <div class="form-group"><label for="transfer_details">تفاصيل الحوالة</label><input type="text" id="transfer_details" name="transfer_details"></div>
               <div class="form-group" style="grid-column: 1 / -1;"><label for="cheque_notes">ملاحظات</label><input type="text" id="cheque_notes" name="notes"></div>
               <div class="form-group"><label for="status">الحالة</label><select id="status" name="status"><option value="in_wallet">في المحفظة</option><option value="cashed">تم صرفه</option><option value="returned">مرتجع</option></select></div>
                <button type="submit">إضافة الشيك</button>
             </form>
        </div>



@endsection


@section('script')

    <script>
        const chequeLogKey = 'cheque_log';
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

        function renderChequeLog() {
            const log = getDB(chequeLogKey).sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate));
            const body = document.getElementById('chequeLogBody');
            body.innerHTML = '';

            let currencyTotals = { in_wallet: {}, cashed: {}, returned: {} };

            log.forEach(item => {
                const amount = parseFloat(item.amount);
                const currency = item.currency || 'شيكل';

                if (item.status && currencyTotals[item.status]) {
                    if (!currencyTotals[item.status][currency]) {
                        currencyTotals[item.status][currency] = 0;
                    }
                    currencyTotals[item.status][currency] += amount;
                }

                let statusText = 'غير محدد';
                switch(item.status) {
                    case 'in_wallet': statusText = 'في المحفظة'; break;
                    case 'cashed': statusText = 'تم صرفه'; break;
                    case 'returned': statusText = 'مرتجع'; break;
                }

                body.innerHTML += `<tr>
                    <td>${item.type === 'incoming' ? 'وارد' : 'صادر'}</td>
                    <td>${item.chequeNumber}</td>
                    <td>${formatCurrency(item.amount, item.currency)}</td>
                    <td>${formatDate(item.dueDate)}</td>
                    <td>${item.source}</td>
                    <td>${item.payee}</td>
                    <td>${item.projectName || '-'}</td>
                    <td class="status-${item.status}">${statusText}</td>
                    <td>
                        <select onchange="updateStatus(${item.id}, this.value)">
                            <option value="in_wallet" ${item.status === 'in_wallet' ? 'selected' : ''}>في المحفظة</option>
                            <option value="cashed" ${item.status === 'cashed' ? 'selected' : ''}>تم صرفه</option>
                            <option value="returned" ${item.status === 'returned' ? 'selected' : ''}>مرتجع</option>
                        </select>
                    </td>
                </tr>`;
            });

            const createTotalString = (totalObj) => {
                const parts = Object.entries(totalObj).map(([currency, amount]) => formatCurrency(amount, currency));
                return parts.length > 0 ? parts.join(' | ') : formatCurrency(0, 'شيكل');
            };

            document.getElementById('inWalletTotal').textContent = createTotalString(currencyTotals.in_wallet);
            document.getElementById('cashedTotal').textContent = createTotalString(currencyTotals.cashed);
            document.getElementById('returnedTotal').textContent = createTotalString(currencyTotals.returned);
        }

        function updateStatus(id, newStatus) {
            const log = getDB(chequeLogKey);
            const itemIndex = log.findIndex(item => item.id === id);
            if (itemIndex > -1) {
                log[itemIndex].status = newStatus;
                setDB(chequeLogKey, log);
                renderChequeLog();
                Swal.fire('تم', 'تم تحديث حالة الشيك', 'success');
            }
        }

        document.getElementById('chequeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const log = getDB(chequeLogKey);
            log.push({
                id: Date.now(),
                chequeDate: document.getElementById('cheque_date').value,
                dueDate: document.getElementById('cheque_due_date').value,
                type: document.getElementById('cheque_type').value,
                chequeNumber: document.getElementById('cheque_number').value,
                amount: document.getElementById('cheque_amount').value,
                currency: document.getElementById('cheque_currency').value,
                projectName: document.getElementById('cheque_project_name').value,
                source: document.getElementById('cheque_source').value,
                payee: document.getElementById('cheque_payee').value,
                operator: document.getElementById('cheque_operator').value,
                details: document.getElementById('cheque_details').value,
                notes: document.getElementById('cheque_notes').value,
                status: 'in_wallet'
            });
            setDB(chequeLogKey, log);
            this.reset();
            renderChequeLog();
            Swal.fire('تم', 'تمت إضافة الشيك بنجاح', 'success');
        });

        document.getElementById('date-toggle').addEventListener('click', () => {
            currentDateFormat = currentDateFormat === 'ar-EG' ? 'en-GB' : 'ar-EG';
            localStorage.setItem(dateFormatKey, currentDateFormat);
            document.getElementById('date-toggle').textContent = currentDateFormat === 'ar-EG' ? 'عرض التاريخ بالإنجليزية' : 'عرض التاريخ بالعربية';
            renderChequeLog();
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('date-toggle').textContent = currentDateFormat === 'ar-EG' ? 'عرض التاريخ بالإنجليزية' : 'عرض التاريخ بالعربية';
            document.getElementById('cheque_date').valueAsDate = new Date();
            document.getElementById('cheque_due_date').valueAsDate = new Date();
            renderChequeLog();
        });
    </script>
@endsection
