@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
 <style>
        :root { --dark-bg-1: #ffffff; --dark-bg-2:rgb(183, 223, 185) ; --text-color: #570a0a; --border-color: #ddd5d5; --success: #2ecc71; --danger: #e74c3c; }
        body { background-color: var(--dark-bg-1 ); color: var(--text-color); font-family: 'Cairo', sans-serif; padding: 15px; margin: 0; }
        .container { max-width: 1200px; margin: auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .page-header h1 { font-size: 1.8rem; margin: 0; }
        .balance-card { background-color: var(--success); color: white; padding: 20px; border-radius: 12px; text-align: center; margin-bottom: 20px; }
        .form-container, .table-container { background-color: var(--dark-bg-2); padding: 20px; border-radius: 12px; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        input, select, button { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background-color: var(--dark-bg-1); color: var(--text-color); font-size: 1rem; box-sizing: border-box; }
        button { background-color: var(--success); cursor: pointer; grid-column: 1 / -1; margin-top: 10px; font-weight: bold; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid var(--border-color); white-space: nowrap; }
        .table-wrapper { overflow-x: auto; }
        .form-group.full-width { grid-column: 1 / -1; }
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
            <h1><i class="fas fa-money-bill-wave"></i> صندوق الكاش</h1>
        </div>
        <div class="balance-card"><h2>الرصيد الحالي: <span id="currentCashBalance">0</span></h2></div>

        <div class="form-container">
            <h3>تسجيل حركة نقدية جديدة</h3>
            <form id="cashForm"  action="{{ route('dashboard.prbancasccheq') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group"><label for="cash_date">تاريخ الحركة</label><input type="date" id="cash_date" name="date" required></div>
                <div class="form-group"><label for="cash_type">نوع الحركة</label><select id="cash_type" name="type" required><option value="deposit">إيداع</option><option value="withdrawal">سحب نقدي</option><option value="personal_withdrawal">مسحوبات شخصية</option></select></div>
                <div class="form-group"><label for="cheque_project_name">اسم صاحب المبلغ</label><input type="text" id="cheque_project_name" name="project_name" placeholder="المشروع المرتبط بالشيك"></div>
                <div class="form-group"><label for="payer_id_number">رقم الهوية</label><input type="text" id="payer_id_number" name="payer_id_number"></div>
                <div class="form-group"><label for="client_phone">رقم الجوال</label><input type="text" id="client_phone" name="client_phone"></div>
                <div class="form-group"><label for="beneficiary_name">المستلم</label><select id="beneficiary_name" name="beneficiary" required><option value="خالد">خالد</option><option value="محمد">محمد</option></select></div>
                <div class="form-group"><label for="cash_operator_role">وظيفته</label><input type="text" id="cash_operator_role" name="operator_role" placeholder="مثال: مدير مالي"></div>
                <div class="form-group"><label for="cash_operator">القائم بالعملية</label><input type="text" id="cash_operator" name="operator" placeholder="اسم الموظف/المدير" required></div>
                <div class="form-group"><label for="cheque_currency">العملة</label><select id="cheque_currency" name="currency" required><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select></div>
                <div class="form-group"><label for="cheque_amount">المبلغ</label><input type="number" id="cheque_amount" name="amount" step="0.01" required></div>
                <div class="form-group full-width"><label for="cash_details">تفاصيل</label><input type="text" id="cash_details" name="details"></div>
                <div class="form-group full-width"><label for="cash_notes">ملاحظات</label><input type="text" id="cash_notes" name="notes"></div>
                <button type="submit">حفظ الحركة</button>
            </form>
        </div>


@endsection


@section('script')
<script>
        const cashLogKey = 'cash_log';
        const dateFormatKey = 'date_format_preference';
        const getDB = (key) => JSON.parse(localStorage.getItem(key)) || [];
        const setDB = (key, data) => localStorage.setItem(key, JSON.stringify(data));

        let currentLang = localStorage.getItem(dateFormatKey) || 'ar';

        const monthNames = {
            ar: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            en: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        };

        const formatCurrency = (num, currency) => new Intl.NumberFormat('en-US').format(num || 0) + ` ${currency}`;
        const formatDate = (dateString) => {
            if (!dateString) return '-';
            const d = new Date(dateString);
         const day = d.getUTCDate();
            const month = monthNames[currentLang][d.getUTCMonth()];
            const year = d.getUTCFullYear();
            return `${day} ${month} ${year}`;
        }

        function renderCashLog() {
            const log = getDB(cashLogKey).sort((a, b) => new Date(b.date) - new Date(a.date));
            const body = document.getElementById('cashLogBody');
            body.innerHTML = '';

            log.forEach(item => {
                const amount = parseFloat(item.amount);
                const isDeposit = item.type === 'deposit';

                let typeText = 'سحب';
                if(isDeposit) typeText = 'إيداع';
                if(item.type === 'personal_withdrawal') typeText = 'مسحوبات شخصية';

                body.innerHTML += `<tr style="color:${isDeposit ? 'var(--success)' : 'var(--danger)'};">
                    <td>${formatDate(item.date)}</td>
                    <td>${typeText}</td>
                    <td>${formatCurrency(amount, item.currency)}</td>
                    <td>${item.projectName || '-'}</td>
                    <td>${item.source || '-'}</td>
                    <td>${item.beneficiary || '-'}</td>
                    <td>${item.operator}</td>
                    <td>${item.details}</td>
                    <td>${item.notes || '-'}</td>
                </tr>`;
            });

            const totalBalance = log.reduce((s, i) => s + (i.type === 'deposit' ? parseFloat(i.amount) : -parseFloat(i.amount)), 0);
            document.getElementById('currentCashBalance').textContent = new Intl.NumberFormat('en-US').format(totalBalance || 0) + ' (متعدد العملات)';
        }

        // اترك الإرسال للنموذج ليتم عبر الخادم (Laravel)
        // يمكن لاحقاً إضافة منع مزدوج للنقر أو سبينر هنا إذا لزم

         document.getElementById('date-toggle').addEventListener('click', () => {
             currentLang = currentLang === 'ar' ? 'en' : 'ar';
             localStorage.setItem(dateFormatKey, currentLang);
             document.getElementById('date-toggle').textContent = currentLang === 'ar' ? 'عرض التاريخ بالإنجليزية' : 'عرض التاريخ بالعربية';
             renderCashLog();
         });

         document.addEventListener('DOMContentLoaded', () => {
             document.getElementById('date-toggle').textContent = currentLang === 'ar' ? 'عرض التاريخ بالإنجليزية' : 'عرض التاريخ بالعربية';
             document.getElementById('cash_date').valueAsDate = new Date();
             renderCashLog();
         });
    </script>
@endsection
