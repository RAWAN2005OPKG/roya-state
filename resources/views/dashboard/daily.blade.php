@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
<style>
    /* تعريف المتغيرات الأساسية للألوان والتنسيق */
    :root {
        --primary-color: #4f46e5; /* لون أساسي (أزرق بنفسجي) */
        --primary-hover: #3730a3; /* لون أساسي عند التحويم */
        --secondary-color: #06b6d4; /* لون ثانوي (أزرق سماوي) */
        --white-bg: #ffffff; /* خلفية بيضاء */
        --light-bg: #f8fafc; /* خلفية فاتحة */
        --card-bg: #ffffff; /* خلفية البطاقات */
        --text-color: #1f2937; /* لون النص الأساسي (رمادي داكن) */
        --text-muted: #6b7280; /* لون النص الثانوي/الخافت */
        --border-color: #e5e7eb; /* لون الحدود */
        --success-color: #10b981; /* لون النجاح (أخضر) */
        --danger-color: #ef4444; /* لون الخطر (أحمر) */
        --warning-color: #f59e0b; /* لون التحذير (برتقالي) */
        --info-color: #3b82f6; /* لون المعلومات (أزرق) */
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .search-actions { 
        display: flex; 
        gap: 15px; 
        align-items: center; 
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .search-box { 
        flex: 1; 
        min-width: 250px;
        position: relative;
    }
    .search-box input { 
        width: 100%; 
        padding: 12px 45px 12px 15px; 
        border: 2px solid #ddd; 
        border-radius: 10px;
        font-size: 1rem;
    }
    .search-box i { 
        position: absolute; 
        right: 15px; 
        top: 50%; 
        transform: translateY(-50%); 
        color: #666;
    }
    .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
    
    .form-section { margin-bottom: 35px; }
    /* إعادة تعيين الأنماط الأساسية */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', 'Arial', sans-serif;
    }

    /* أنماط الخلفية العامة */
    body {
        background-color: var(--light-bg);
        color: var(--text-color);
        direction: rtl;
        text-align: right;
        line-height: 1.6;
    }

    .main-content {
        width: 100%;
        max-width: 1400px;
        margin: 20px auto;
        padding: 20px;
        animation: fadeIn 0.6s ease-out;
    }

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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 30px;
        background-color: var(--white-bg);
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
    }

    .page-header h1 {
        font-size: 2.5rem;
        color: var(--text-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header h1 i {
        color: var(--primary-color);
    }

    .page-header p {
        color: var(--text-muted);
        margin-top: 10px;
        font-size: 1.1rem;
    }

    .actions-bar {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    /* أنماط الأزرار */
    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
    }

    .btn-secondary {
        background-color: var(--text-muted);
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    .btn-success {
        background-color: var(--success-color);
        color: #ffffff;
    }

    .btn-success:hover {
        background-color: #059669;
    }

    /* عناصر التحكم بالتقرير */
    .report-controls {
        display: flex;
        gap: 20px;
        align-items: center;
        background-color: var(--white-bg);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .report-controls label {
        font-weight: 600;
        color: var(--text-color);
    }

    .report-controls input[type="date"] {
        padding: 10px 15px;
        background-color: var(--white-bg);
        border: 2px solid var(--border-color);
        color: var(--text-color);
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    .report-controls input[type="date"]:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* شبكة مؤشرات الأداء الرئيسية (KPIs) */
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .kpi-card {
        background-color: var(--white-bg);
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
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
        box-shadow: var(--shadow-lg);
    }

    .kpi-card .label {
        color: var(--text-muted);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 1rem;
        font-weight: 500;
    }

    .kpi-card .label i {
        color: var(--primary-color);
    }

    .kpi-card .value {
        font-size: 2rem;
        font-weight: bold;
        color: var(--text-color);
    }

    /* أقسام التقرير */
    .report-section {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 20px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary-color);
    }

    /* نظام التبويبات */
    .tabs {
        display: flex;
        border-bottom: 2px solid var(--border-color);
        margin-bottom: 25px;
        background-color: var(--light-bg);
        border-radius: 8px 8px 0 0;
        overflow: hidden;
    }

    .tab-button {
        padding: 15px 25px;
        cursor: pointer;
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1rem;
        font-weight: 600;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-button.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
        background-color: var(--white-bg);
    }

    .tab-button:hover {
        color: var(--primary-color);
        background-color: rgba(79, 70, 229, 0.05);
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* شبكة النماذج */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-color);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        background-color: var(--white-bg);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: var(--text-muted);
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 700;
        font-size: 1.1rem;
        background-color: var(--primary-color);
        color: #fff;
        transition: all 0.3s ease;
        grid-column: 1 / -1;
        margin-top: 15px;
    }

    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* الجداول */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--white-bg);
    }

    .data-table th,
    .data-table td {
        padding: 15px 12px;
        text-align: right;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .data-table th {
        background-color: var(--light-bg);
        font-weight: 600;
        color: var(--text-color);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.05);
    }

    .income-row {
        color: var(--success-color);
    }

    .expense-row {
        color: var(--danger-color);
    }

    .comparison-table .diff-up {
        color: var(--success-color);
        font-weight: 600;
    }

    .comparison-table .diff-down {
        color: var(--danger-color);
        font-weight: 600;
    }

    /* المودال */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        animation: modalOpen 0.3s ease-out;
    }

    @keyframes modalOpen {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-content h2 {
        color: var(--primary-color);
        margin-bottom: 20px;
        text-align: center;
        font-size: 1.8rem;
    }

    /* محتوى التقرير اليدوي */
    .manual-report-content p {
        margin-bottom: 15px;
        line-height: 1.7;
        white-space: pre-wrap;
        color: var(--text-color);
    }

    .manual-report-content strong {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* العناصر المخفية */
    .hidden {
        display: none;
    }

    /* تصميم متجاوب */
    @media (max-width: 768px) {
        .main-content {
            padding: 10px;
            margin: 10px auto;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .actions-bar {
            width: 100%;
            justify-content: flex-start;
        }

        .dashboard {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .report-controls {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .tabs {
            flex-direction: column;
        }

        .data-table th,
        .data-table td {
            padding: 10px 8px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-chart-line"></i> لوحة التحكم الرئيسية</h1>
            <p>إدارة العمليات اليومية والتقارير المالية</p>
        </div>
        <div class="actions-bar">
            <button class="btn btn-primary" onclick="openManualReportModal()">
                <i class="fas fa-edit"></i> إضافة تقرير
            </button>
            <button class="btn btn-secondary" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i> تصدير Excel
            </button>
            <button class="btn btn-secondary" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i> تصدير PDF
            </button>
        </div>
    </div>

    @if(session('success'))
    <div style="margin: 15px 0; padding: 12px 16px; border: 1px solid #10b981; background: #ecfdf5; color: #065f46; border-radius: 8px;">
        {{ session('success') }}
    </div>
    @endif

    <!-- عناصر التحكم بالتقرير -->
    <div class="report-controls">
        <label for="reportDate">عرض تقرير يوم:</label>
        <input type="date" id="reportDate">
    </div>

    <!-- ملخص سريع (KPIs) -->
    <div class="report-section">
        <h2 class="section-title"><i class="fas fa-tachometer-alt"></i> ملخص سريع</h2>
        <div class="dashboard">
            <div class="kpi-card">
                <div class="label"><i class="fas fa-coins"></i> إجمالي الإيرادات</div>
                <div class="value" id="totalIncome">0</div>
            </div>
            <div class="kpi-card">
                <div class="label"><i class="fas fa-receipt"></i> إجمالي المصروفات</div>
                <div class="value" id="totalExpenses">0</div>
            </div>
            <div class="kpi-card">
                <div class="label"><i class="fas fa-wallet"></i> صافي التدفق النقدي</div>
                <div class="value" id="netCashFlow">0</div>
            </div>
        </div>
    </div>

    <!-- مقارنة (اليوم مقابل أمس) -->
    <div class="report-section">
    <div class="search-box">
                <input type="text" id="searchInput" placeholder="البحث  ...">
                <i class="fas fa-search"></i>
            </div>
            <div class="action-buttons">
                <button class="btn btn-success" onclick="exportData()">
                    <i class="fas fa-download"></i> تصدير
                </button>
                <button class="btn btn-info" onclick="printForm()">
                    <i class="fas fa-print"></i> طباعة
                </button>
            </div>
        </div>
        <h2 class="section-title"><i class="fas fa-balance-scale"></i> مقارنة (اليوم مقابل أمس)</h2>
        <table class="data-table comparison-table">
            <thead>
                <tr>
                    <th>المؤشر</th>
                    <th>اليوم</th>
                    <th>أمس</th>
                    <th>الفرق</th>
                </tr>
            </thead>
            <tbody id="comparisonBody"></tbody>
        </table>
    </div>


    <!-- سجل الحركات المالية -->
    <div class="report-section">
    <div class="search-box">
                <input type="text" id="searchInput" placeholder="البحث  ...">
                <i class="fas fa-search"></i>
            </div>
            <div class="action-buttons">
                <button class="btn btn-success" onclick="exportData()">
                    <i class="fas fa-download"></i> تصدير
                </button>
                <button class="btn btn-info" onclick="printForm()">
                    <i class="fas fa-print"></i> طباعة
                </button>
            </div>
        </div>
        <h2 class="section-title"><i class="fas fa-list-alt"></i> سجل الحركات المالية</h2>
        <table class="data-table" id="activityTable">
            <thead>
                <tr>
                    <th>الوقت</th>
                    <th>النوع</th>
                    <th>التفاصيل</th>
                    <th>بواسطة/لمن</th>
                    <th>المبلغ</th>
                </tr>
            </thead>
            <tbody id="activityLogList"></tbody>
        </table>
    </div>

    <!-- التقرير الإداري -->
    <div id="manualReportSection" class="report-section" style="display: none;">
        <h2 class="section-title"><i class="fas fa-file-alt"></i> التقرير الإداري</h2>
        <div id="manualReportContent" class="manual-report-content"></div>
    </div>
</main>

<!-- مودال التقرير اليدوي -->
<div id="manualReportModal" class="modal">
    <div class="modal-content">
        <h2>التقرير اليومي</h2>
        <form id="manualReportForm" action="{{ route('dashboard.daily.manual-report.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="manualReportDate">تاريخ التقرير</label>
                <input type="date" id="manualReportDate" name="report_date" required>
            </div>
            <div class="form-group">
                <label for="manualReportAchievements">الإنجاز اليومي</label>
                <textarea id="manualReportAchievements" name="achievements" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="manualReportIssues">العقبات والمشاكل</label>
                <textarea id="manualReportIssues" name="issues" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="manualReportDecisions">القرارات المتخذة بشأن العقبات والمشاكل</label>
                <textarea id="manualReportDecisions" name="decisions" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">حفظ التقرير</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal('manualReportModal')" style="flex: 1;">إلغاء</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    // بيانات وهمية للاختبار - يجب استبدالها ببيانات حقيقية من الخادم
    const salesData = [
        {
            customer_name: "أحمد محمد",
            project_name: "مشروع الأندلس",
            amount_paid: 50000,
            seller_name: "خالد",
            payment_method: "كاش",
            sale_date: new Date().toISOString().split('T')[0],
            notes: "دفعة أولى"
        }
    ];

    const expensesData = [
        {
            payee: "شركة الكهرباء",
            category: "فواتير",
            amount: 1500,
            paid_by: "محمد",
            payment_method: "معاملة بنكية",
            expense_date: new Date().toISOString().split('T')[0]
        }
    ];

    const manualReportsData = [];

    // دوال المساعدة
    function formatCurrency(num) {
        return new Intl.NumberFormat('ar-SA', { 
            minimumFractionDigits: 0, 
            maximumFractionDigits: 2 
        }).format(num || 0) + ' شيكل';
    }

    function isSameDay(date1, date2) {
        return date1.getFullYear() === date2.getFullYear() && 
               date1.getMonth() === date2.getMonth() && 
               date1.getDate() === date2.getDate();
    }

    function getFinancialsForDate(date) {
        const income = salesData.filter(p => isSameDay(new Date(p.sale_date), date))
                                .reduce((sum, p) => sum + parseFloat(p.amount_paid || 0), 0);
        const totalExpenses = expensesData.filter(p => isSameDay(new Date(p.expense_date), date))
                                         .reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
        return { income, expenses: totalExpenses, net: income - totalExpenses };
    }

    // دالة فتح التبويبات
    function openTab(evt, tabName) {
        document.querySelectorAll('.tab-content').forEach(tc => tc.style.display = "none");
        document.querySelectorAll('.tab-button').forEach(tb => tb.classList.remove('active'));
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.classList.add('active');
    }

    // دالة توليد التقرير
    function generateReport(selectedDateStr) {
        const selectedDate = new Date(selectedDateStr);

        // حساب البيانات المالية لليوم المحدد
        const todayFinancials = getFinancialsForDate(selectedDate);
        document.getElementById('totalIncome').textContent = formatCurrency(todayFinancials.income);
        document.getElementById('totalExpenses').textContent = formatCurrency(todayFinancials.expenses);
        document.getElementById('netCashFlow').textContent = formatCurrency(todayFinancials.net);
        document.getElementById('netCashFlow').style.color = todayFinancials.net >= 0 ? 'var(--success-color)' : 'var(--danger-color)';

        // حساب البيانات المالية لأمس
        const yesterday = new Date(selectedDate);
        yesterday.setDate(yesterday.getDate() - 1);
        const yesterdayFinancials = getFinancialsForDate(yesterday);

        // تحديث جدول المقارنة
        const comparisonBody = document.getElementById('comparisonBody');
        comparisonBody.innerHTML = '';

        function createComparisonRow(label, todayValue, yesterdayValue) {
            const diff = todayValue - yesterdayValue;
            const diffText = diff > 0 ? `↑ ${formatCurrency(diff)}` : 
                           diff < 0 ? `↓ ${formatCurrency(Math.abs(diff))}` : `-`;
            const diffClass = diff > 0 ? 'diff-up' : diff < 0 ? 'diff-down' : '';
            return `<tr>
                <td>${label}</td>
                <td>${formatCurrency(todayValue)}</td>
                <td>${formatCurrency(yesterdayValue)}</td>
                <td class="${diffClass}">${diffText}</td>
            </tr>`;
        }

        comparisonBody.innerHTML += createComparisonRow('الإيرادات', todayFinancials.income, yesterdayFinancials.income);
        comparisonBody.innerHTML += createComparisonRow('المصروفات', todayFinancials.expenses, yesterdayFinancials.expenses);

        // تحديث سجل الحركات المالية
        const activityLogList = document.getElementById('activityLogList');
        activityLogList.innerHTML = '';
        let activities = [];

        // إضافة الإيرادات
        salesData.filter(p => isSameDay(new Date(p.sale_date), selectedDate)).forEach(p => {
            activities.push({
                time: new Date(p.sale_date),
                type: 'إيراد',
                details: `استلام دفعة من ${p.customer_name} - ${p.project_name}`,
                person: p.seller_name,
                amount: formatCurrency(p.amount_paid),
                class: 'income-row'
            });
        });

        // إضافة المصروفات
        expensesData.filter(p => isSameDay(new Date(p.expense_date), selectedDate)).forEach(p => {
            activities.push({
                time: new Date(p.expense_date),
                type: 'مصروف',
                details: `دفع لـ ${p.payee} - ${p.category}`,
                person: p.paid_by,
                amount: formatCurrency(p.amount),
                class: 'expense-row'
            });
        });

        if (activities.length === 0) {
            activityLogList.innerHTML = '<tr><td colspan="5" style="text-align:center; color: var(--text-muted);">لا توجد حركات مالية مسجلة لهذا اليوم.</td></tr>';
        } else {
            activities.sort((a, b) => a.time - b.time).forEach(act => {
                activityLogList.innerHTML += `<tr class="${act.class}">
                    <td>${act.time.toLocaleTimeString('ar-EG')}</td>
                    <td>${act.type}</td>
                    <td>${act.details}</td>
                    <td>${act.person}</td>
                    <td>${act.amount}</td>
                </tr>`;
            });
        }

        // عرض التقرير اليدوي إذا كان موجوداً
        const reportForDay = manualReportsData.find(r => r.report_date === selectedDateStr);
        const manualSection = document.getElementById('manualReportSection');
        if (reportForDay) {
            manualSection.style.display = 'block';
            document.getElementById('manualReportContent').innerHTML = `
                <p><strong>الإنجازات:</strong>\n${reportForDay.achievements || 'لم تسجل'}</p>
                <p><strong>العقبات:</strong>\n${reportForDay.issues || 'لم تسجل'}</p>
                <p><strong>القرارات:</strong>\n${reportForDay.decisions || 'لم تسجل'}</p>
            `;
        } else {
            manualSection.style.display = 'none';
        }
    }

    // دوال المودال
    function openManualReportModal() {
        document.getElementById('manualReportForm').reset();
        document.getElementById('manualReportDate').value = document.getElementById('reportDate').value;
        openModal('manualReportModal');
    }

    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    }

    function closeModal(modalId) {
        if (modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.style.display = 'none';
        } else {
            document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
        }
    }

    // دالة حفظ التقرير اليدوي
    function saveManualReport(e) {
        e.preventDefault();
        const reportDate = document.getElementById('manualReportDate').value;
        const achievements = document.getElementById('manualReportAchievements').value;
        const issues = document.getElementById('manualReportIssues').value;
        const decisions = document.getElementById('manualReportDecisions').value;

        // إضافة التقرير إلى البيانات المحلية (في التطبيق الحقيقي، يتم إرساله للخادم)
        const existingReportIndex = manualReportsData.findIndex(r => r.report_date === reportDate);
        const reportData = { report_date: reportDate, achievements, issues, decisions };
        
        if (existingReportIndex > -1) {
            manualReportsData[existingReportIndex] = reportData;
        } else {
            manualReportsData.push(reportData);
        }

        // عرض رسالة نجاح
        if (typeof Swal !== 'undefined') {
            Swal.fire('تم', 'تم حفظ التقرير بنجاح', 'success');
        } else {
            alert('تم حفظ التقرير بنجاح');
        }

        closeModal('manualReportModal');
        generateReport(reportDate);
    }

    // دوال التصدير
    function exportToExcel() {
        if (typeof XLSX === 'undefined') {
            alert('مكتبة التصدير إلى Excel غير متوفرة');
            return;
        }

        const wb = XLSX.utils.book_new();
        const activitySheet = XLSX.utils.table_to_sheet(document.getElementById("activityTable"));
        XLSX.utils.book_append_sheet(wb, activitySheet, "الحركات المالية");
        const comparisonSheet = XLSX.utils.table_to_sheet(document.querySelector(".comparison-table"));
        XLSX.utils.book_append_sheet(wb, comparisonSheet, "المقارنة");
        XLSX.writeFile(wb, `التقرير_اليومي_${document.getElementById('reportDate').value}.xlsx`);
    }

    function exportToPDF() {
        if (typeof html2canvas === 'undefined' || typeof jsPDF === 'undefined') {
            alert('مكتبات التصدير إلى PDF غير متوفرة');
            return;
        }

        const reportElement = document.querySelector('.main-content');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({ 
                title: 'جاري التجهيز...', 
                allowOutsideClick: false, 
                didOpen: () => Swal.showLoading() 
            });
        }

        html2canvas(reportElement, { scale: 2, useCORS: true }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF({ orientation: 'p', unit: 'mm', format: 'a4' });
            const pageHeight = pdf.internal.pageSize.getHeight();
            const pageWidth = pdf.internal.pageSize.getWidth();
            const imgHeight = canvas.height * pageWidth / canvas.width;
            let heightLeft = imgHeight;
            let position = 0;

            pdf.addImage(imgData, 'PNG', 0, position, pageWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 0, position, pageWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            pdf.save(`التقرير_اليومي_${document.getElementById('reportDate').value}.pdf`);
            
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
        });
    }

    // التهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تعيين التاريخ الحالي
        const reportDateInput = document.getElementById('reportDate');
        const revenueDate = document.getElementById('revenueDate');
        const expenseDate = document.getElementById('expenseDate');
        
        const today = new Date().toISOString().split('T')[0];
        if (reportDateInput) reportDateInput.value = today;
        if (revenueDate) revenueDate.value = today;
        if (expenseDate) expenseDate.value = today;

        // توليد التقرير الأولي
        generateReport(today);

        // إضافة مستمع لتغيير التاريخ
        if (reportDateInput) {
            reportDateInput.addEventListener('change', (e) => generateReport(e.target.value));
        }

        // مزامنة تاريخ المودال مع تاريخ التقرير واترك الإرسال للباك إند
        const manualForm = document.getElementById('manualReportForm');
        if (manualForm) {
            const dateInput = document.getElementById('manualReportDate');
            if (dateInput && reportDateInput) {
                dateInput.value = reportDateInput.value;
            }
        }

        // إعداد أحداث طرق الدفع
        const revPaymentMethod = document.getElementById('revenuePaymentMethod');
        const revBankDetailsGroup = document.getElementById('revenueBankDetailsGroup');
        if (revPaymentMethod && revBankDetailsGroup) {
            revPaymentMethod.addEventListener('change', () => {
                revBankDetailsGroup.classList.toggle('hidden', revPaymentMethod.value !== 'معاملة بنكية');
            });
        }

        const expPaymentMethod = document.getElementById('expensePaymentMethod');
        const expBankDetailsGroup = document.getElementById('expenseBankDetailsGroup');
        if (expPaymentMethod && expBankDetailsGroup) {
            expPaymentMethod.addEventListener('change', () => {
                expBankDetailsGroup.classList.toggle('hidden', expPaymentMethod.value !== 'معاملة بنكية');
            });
        }

        // إغلاق المودال عند النقر خارجه
        window.addEventListener('click', (event) => {
            if (event.target.classList.contains('modal')) {
                closeModal(event.target.id);
            }
        });
    });
</script>
@endsection
