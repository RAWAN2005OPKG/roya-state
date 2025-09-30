@extends('layouts.container')
@section('title', 'مدفوعات العملاء')

@section('styles')
<style>
    /* تعريف المتغيرات الأساسية للألوان والتنسيق */
    :root {
        --primary-color: #4f46e5; /* لون أساسي (أزرق بنفسجي) */
        --primary-hover: #3730a3; /* لون أساسي عند التحويم */
        --secondary-color: #06b6d4; /* لون ثانوي (أزرق سماوي) */
        --dark-bg-1:rgb(255, 255, 255); /* خلفية داكنة 1 */
        --dark-bg-2:rgb(255, 255, 255); /* خلفية داكنة 2 */
        --dark-bg-3:rgb(255, 255, 255); /* خلفية داكنة 3 */
        --text-color:rgb(0, 0, 0); /* لون النص الأساسي */
        --text-muted:rgb(19, 19, 19); /* لون النص الثانوي/الخافت */
        --border-color: rgba(148, 163, 184, 0.2); /* لون الحدود */
        --success-color: #10b981; /* لون النجاح (أخضر) */
        --danger-color: #ef4444; /* لون الخطر (أحمر) */
        --warning-color: #f59e0b; /* لون التحذير (برتقالي) */
        --info-color: #3b82f6; /* لون المعلومات (أزرق) */
    }

    /* إعادة تعيين الأنماط الأساسية */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* أنماط الجسم والخلفية */
    body {
        background: linear-gradient(135deg, var(--dark-bg-1) 0%,rgb(255, 255, 255) 100%);
        color: var(--text-color);
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        direction: rtl;
        text-align: right;
        line-height: 1.6;
    }

    /* المحتوى الرئيسي للصفحة */
    .main-content {
        width: 100%;
        max-width: 1600px;
        margin: 40px auto;
        padding: 0 20px;
        animation: fadeIn 0.6s ease-out;
    }

    /* تأثير الظهور التدريجي */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* رأس الصفحة */
    .page-header {
        text-align: center;
        margin-bottom: 40px;
        padding: 30px;
        background: linear-gradient(135deg, var(--dark-bg-2) 0%, var(--dark-bg-3) 100%);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .page-header h1 {
        font-size: 2.8rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .page-header p {
        font-size: 1.2rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    /* شبكة مؤشرات الأداء الرئيسية (KPIs) */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .kpi-card {
        background: linear-gradient(135deg, var(--dark-bg-2) 0%, var(--dark-bg-3) 100%);
        padding: 30px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .kpi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .kpi-card .label {
        color: var(--text-muted);
        margin-bottom: 15px;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .kpi-card .value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-color);
        line-height: 1.2;
    }

    .kpi-card .value.currency {
        color: var(--success-color);
        text-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
    }

    .kpi-card .value.remaining {
        color: var(--danger-color);
        text-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
    }

    /* حاوية الجدول */
    .table-container {
        background: linear-gradient(135deg, var(--dark-bg-2) 0%, var(--dark-bg-3) 100%);
        padding: 35px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-color);
    }

    /* عناصر التحكم بالجدول (بحث، فلاتر، تصدير) */
    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .search-filter-group {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        flex-grow: 1;
    }

    .search-input,
    .filter-select {
        padding: 12px 16px;
        background: var(--dark-bg-1);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        color: var(--text-color);
        min-width: 220px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .search-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* الأزرار العامة */
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-excel {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }

    .btn-excel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
        color: white;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
    }

    .btn-action {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.1rem;
        padding: 5px;
        transition: color 0.3s;
    }

    .btn-action:hover {
        color: var(--primary-color);
    }

    /* تغليف الجدول لتمكين التمرير الأفقي */
    .table-wrapper {
        overflow-x: auto;
    }

    /* أنماط الجدول */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        padding: 18px 15px;
        text-align: right;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
        vertical-align: middle;
    }

    .data-table th {
        font-size: 1.05rem;
        color: var(--text-muted);
        font-weight: 600;
        background: var(--dark-bg-1);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background: rgba(79, 70, 229, 0.1);
        transform: scale(1.01);
    }

    /* شريط التقدم */
    .progress-bar-container {
        width: 100%;
        background-color: var(--dark-bg-1);
        border-radius: 20px;
        overflow: hidden;
        height: 22px;
        position: relative;
    }

    .progress-bar {
        background: linear-gradient(90deg, var(--success-color), #34d399);
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.85rem;
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    /* العلامات (Tags) */
    .tag {
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
        text-align: center;
        border: 1px solid currentColor;
        transition: all 0.3s ease;
        display: inline-flex; /* لضمان محاذاة أفضل */
        align-items: center;
        justify-content: center;
    }

    .tag:hover {
        transform: scale(1.05);
    }

    .tag-cash {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success-color);
        border-color: var(--success-color);
    }

    .tag-installment {
        background: rgba(245, 158, 11, 0.15);
        color: var(--warning-color);
        border-color: var(--warning-color);
    }

    .tag-bank {
        background: rgba(59, 130, 246, 0.15);
        color: var(--info-color);
        border-color: var(--info-color);
    }

    /* تصميم متجاوب */
    @media (max-width: 768px) {
        .main-content {
            padding: 0 10px;
            margin: 20px auto;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .kpi-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-filter-group {
            flex-direction: column;
        }

        .search-input,
        .filter-select {
            min-width: 100%;
        }

        .data-table th,
        .data-table td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }
    }

    /* دعم RTL لمكتبة SweetAlert2 */
    .rtl-popup {
        direction: rtl !important;
        text-align: right !important;
    }

    /* رسالة التحميل */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .fa-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endsection

@section('content')

<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-file-signature"></i> العقود النشطة</h1>
        <p>نظرة شاملة على جميع الاتفاقيات المالية الجارية مع العملاء</p>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="label">إجمالي عدد العقود</div>
            <div class="value" id="kpiTotalContracts">0</div>
        </div>
        <div class="kpi-card">
            <div class="label">إجمالي قيمة العقود</div>
            <div class="value currency" id="kpiTotalValue">0</div>
        </div>
        <div class="kpi-card">
            <div class="label">إجمالي المبالغ المدفوعة</div>
            <div class="value currency" id="kpiTotalPaid">0</div>
        </div>
        <div class="kpi-card">
            <div class="label">إجمالي المبالغ المتبقية</div>
            <div class="value remaining" id="kpiTotalRemaining">0</div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-controls">
            <div class="search-filter-group">
                <input type="text" id="searchInput" class="search-input" placeholder="ابحث باسم العميل أو الوحدة...">
                <select id="projectFilter" class="filter-select">
                    <option value="">كل المشاريع</option>
                </select>
                <select id="paymentMethodFilter" class="filter-select">
                    <option value="">كل طرق الدفع</option>
                    <option value="كاش">كاش</option>
                    <option value="تقسيط">تقسيط</option>
                    <option value="معاملة بنكية">معاملة بنكية</option>
                    <option value="معاملة بنكية وكاش">معاملة بنكية وكاش</option>
                </select>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="/dashboard/new-contract" class="btn btn-primary"><i class="fas fa-plus"></i> عقد جديد</a>
                <button class="btn btn-excel" onclick="exportToExcel()"><i class="fas fa-file-excel"></i> تصدير إلى Excel</button>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table" id="contractsTable">
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th>المشروع / الوحدة</th>
                        <th>قيمة العقد</th>
                        <th>طريقة الدفع</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>نسبة السداد</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="contractsTableBody"></tbody>
            </table>
        </div>
    </div>
</main>

@endsection

@section('script')
<script>

    function getDB(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error(`خطأ في قراءة البيانات من ${key}:`, e);
            return [];
        }
    }

    /*
     يحفظ البيانات في localStorage.
      @param {string} key - المفتاح لتخزين البيانات.
      @param {Array} data - البيانات المراد حفظها.
     */
    function setDB(key, data) {
        try {
            localStorage.setItem(key, JSON.stringify(data));
        } catch (e) {
            console.error(`خطأ في حفظ البيانات إلى ${key}:`, e);
        }
    }

    /*
      تنسيق الأرقام كعملة.
      @param {number} num - الرقم المراد تنسيقه.
      @param {string} currency - رمز العملة (افتراضي: 'شيكل').
      @returns {string} الرقم المنسق مع العملة.
     */
    function formatCurrency(num, currency = 'شيكل') {
        try {
            return new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num || 0) + ' ' + currency;
        } catch (e) {
            console.warn('خطأ في تنسيق العملة، استخدام التنسيق الافتراضي:', e);
            return (num || 0).toFixed(2) + ' ' + currency;
        }
    }

    /*
     تنظيف المدخلات لمنع هجمات XSS.
     @param {string} input - النص المراد تنظيفه.
     @returns {string} النص النظيف.
     */
    function sanitizeInput(input) {
        const div = document.createElement('div');
        div.textContent = input;
        return div.innerHTML;
    }

    /*
      دالة Debounce لتأخير تنفيذ الدالة
      @param {Function} func - الدالة المراد تأخيرها
      @param {number} wait - وقت التأخير بالمللي ثانية
      @returns {Function} الدالة المؤجلة
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    
    
     // يعرض العقود في الجدول ويحدث مؤشرات الأداء الرئيسية.
     
    function renderContracts() {
        try {
            showLoading(true);

            let clients = getDB('clients');
            const payments = getDB('client_payments');

            // توليد بيانات عينة إذا كانت قاعدة البيانات فارغة
            if (clients.length === 0) {
                generateSampleData();
                clients = getDB('clients'); // إعادة جلب البيانات بعد التوليد
            }

            const tableBody = document.getElementById('contractsTableBody');
            if (!tableBody) {
                console.error('عنصر الجدول (contractsTableBody) غير موجود.');
                showError('حدث خطأ داخلي: عنصر الجدول غير موجود.');
                showLoading(false);
                return;
            }

            tableBody.innerHTML = ''; // مسح المحتوى الحالي للجدول

            // قيم الفلاتر
            const searchTerm = (document.getElementById('searchInput')?.value || '').toLowerCase();
            const projectFilter = document.getElementById('projectFilter')?.value || '';
            const paymentMethodFilter = document.getElementById('paymentMethodFilter')?.value || '';

            let totalContracts = 0;
            let totalValue = 0;
            let totalPaid = 0;

            // تصفية العملاء بناءً على معايير البحث والفلاتر
            const filteredClients = clients.filter(client => {
                const searchMatch = !searchTerm ||
                    client.name.toLowerCase().includes(searchTerm) ||
                    client.unit.toLowerCase().includes(searchTerm) ||
                    client.project.toLowerCase().includes(searchTerm);
                const projectMatch = !projectFilter || client.project === projectFilter;
                const paymentMethodMatch = !paymentMethodFilter || client.payment_method === paymentMethodFilter;
                return searchMatch && projectMatch && paymentMethodMatch;
            });

            if (filteredClients.length === 0) {
                showEmptyState();
                showLoading(false);
                return;
            }

            // بناء صفوف الجدول لكل عميل مصفى
            filteredClients.forEach(client => {
                try {
                    const clientPayments = payments.filter(p => p.clientId === client.id);
                    const paidAmount = clientPayments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
                    const agreementAmount = parseFloat(client.agreementAmount) || 0;
                    const remainingAmount = Math.max(0, agreementAmount - paidAmount);
                    const paidPercentage = agreementAmount > 0 ? (paidAmount / agreementAmount) * 100 : 0;

                    // تحديث إجمالي مؤشرات الأداء الرئيسية
                    totalContracts++;
                    totalValue += agreementAmount;
                    totalPaid += paidAmount;

                    let paymentTagClass = '';
                    switch (client.payment_method) {
                        case 'كاش':
                            paymentTagClass = 'tag-cash';
                            break;
                        case 'تقسيط':
                            paymentTagClass = 'tag-installment';
                            break;
                        case 'معاملة بنكية':
                        case 'معاملة بنكية وكاش': // يمكن دمجها أو فصلها حسب الحاجة
                            paymentTagClass = 'tag-bank';
                            break;
                        default:
                            paymentTagClass = ''; // لا يوجد نمط خاص
                    }

                    const row = `
                        <tr data-client-id="${client.id}">
                            <td><strong>${sanitizeInput(client.name)}</strong></td>
                            <td>${sanitizeInput(client.project)} / ${sanitizeInput(client.unit)}</td>
                            <td>${formatCurrency(agreementAmount, client.currency)}</td>
                            <td><span class="tag ${paymentTagClass}">${sanitizeInput(client.payment_method || '-')}
                            </span></td>
                            <td style="color: var(--success-color);">${formatCurrency(paidAmount, client.currency)}</td>
                            <td style="color: var(--danger-color);">${formatCurrency(remainingAmount, client.currency)}</td>
                            <td>
                                <div class="progress-bar-container" title="${paidPercentage.toFixed(1)}%">
                                    <div class="progress-bar" style="width: ${paidPercentage}%;">${paidPercentage.toFixed(0)}%</div>
                                </div>
                            </td>
                            <td>
                                <button class="btn-action" title="عرض السجل المالي للعميل" onclick="viewClientDetails(${client.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action" title="تعديل العقد" onclick="editContract(${client.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-action" title="حذف العقد" onclick="deleteContract(${client.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn-action" title="طباعة العقد" onclick="printContract(${client.id})">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                } catch (clientError) {
                    console.error('خطأ في معالجة بيانات العميل:', clientError);
                    showError('حدث خطأ أثناء معالجة بيانات أحد العملاء.');
                }
            });

            // تحديث عناصر مؤشرات الأداء الرئيسية
            updateKPIs(totalContracts, totalValue, totalPaid);
            showLoading(false);

        } catch (error) {
            console.error('خطأ في عرض العقود:', error);
            showError('حدث خطأ في تحميل البيانات.');
            showLoading(false);
        }
    }

    /*
      يحدث قيم مؤشرات الأداء الرئيسية (KPIs).
      @param {number} totalContracts - إجمالي عدد العقود.
      @param {number} totalValue - إجمالي قيمة العقود.
      @param {number} totalPaid - إجمالي المبالغ المدفوعة.
     */
    function updateKPIs(totalContracts, totalValue, totalPaid) {
        const elements = {
            'kpiTotalContracts': totalContracts,
            'kpiTotalValue': formatCurrency(totalValue),
            'kpiTotalPaid': formatCurrency(totalPaid),
            'kpiTotalRemaining': formatCurrency(totalValue - totalPaid)
        };

        Object.entries(elements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            }
        });
    }

    /*
      يعرض أو يخفي رسالة التحميل.
      @param {boolean} show - true لإظهار رسالة التحميل، false لإخفائها.
     */
    function showLoading(show) {
        const tableBody = document.getElementById('contractsTableBody');
        if (!tableBody) return;

        if (show) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary-color);"></i>
                        <div style="margin-top: 10px;">جاري التحميل...</div>
                    </td>
                </tr>
            `;
        } else if (tableBody.querySelector('.fa-spinner')) {
            // إذا كانت رسالة التحميل معروضةراح يتم استبدالها بمحتوى الجدول الحقيقي
        }
    }

    
     // يعرض حالة عدم وجود نتائج.
     
    function showEmptyState() {
        const tableBody = document.getElementById('contractsTableBody');
        if (!tableBody) return;

        tableBody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <i class="fas fa-search" style="font-size: 2rem; color: var(--text-muted);"></i>
                    <div style="margin-top: 10px; color: var(--text-muted);">لا توجد نتائج مطابقة للبحث.</div>
                </td>
            </tr>
        `;
    }

    /*
      يعرض رسالة خطأ للمستخدم.
      @param {string} message - رسالة الخطأ المراد عرضها.
     */
    function showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'خطأ',
                text: message,
                icon: 'error',
                confirmButtonText: 'حسناً',
                customClass: {
                    popup: 'rtl-popup'
                }
            });
        } else {
            alert('خطأ: ' + message);
        }
    }

    /*
     يعرض تفاصيل العميل في نافذة منبثقة.
     @param {number} clientId - معرف العميل.
     */
    function viewClientDetails(clientId) {
        try {
            const clients = getDB('clients');
            const payments = getDB('client_payments');
            const client = clients.find(c => c.id === clientId);

            if (!client) {
                showError('لم يتم العثور على بيانات العميل.');
                return;
            }

            const clientPayments = payments.filter(p => p.clientId === clientId);
            const paidAmount = clientPayments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
            const agreementAmount = parseFloat(client.agreementAmount) || 0;
            const remainingAmount = Math.max(0, agreementAmount - paidAmount);

            let paymentsHtml = '';
            if (clientPayments.length > 0) {
                paymentsHtml = clientPayments.map(payment => `
                    <div style="padding: 10px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <span>${sanitizeInput(payment.date)}</span>
                        <span style="color: var(--success-color); font-weight: bold;">${formatCurrency(payment.amount, client.currency)}</span>
                    </div>
                `).join('');
            } else {
                paymentsHtml = '<div style="text-align: center; padding: 20px; color: var(--text-muted);">لا توجد مدفوعات مسجلة لهذا العميل.</div>';
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: `تفاصيل العميل: ${sanitizeInput(client.name)}`,
                    html: `
                        <div style="text-align: right; direction: rtl;">
                            <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color);">
                                <strong>المشروع:</strong> ${sanitizeInput(client.project)}<br>
                                <strong>الوحدة:</strong> ${sanitizeInput(client.unit)}<br>
                                <strong>قيمة العقد:</strong> ${formatCurrency(agreementAmount, client.currency)}<br>
                                <strong>طريقة الدفع:</strong> ${sanitizeInput(client.payment_method)}<br>
                                <strong>المدفوع:</strong> <span style="color: var(--success-color);">${formatCurrency(paidAmount, client.currency)}</span><br>
                                <strong>المتبقي:</strong> <span style="color: var(--danger-color);">${formatCurrency(remainingAmount, client.currency)}</span>
                            </div>
                            <div style="border-top: 2px solid var(--border-color); padding-top: 15px;">
                                <h4 style="margin-bottom: 10px; color: var(--text-color);">سجل المدفوعات:</h4>
                                <div style="max-height: 200px; overflow-y: auto; border: 1px solid var(--border-color); border-radius: 8px;">
                                    ${paymentsHtml}
                                </div>
                            </div>
                        </div>
                    `,
                    width: 600,
                    confirmButtonText: 'إغلاق',
                    customClass: {
                        popup: 'rtl-popup'
                    }
                });
            } else {
                alert(`تفاصيل العميل: ${client.name}\nالمشروع: ${client.project}\nالوحدة: ${client.unit}\nقيمة العقد: ${formatCurrency(agreementAmount, client.currency)}\nالمدفوع: ${formatCurrency(paidAmount, client.currency)}\nالمتبقي: ${formatCurrency(remainingAmount, client.currency)}\n\nسجل المدفوعات:\n${clientPayments.map(p => `${p.date}: ${formatCurrency(p.amount, client.currency)}`).join('\n')}`);
            }
        } catch (error) {
            console.error('خطأ في عرض تفاصيل العميل:', error);
            showError('حدث خطأ أثناء عرض تفاصيل العميل.');
        }
    }

    
     // يملأ قائمة الفلاتر بالمشاريع الفريدة
     
    function populateFilters() {
        try {
            const clients = getDB('clients');
            const projectFilterSelect = document.getElementById('projectFilter');

            if (!projectFilterSelect) {
                console.error('عنصر فلتر المشاريع (projectFilter) غير موجود.');
                return;
            }

            projectFilterSelect.innerHTML = '<option value="">كل المشاريع</option>';

            if (clients.length > 0) {
                const uniqueProjects = [...new Set(clients.map(c => c.project).filter(Boolean))];
                uniqueProjects.sort().forEach(projectName => {
                    const option = document.createElement('option');
                    option.value = projectName;
                    option.textContent = projectName;
                    projectFilterSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('خطأ في تحديث الفلاتر:', error);
            showError('حدث خطأ أثناء تحميل فلاتر المشاريع.');
        }
    }

    /*
     يصدر بيانات الجدول إلى ملف Excel.
     */
    function exportToExcel() {
        try {
            const table = document.getElementById("contractsTable");
            if (!table) {
                showError('لا يمكن العثور على الجدول للتصدير.');
                return;
            }

            if (typeof XLSX === 'undefined') {
                showError('مكتبة التصدير إلى Excel (XLSX) غير متوفرة. يرجى التأكد من تضمينها.');
                return;
            }

            // استنساخ الجدول لإزالة شريط التقدم والإجراءات لتصدير أنظف
            const tableClone = table.cloneNode(true);

            // تنظيف الجدول المستنسخ
            Array.from(tableClone.querySelectorAll('tbody tr')).forEach(row => {
                if (row.cells.length > 6) {
                    // استبدال شريط التقدم بنص النسبة المئوية
                    const progressCell = row.cells[6];
                    const progressBar = progressCell.querySelector('.progress-bar');
                    if (progressBar) {
                        progressCell.textContent = progressBar.textContent;
                    }

                    // إزالة خلية الإجراءات إذا كانت موجودة
                    if (row.cells.length > 7) {
                        row.deleteCell(7);
                    }
                }
            });

            // إزالة رأس عمود الإجراءات
            const headerRow = tableClone.querySelector('thead tr');
            if (headerRow && headerRow.cells.length > 7) {
                headerRow.deleteCell(7);
            }

            const wb = XLSX.utils.table_to_book(tableClone, { sheet: "العقود النشطة" });
            const fileName = `العقود_النشطة_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, fileName);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'تم التصدير بنجاح',
                    text: 'تم تصدير البيانات إلى ملف Excel بنجاح.',
                    icon: 'success',
                    confirmButtonText: 'حسناً',
                    customClass: {
                        popup: 'rtl-popup'
                    }
                });
            } else {
                alert('تم تصدير البيانات إلى ملف Excel بنجاح.');
            }
        } catch (error) {
            console.error('خطأ في تصدير البيانات:', error);
            showError('حدث خطأ أثناء تصدير البيانات إلى Excel.');
        }
    }

 

    // --- أحداث الصفحة ---
    /**
     * تهيئة الصفحة عند التحميل.
     */
    document.addEventListener('DOMContentLoaded', function () {
        try {
            populateFilters();
            renderContracts();

            // إضافة مستمعي الأحداث للبحث والفلاتر
            const searchInput = document.getElementById('searchInput');
            const projectFilter = document.getElementById('projectFilter');
            const paymentMethodFilter = document.getElementById('paymentMethodFilter');

            if (searchInput) {
                searchInput.addEventListener('input', debounce(renderContracts, 300));
            }

            if (projectFilter) {
                projectFilter.addEventListener('change', renderContracts);
            }

            if (paymentMethodFilter) {
                paymentMethodFilter.addEventListener('change', renderContracts);
            }

        } catch (error) {
            console.error('خطأ في تهيئة الصفحة:', error);
            showError('حدث خطأ أثناء تهيئة الصفحة.');
        }
    });

</script>
@endsection


