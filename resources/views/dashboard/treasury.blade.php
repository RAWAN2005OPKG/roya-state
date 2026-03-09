@extends('layouts.container')
@section('title', 'الخزينة العامة والسجل المالي')

@section('styles')
<style>
    :root {
        --primary-color: #2c67f2;
        --primary-hover: #2352c0;
        --secondary-color: #6c757d;
        --bg-color: #f4f7fc;
        --surface-color: #ffffff;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --border-color: #e2e8f0;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #3b82f6;
        --font-family: 'Cairo', sans-serif;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -2px rgb(0 0 0 / 0.1);
        --border-radius: 0.75rem;
    }

    body {
        background-color: var(--bg-color);
        color: var(--text-primary);
        font-family: var(--font-family);
        margin: 0;
        direction: rtl;
    }

    .main-content {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .page-header h1 {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    /* بطاقات الملخصات العلوية */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .kpi-card {
        background-color: var(--surface-color);
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        text-align: center;
    }
    .kpi-card .label {
        font-size: 1rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }
    .kpi-card .value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .kpi-card.main {
        grid-column: 1 / -1;
        background: linear-gradient(45deg, var(--primary-color), #5a88f5);
        color: white;
    }
    .kpi-card.main .label { color: rgba(255,255,255,0.8); }
    .kpi-card.main .value { color: white; font-size: 2.5rem; }

    /* بطاقات الأقسام الفرعية */
    .sub-box {
        background-color: var(--surface-color);
        padding: 1.5rem;
        border-radius: var(--border-radius);
        text-align: center;
        text-decoration: none;
        color: var(--text-primary);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .sub-box:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        text-decoration: none;
        color: var(--primary-color);
    }
    .sub-box .icon { font-size: 2rem; }
    .sub-box .label { font-weight: 600; font-size: 1.1rem; }

    /* حاوية الإجراءات والجدول */
    .card {
        background-color: var(--surface-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border-color);
        margin-bottom: 2.5rem;
    }
    .card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
    }
    .card-toolbar { display: flex; flex-wrap: wrap; gap: 0.75rem; }
    .card-body { padding: 1.5rem; }

    /* تصميم الأزرار */
    .btn {
        padding: 0.75rem 1.25rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary { background-color: var(--primary-color); color: white; }
    .btn-primary:hover { background-color: var(--primary-hover); }
    .btn-secondary { background-color: #f1f5f9; color: var(--text-primary); border: 1px solid var(--border-color); }
    .btn-secondary:hover { background-color: #e2e8f0; }
    .btn-light-primary { background-color: #e9effd; color: var(--primary-color); }
    .btn-light-primary:hover { background-color: #dce6fa; }

    /* تصميم القائمة المنسدلة (Dropdown) */
    .dropdown { position: relative; display: inline-block; }
    .dropdown-menu {
        display: none;
        position: absolute;
        left: 0;
        background-color: var(--surface-color);
        min-width: 180px;
        box-shadow: var(--shadow-lg);
        z-index: 10;
        border-radius: 0.5rem;
        padding: 0.5rem 0;
        border: 1px solid var(--border-color);
    }
    .dropdown-menu a {
        color: var(--text-primary);
        padding: 0.75rem 1.25rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
    }
    .dropdown-menu a:hover { background-color: #f9fafb; }
    .dropdown:hover .dropdown-menu { display: block; }

    /* تصميم الجدول */
    .table-wrapper { overflow-x: auto; }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: right;
    }
    th, td {
        padding: 1rem;
        white-space: nowrap;
        border-bottom: 1px solid var(--border-color);
    }
    th {
        background-color: #f8fafc;
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
    }
    tbody tr:hover { background-color: #f9fafb; }
    .badge {
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .badge-success { background-color: #d1fae5; color: #065f46; }
    .badge-danger { background-color: #fee2e2; color: #991b1b; }
    .badge-warning { background-color: #fef3c7; color: #92400e; }
    .badge-info { background-color: #dbeafe; color: #1e40af; }

    /* تصميم النوافذ المنبثقة (Modal) */
    .modal {
        display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%;
        background-color: rgba(30, 41, 59, 0.5); backdrop-filter: blur(4px);
        align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.3s ease;
    }
    .modal.show { display: flex; opacity: 1; }
    .modal-content {
        background-color: var(--surface-color); padding: 0; border-radius: var(--border-radius);
        width: 95%; max-width: 700px; max-height: 90vh;
        box-shadow: var(--shadow-lg);
        transform: scale(0.95); transition: transform 0.3s ease;
        display: flex; flex-direction: column;
    }
    .modal.show .modal-content { transform: scale(1); }
    .modal-header {
        padding: 1.5rem 2rem;
        display: flex; justify-content: space-between; align-items: center;
        border-bottom: 1px solid var(--border-color);
    }
    .modal-header h2 { font-size: 1.5rem; font-weight: 700; margin: 0; }
    .modal-header .close-btn { background: none; border: none; font-size: 1.75rem; cursor: pointer; color: var(--text-secondary); }
    .modal-body {
        padding: 2rem;
        overflow-y: auto;
    }
    .modal-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .modal-form-grid .form-group {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.75rem;
        align-items: center;
    }
    .modal-form-grid .form-group label {
        order: 2;
        white-space: nowrap;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .modal-form-grid .form-group input,
    .modal-form-grid .form-group select,
    .modal-form-grid .form-group textarea {
        order: 1;
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
    }
    .modal-form-grid .form-group.full-width {
        grid-column: 1 / -1;
    }
    .modal-form-grid .form-group.full-width textarea {
        height: 80px;
    }
    .modal-footer {
        padding: 1.5rem 2rem;
        display: flex; gap: 0.75rem; justify-content: flex-start;
        border-top: 1px solid var(--border-color);
        background-color: #f9fafb;
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> الخزينة والسجل المالي</h1>
    </div>

    <!-- بطاقات الملخصات -->
    <div class="kpi-grid">
        <div class="kpi-card main">
            <div class="label">إجمالي السيولة المتاحة (كاش + بنك)</div>
            <div class="value" id="totalLiquidity">
                {{ number_format($totalLiquidity ?? 0, 2) }} شيكل
            </div>
        </div>
    </div>

    <!-- بطاقات الأقسام الفرعية مع الروابط الصحيحة -->
    <div class="kpi-grid">
        <a href="{{ route('dashboard.cash.index') }}" class="sub-box">
            <div class="icon" style="color: var(--success-color);"><i class="fas fa-money-bill-wave"></i></div>
            <div class="label">صندوق الكاش</div>
        </a>
        <a href="{{ route('dashboard.bank.index') }}" class="sub-box">
            <div class="icon" style="color: var(--info-color);"><i class="fas fa-landmark"></i></div>
            <div class="label">الحسابات البنكية</div>
        </a>
        <a href="{{ route('dashboard.cheques.index') }}" class="sub-box">
            <div class="icon" style="color: var(--warning-color);"><i class="fas fa-money-check-alt"></i></div>
            <div class="label">محفظة الشيكات</div>
        </a>
        <a href="{{ route('dashboard.payments.index') }}" class="sub-box">
            <div class="icon" style="color: var(--danger-color);"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="label">سندات القبض والصرف</div>
        </a>
    </div>

    <!-- حاوية الأزرار العلوية -->
    <div class="actions-container">
        <button class="btn btn-light-primary" onclick="openModal('fundsTransferModal')"><i class="fas fa-exchange-alt"></i> تحويل بين الصناديق</button>
        <button class="btn btn-light-primary" onclick="openModal('projectTransferModal')"><i class="fas fa-project-diagram"></i> تحويل بين المشاريع</button>
<a href="{{ route('dashboard.add_transaction.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة سجل جديد</a>
    </div>

    <!-- بطاقة السجل المالي الموحد -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">السجل المالي الموحد</h2>
        </div>
        <div class="card-body">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>نوع السجل</th>
                            <th>تفاصيل الحركة</th>
                            <th>نوع الحركة</th>
                            <th>المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($all_transactions) && $all_transactions->count() > 0)
                            @foreach ($all_transactions as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                                    <td>
                                        @if($transaction->type_label == 'حركة كاش') <span class="badge badge-success">كاش</span>
                                        @elseif($transaction->type_label == 'حركة بنك') <span class="badge badge-info">بنك</span>
                                        @elseif($transaction->type_label == 'شيك') <span class="badge badge-warning">شيك</span>
                                        @else {{ $transaction->type_label }} @endif
                                    </td>
                                    <td>{{ $transaction->details }}</td>
                                    <td>{{ $transaction->move_type }}</td>
                                    <td style="color: {{ $transaction->signed_amount >= 0 ? 'var(--success-color)' : 'var(--danger-color)' }}; font-weight: 600;">{{ number_format(abs($transaction->amount), 2) }} {{ $transaction->currency }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-secondary);">لا توجد حركات مالية لعرضها حالياً.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if(isset($all_transactions))
            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">{{ $all_transactions->links() }}</div>
            @endif
        </div>
    </div>
</main>

<!-- النوافذ المنبثقة (Modals) -->
<div id="fundsTransferModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-exchange-alt"></i> تحويل بين الصناديق</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="fundsTransferForm" action="{{ route('dashboard.funds-transfers.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-grid">
                    <div class="form-group"><input type="text" id="contactName" name="name" required><label for="contactName">الاسم *</label></div>
                    <div class="form-group"><input type="date" id="transferDate" name="date" required><label for="transferDate">التاريخ *</label></div>
                    <div class="form-group"><input type="text" id="transferPhone" name="phone"><label for="transferPhone">رقم الجوال</label></div>
                    <div class="form-group"><input type="text" id="transferIdNumber" name="id_number"><label for="transferIdNumber">رقم الهوية</label></div>
                    <div class="form-group"><select id="toAccount" name="to_account" required><option value="">اختر الصندوق</option><option value="cash">صندوق الكاش</option><option value="bank">الحساب البنكي</option></select><label for="toAccount">إلى الصندوق *</label></div>
                    <div class="form-group"><select id="fromAccount" name="from_account" required><option value="">اختر الصندوق</option><option value="cash">صندوق الكاش</option><option value="bank">الحساب البنكي</option></select><label for="fromAccount">من الصندوق *</label></div>
                    <div class="form-group"><select id="currency" name="currency" required><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select><label for="currency">العملة</label></div>
                    <div class="form-group"><input type="number" id="transferAmount" name="amount" step="0.01" min="0" required><label for="transferAmount">المبلغ *</label></div>
                    <div class="form-group full-width"><textarea id="transferNotes" name="notes" rows="3"></textarea><label for="transferNotes">ملاحظات</label></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">تنفيذ التحويل</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<div id="projectTransferModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-project-diagram"></i> تحويل بين المشاريع</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <form id="projectTransferForm" action="{{ route('dashboard.project-transfers.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="modal-form-grid">
                    <div class="form-group"><input type="text" id="projectContactName" name="name" required><label for="projectContactName">الاسم *</label></div>
                    <div class="form-group"><input type="date" id="projectTransferDate" name="date" required><label for="projectTransferDate">التاريخ *</label></div>
                    <div class="form-group"><input type="text" id="projectTransferPhone" name="phone" required><label for="projectTransferPhone">رقم الجوال</label></div>
                    <div class="form-group"><input type="text" id="projectTransferIdNumber" name="id_number" required><label for="projectTransferIdNumber">رقم الهوية</label></div>
                    <div class="form-group"><select id="toProject" name="to_project_id" required><option value="">اختر المشروع...</option></select><label for="toProject">إلى المشروع *</label></div>
                    <div class="form-group"><select id="fromProject" name="from_project_id" required><option value="">اختر المشروع...</option></select><label for="fromProject">من المشروع *</label></div>
                    <div class="form-group"><select id="projectTransferCurrency" name="currency" required><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select><label for="projectTransferCurrency">العملة</label></div>
                    <div class="form-group"><input type="number" id="projectTransferAmount" name="amount" step="0.01" min="0" required><label for="projectTransferAmount">المبلغ *</label></div>
                    <div class="form-group full-width"><textarea id="projectTransferNotes" name="notes" rows="3"></textarea><label for="projectTransferNotes">ملاحظات</label></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">تنفيذ التحويل</button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()">إلغاء</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            const dateInput = modal.querySelector('input[type="date"]');
            if (dateInput && !dateInput.value) {
                dateInput.valueAsDate = new Date();
            }
        }
    }

    function closeModal() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('show');
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal();
        }
    });
</script>
@endpush
