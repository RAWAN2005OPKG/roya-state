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

    .background {
        background-color: var(--light-bg);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        direction: rtl;
        padding: 20px;
        overflow-y: auto;
    }

    /* حاوية النموذج */
    .form-container {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        width: 100%;
        max-width: 950px;
        border: 1px solid var(--border-color);
    }

    /* رأس النموذج */
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
    }

    .header-text h1 {
        font-size: 1.8rem;
        color: var(--text-color);
        margin: 0;
    }

    .header-text p {
        font-size: 1rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* أقسام النموذج */
    .form-section {
        margin-bottom: 35px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .section-header i {
        margin-left: 5px;
    }

    .section-header h3 {
        margin: 0;
        font-weight: 600;
        color: var(--primary-color);
    }

    /* شبكة النموذج */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-color);
    }

    .form-group label.required::after {
        content: '*';
        color: var(--danger-color);
        margin-right: 5px;
    }

    /* أنماط حقول الإدخال */
    input, select, textarea {
        width: 100%;
        padding: 12px 15px;
        background-color: var(--white-bg);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    input::placeholder, textarea::placeholder {
        color: var(--text-muted);
    }

    input[readonly] {
        background-color: var(--light-bg);
        color: var(--text-muted);
        cursor: not-allowed;
        border-style: dashed;
    }

    /* حقل الإدخال مع العملة */
    .input-with-currency {
        position: relative;
    }

    .input-with-currency .currency {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .input-with-currency input {
        padding-left: 70px;
    }

    /* الأقسام الديناميكية والمخفية */
    .dynamic-section, .hidden-section {
        display: none;
        background-color: var(--light-bg);
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
        grid-column: 1 / -1;
        border: 1px solid var(--border-color);
    }

    /* مجموعات قوائم التحقق */
    .checklist-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 10px;
    }

    .checklist-item {
        display: flex;
        align-items: center;
        color: var(--text-color);
    }

    .checklist-item input {
        margin-left: 10px;
        width: 18px;
        height: 18px;
    }

    .checklist-item label {
        color: var(--text-color);
        font-weight: normal;
    }

    /* منطقة تحميل الملفات */
    .file-upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background-color: var(--light-bg);
    }

    .file-upload-area:hover, .file-upload-area.drag-over {
        border-color: var(--primary-color);
        background-color: rgba(79, 70, 229, 0.05);
    }

    .upload-content i {
        font-size: 3rem;
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .upload-content p {
        margin: 0;
        font-size: 1.1rem;
        color: var(--text-color);
    }

    .upload-content .file-types {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* معاينة الوسائط */
    .media-preview {
        position: relative;
        max-width: 100%;
        margin-top: 15px;
    }

    .media-preview img, .media-preview video {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .remove-media {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .remove-media:hover {
        background-color: var(--danger-color);
        transform: scale(1.1);
    }

    /* أزرار الإجراءات */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
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

    /* أنماط SweetAlert2 */
    .swal2-popup {
        background: var(--white-bg) !important;
        color: var(--text-color) !important;
        border: 1px solid var(--border-color);
    }

    .swal2-title {
        color: var(--primary-color) !important;
    }

    .swal2-html-container {
        color: var(--text-muted) !important;
    }

    .swal2-confirm {
        background-color: var(--primary-color) !important;
        color: white !important;
    }

    .swal2-cancel {
        background-color: var(--text-muted) !important;
        color: #ffffff !important;
    }

    /* أنماط المودال */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: var(--white-bg);
        margin: auto;
        padding: 30px;
        border: 1px solid var(--border-color);
        width: 90%;
        max-width: 700px;
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        position: relative;
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
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--text-muted);
        text-decoration: none;
        margin-bottom: 15px;
        transition: color 0.3s ease;
    }

    .btn-back:hover {
        color: var(--primary-color);
    }

    /* أنماط لوحة التحكم الرئيسية */
    .main-content {
        width: 100%;
        max-width: 1600px;
        margin: 40px auto;
        padding: 0 20px;
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

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding: 30px;
        background-color: var(--white-bg);
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
    }

    .page-header h1 {
        font-size: 2.8rem;
        color: var(--text-color);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-header h1 i {
        font-size: 2.5rem;
        color: var(--primary-color);
    }

    .header-actions {
        display: flex;
        gap: 15px;
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .kpi-card {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow);
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
        font-size: 1.1rem;
        font-weight: 500;
    }

    .kpi-card .value {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-color);
        line-height: 1.2;
    }

    .table-container {
        background-color: var(--white-bg);
        padding: 35px;
        border-radius: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-bottom: 40px;
    }

    .container-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .container-title {
        font-size: 1.8rem;
        color: var(--text-color);
        margin: 0;
    }

    .table-wrapper {
        overflow-x: auto;
    }

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
        color: var(--text-color);
    }

    .data-table th {
        font-size: 1.05rem;
        color: var(--text-muted);
        font-weight: 600;
        background-color: var(--light-bg);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .data-table tbody tr {
        transition: all 0.3s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.05);
        transform: scale(1.01);
    }

    .report-section {
        background-color: var(--white-bg);
        padding: 35px;
        border-radius: 20px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        margin-top: 40px;
    }

    .report-section .page-header {
        background: none;
        padding: 0;
        margin-bottom: 25px;
        box-shadow: none;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }

    .report-section .page-header h2 {
        font-size: 2rem;
        color: var(--text-color);
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .main-content {
            padding: 0 10px;
            margin: 20px auto;
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

        .kpi-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .container-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .data-table th, .data-table td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')

<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة العملاء</h1>
        <div class="header-actions">
        <button class="btn btn-primary" onclick="openClientModal()"><i class="fas fa-user-plus"></i> إضافة عميل جديد</button>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="label">إجمالي عدد العملاء</div>
            <div class="value" id="kpiTotalClients">0</div>
        </div>
        <div class="kpi-card">
            <div class="label">إجمالي قيمة الاتفاقيات</div>
            <div class="value" id="kpiTotalAgreements">0</div>
        </div>
        <div class="kpi-card">
            <div class="label">إجمالي المبالغ المدفوعة</div>
            <div class="value" id="kpiTotalPaid">0</div>
        </div>
        <div class="kpi-card">
            <div class="label">إجمالي المبالغ المتبقية</div>
            <div class="value" id="kpiTotalRemaining" style="color: var(--danger-color);">0</div>
        </div>
    </div>


	<!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
<h3 class="card-label">قائمة العملاء
<span class="d-block text-muted pt-2 font-size-sm">advance header options</span></h3>
            </div>
            <div class="card-toolbar">
<!--begin::Dropdown-->
<div class="dropdown dropdown-inline mr-2">
<button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<span class="svg-icon svg-icon-md">
<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<rect x="0" y="0" width="24" height="24" />
<path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
<path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
</g>
</svg>
<!--end::Svg Icon-->
</span>Export</button>
<!--begin::Dropdown Menu-->
<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
<!--begin::Navigation-->
<ul class="navi flex-column navi-hover py-2">
<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Choose an option:</li>
<li class="navi-item">
<a href="#" class="navi-link">
<span class="navi-icon">
<i class="la la-print"></i>
</span>
<span class="navi-text">Print</span>
</a>
</li>
<li class="navi-item">
<a href="#" class="navi-link">
<span class="navi-icon">
<i class="la la-copy"></i>
</span>
<span class="navi-text">Copy</span>
</a>
</li>
<li class="navi-item">
<a href="#" class="navi-link">
<span class="navi-icon">
<i class="la la-file-excel-o"></i>
</span>
<span class="navi-text">Excel</span>
</a>
</li>
<li class="navi-item">
<a href="#" class="navi-link">
<span class="navi-icon">
<i class="la la-file-text-o"></i>
</span>
<span class="navi-text">CSV</span>
</a>
</li>
<li class="navi-item">
<a href="#" class="navi-link">
<span class="navi-icon">
<i class="la la-file-pdf-o"></i>
</span>
<span class="navi-text">PDF</span>
</a>
</li>
</ul>
<!--end::Navigation-->
</div>
<!--end::Dropdown Menu--></div>
<!--end::Dropdown-->
<!--begin::Button-->
<a href="#" class="btn btn-primary font-weight-bolder">
<span class="svg-icon svg-icon-md">
<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
<rect x="0" y="0" width="24" height="24" />
<circle fill="#000000" cx="9" cy="15" r="6" />
<path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
</g>
</svg>
<!--end::Svg Icon-->
</span>New Record</a>
<!--end::Button-->
</div>
</div>
<div class="card-body">
<!--begin: Datatable-->
<table class="table table-bordered table-hover table-checkable mt-10" id="kt_datatable">
<thead>
<tr>
<th colspan="2">Order Information</th>
<th colspan="3">Shipping Information</th>
<th colspan="3">Agent Information</th>
<th colspan="3">Stats</th>
</tr>
<tr>
<th>Record ID</th>
<th>Order ID</th>
<th>Country</th>
<th>Ship City</th>
<th>Ship Address</th>
<th>Company Agent</th>
<th>Company Name</th>
<th>Ship Date</th>
<th>Status</th>
<th>Type</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<tr>
<td>1</td>
<td>64616-103</td>
<td>Brazil</td>
<td>São Félix do Xingu</td>
<td>698 Oriole Pass</td>
<td>Hayes Boule</td>
<td>Casper-Kerluke</td>
<td>10/15/2017</td>
<td>5</td>
<td>1</td>
<td nowrap="nowrap"></td>
</tr>
<tr>
<td>2</td>
<td>54868-3377</td>
<td>Vietnam</td>
<td>Bình Minh</td>
<td>8998 Delaware Court</td>
<td>Humbert Bresnen</td>
<td>Hodkiewicz and Sons</td>
<td>4/24/2016</td>
<td>2</td>
<td>2</td>
<td nowrap="nowrap"></td>
</tr>
</tbody>
</table>
<!--end: Datatable-->
</div>
</div>
    <!--end::Card-->

</main>

<!-- Modal لإضافة/تعديل عميل -->
<div id="clientModal" class="modal">
    <div class="modal-content">
        <h2 id="clientModalTitle">إضافة عميل جديد</h2>
        <a href="#" class="btn-back" onclick="closeClientModal(); return false;"><i class="fas fa-arrow-right"></i> العودة لصفحة العميل</a>
        <form id="clientForm" action="{{ route('dashboard.customers.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="clientId">
            <div class="form-grid">
                <div class="form-group"><label for="dueDate">تاريخ الاستحقاق</label><input type="date" id="dueDate" required></div>
                <div class="form-group"><label for="clientName">اسم العميل</label><input type="text" id="clientName" required></div>
                <div class="form-group"><label for="clientPhone">الجوال</label><input type="tel" id="clientPhone"></div>
                <div class="form-group"><label for="clientProject">المشروع</label><select id="clientProject" required></select></div>
                <div class="form-group"><label for="clientUnit">الوحدة</label><input type="text" id="clientUnit" placeholder="مثال: شقة 101، محل 3" required></div>
                <div class="form-group">
                    <label for="clientAgreementAmount" class="required">المبلغ الإجمالي للاتفاقية</label>
                    <input type="number" id="clientAgreementAmount" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="client_payment_method" class="required">طريقة الدفع الرئيسية</label>
                    <select id="client_payment_method" name="client_payment_method" required>
                        <option value="">اختر طريقة الدفع</option>
                        <option value="cash">نقدي</option>
                        <option value="bank_transaction">معاملة بنكية</option>
                        <option value="check">شيك</option>
                        <option value="installments">تقسيط</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="client_currency" class="required">نوع العملة</label>
                    <select id="client_currency" name="client_currency" required>
                        <option value="شيكل">شيكل</option>
                        <option value="دينار">دينار</option>
                        <option value="دولار">دولار</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="client_paid_to">تم الدفع لمن (الدفعة الأولى)</label>
                    <select id="client_paid_to" name="client_paid_to">
                        <option value="">اختر الشخص</option>
                        <option value="محمد">محمد</option>
                        <option value="خالد">خالد</option>
                        <option value="other">أخرى (حدد)</option>
                    </select>
                </div>
                <div class="form-group" id="clientOtherPaidToGroup" style="display: none;">
                    <label for="clientOtherPaidTo">اسم المستلم</label>
                    <input type="text" id="clientOtherPaidTo" placeholder="اكتب اسم المستلم">
                </div>

                <!-- قسم تفاصيل البنك الديناميكي للعميل -->
                <div id="bankDetailsSection" class="dynamic-section">
                    <h3 class="section-header"><i class="fas fa-bank"></i> تفاصيل المعاملة البنكية</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bankName">اختر البنك</label>
                            <select id="bankName">
                                <option value="">-- اختر من القائمة --</option>
                                <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                <option value="بنك الصفا">بنك الصفا</option>
                                <option value="بنك فلسطين">بنك فلسطين</option>
                                <option value="البنك العربي">البنك العربي</option>
                                <option value="other">أخرى (حدد)</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherBankNameGroup" style="display: none;">
                            <label for="otherBankName">اسم البنك</label>
                            <input type="text" id="otherBankName" placeholder="اكتب اسم البنك هنا">
                        </div>
                        <div class="form-group" id="otherBankBranchGroup" style="display: none;">
                            <label for="otherBankBranch">اسم فرع البنك</label>
                            <input type="text" id="otherBankBranch" placeholder="اكتب اسم الفرع هنا">
                        </div>
                    </div>
                </div>

                <!-- قسم تفاصيل الشيك الديناميكي للعميل -->
                <div id="checkDetailsSection" class="dynamic-section">
                    <h3 class="section-header"><i class="fas fa-money-check-alt"></i> تفاصيل الشيك</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="checkNumber">رقم الشيك <span class="required">*</span></label>
                            <input type="text" id="checkNumber">
                        </div>
                        <div class="form-group">
                            <label for="checkBank">البنك المصدر <span class="required">*</span></label>
                            <input type="text" id="checkBank">
                        </div>
                        <div class="form-group">
                            <label for="checkDueDate">تاريخ الاستحقاق <span class="required">*</span></label>
                            <input type="date" id="checkDueDate">
                        </div>
                        <div class="form-group">
                            <label for="checkReceiptDate">تاريخ الاستلام <span class="required">*</span></label>
                            <input type="date" id="checkReceiptDate">
                        </div>
                    </div>
                </div>

                <div class="form-group full-width"><label for="clientContractFile">صورة العقد (اختياري)</label><input type="file" id="clientContractFile" accept="image/*,.pdf"></div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeClientModal()">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ العميل</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal لإضافة/تعديل دفعة -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <h2 id="paymentModalTitle">إضافة دفعة جديدة</h2>
<form id="paymentForm" action="{{ route('dashboard.payments.store') }}" method="POST">
            @csrf
            <input type="hidden" id="paymentId">
            <input type="hidden" id="paymentClientId">
            <div class="form-grid">
                <div class="form-group"><label for="paymentAmount">مبلغ الدفعة</label><input type="number" id="paymentAmount" required></div>
                <div class="form-group"><label for="paymentDate">تاريخ الدفعة</label><input type="date" id="paymentDate" required></div>
                <div class="form-group">
                    <label for="payment_paid_to" class="required">تم الدفع لمن</label>
                    <select id="payment_paid_to" name="payment_paid_to" required>
                        <option value="">اختر الشخص</option>
                        <option value="محمد">محمد</option>
                        <option value="خالد">خالد</option>
                        <option value="other">أخرى (حدد)</option>
                    </select>
                </div>
                <div class="form-group" id="paymentOtherPaidToGroup" style="display: none;">
                    <label for="paymentOtherPaidTo">اسم المستلم</label>
                    <input type="text" id="paymentOtherPaidTo" placeholder="اكتب اسم المستلم">
                </div>
                <div class="form-group">
                    <label for="payment_method" class="required">طريقة الدفع</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="">اختر طريقة الدفع</option>
                        <option value="cash">نقدي</option>
                        <option value="bank_transaction">معاملة بنكية</option>
                        <option value="check">شيك</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="payment_currency" class="required">نوع العملة</label>
                    <select id="payment_currency" name="payment_currency" required>
                        <option value="شيكل">شيكل</option>
                        <option value="دينار">دينار</option>
                        <option value="دولار">دولار</option>
                    </select>
                </div>
                <!-- قسم تفاصيل البنك الديناميكي للدفعة -->
                <div id="paymentBankDetailsSection" class="dynamic-section">
                    <h3 class="section-header"><i class="fas fa-bank"></i> تفاصيل المعاملة البنكية</h3>
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
                <!-- قسم تفاصيل الشيك الديناميكي للدفعة -->
                <div id="paymentCheckDetailsSection" class="dynamic-section">
                    <h3 class="section-header"><i class="fas fa-money-check-alt"></i> تفاصيل الشيك</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="paymentCheckNumber">رقم الشيك <span class="required">*</span></label>
                            <input type="text" id="paymentCheckNumber">
                        </div>
                        <div class="form-group">
                            <label for="paymentCheckBank">البنك المصدر <span class="required">*</span></label>
                            <input type="text" id="paymentCheckBank">
                        </div>
                        <div class="form-group">
                            <label for="paymentCheckDueDate">تاريخ الاستحقاق <span class="required">*</span></label>
                            <input type="date" id="paymentCheckDueDate">
                        </div>
                        <div class="form-group">
                            <label for="paymentCheckReceiptDate">تاريخ الاستلام <span class="required">*</span></label>
                            <input type="date" id="paymentCheckReceiptDate">
                        </div>
                    </div>
                </div>
                <div class="form-group full-width"><label for="paymentNotes">ملاحظات</label><textarea id="paymentNotes" rows="3"></textarea></div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ الدفعة</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
    // تعريف المتغيرات العامة وقاعدة البيانات
    let clients = [];
    let payments = [];
    let currentClientId = null;

    // دالة لإنشاء معرف فريد
    function generateUniqueId() {
        return '_' + Math.random().toString(36).substr(2, 9);
    }

    // دوال مساعده لقراءة وحفظ البيانات في LocalStorage
    function getDB(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : [];
        } catch (e) {
            console.error(`خطأ في قراءة البيانات من ${key}:`, e);
            return [];
        }
    }

    function setDB(key, data) {
        try {
            localStorage.setItem(key, JSON.stringify(data));
        } catch (e) {
            console.error(`خطأ في حفظ البيانات إلى ${key}:`, e);
        }
    }

    // دالة لتنسيق العملة
    function formatCurrency(num, currency = 'شيكل') {
        try {
            return new Intl.NumberFormat('ar-SA', { minimumFractionDigits: 0, maximumFractionDigits: 2 }).format(num || 0) + ' ' + currency;
        } catch (e) {
            console.warn('خطأ في تنسيق العملة، استخدام التنسيق الافتراضي:', e);
            return (num || 0).toFixed(2) + ' ' + currency;
        }
    }

    // دالة لتنقية المدخلات
    function sanitizeInput(input) {
        const div = document.createElement('div');
        div.textContent = input;
        return div.innerHTML;
    }

    // دوال عرض الرسائل باستخدام SweetAlert2
    function showSuccess(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'نجح!', text: message, icon: 'success', confirmButtonText: 'حسناً' });
        } else {
            alert('نجح: ' + message);
        }
    }

    function showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'خطأ', text: message, icon: 'error', confirmButtonText: 'حسناً' });
        } else {
            alert('خطأ: ' + message);
        }
    }

    function showInfo(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'معلومات', text: message, icon: 'info', confirmButtonText: 'حسناً' });
        } else {
            alert('معلومات: ' + message);
        }
    }

    // تحميل وحفظ البيانات
    function loadData() {
        clients = getDB('clients');
        payments = getDB('client_payments');
        renderClientsTable();
        updateKPIs();
        populateProjectFilter();
    }

    function saveData() {
        setDB('clients', clients);
        setDB('client_payments', payments);
    }

    //عرض البيانات في الجداول
    function renderClientsTable() {
        const tableBody = document.getElementById('clientsListBody');
        if (!tableBody) return;
        tableBody.innerHTML = '';

        if (clients.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="6" style="text-align: center; padding: 20px; color: var(--text-muted);">لا توجد عملاء مسجلين.</td></tr>`;
            return;
        }

        clients.forEach(client => {
            const clientPayments = payments.filter(p => p.clientId === client.id);
            const paidAmount = clientPayments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
            const agreementAmount = parseFloat(client.agreementAmount) || 0;
            const remainingAmount = Math.max(0, agreementAmount - paidAmount);

            const row = `
                <tr>
                    <td><strong>${sanitizeInput(client.name)}</strong></td>
                    <td>${sanitizeInput(client.project)} / ${sanitizeInput(client.unit)}</td>
                    <td>${formatCurrency(agreementAmount, client.currency)}</td>
                    <td style="color: var(--success-color);">${formatCurrency(paidAmount, client.currency)}</td>
                    <td style="color: var(--danger-color);">${formatCurrency(remainingAmount, client.currency)}</td>
                    <td>
                        <button class="btn-action" title="عرض السجل المالي" onclick="viewClientFinancialReport('${client.id}')"><i class="fas fa-eye"></i></button>
                        <button class="btn-action" title="تعديل العميل" onclick="editClient('${client.id}')"><i class="fas fa-edit"></i></button>
                        <button class="btn-action" title="حذف العميل" onclick="deleteClient('${client.id}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    function renderPaymentsTable(clientId) {
        const tableBody = document.getElementById('paymentsTableBody');
        if (!tableBody) return;
        tableBody.innerHTML = '';
        const clientPayments = payments.filter(p => p.clientId === clientId);

        if (clientPayments.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="7" style="text-align: center; padding: 20px; color: var(--text-muted);">لا توجد مدفوعات مسجلة لهذا العميل.</td></tr>`;
            return;
        }

        clientPayments.forEach(payment => {
            const row = `
                <tr>
                    <td>${sanitizeInput(payment.date)}</td>
                    <td>${formatCurrency(payment.amount, payment.currency)}</td>
                    <td>${sanitizeInput(payment.paid_to || '')}</td>
                    <td>${sanitizeInput(payment.method)}</td>
                    <td>${sanitizeInput(payment.currency)}</td>
                    <td>${sanitizeInput(payment.notes || 'لا يوجد ملاحظات')}</td>
                    <td>
                        <button class="btn-action" title="تعديل الدفعة" onclick="editPayment('${payment.id}')"><i class="fas fa-edit"></i></button>
                        <button class="btn-action" title="حذف الدفعة" onclick="deletePayment('${payment.id}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // تحديث مؤشرات الأداء الرئيسية (KPIs)
    function updateKPIs() {
        const totalClients = clients.length;
        const totalAgreements = clients.reduce((sum, c) => sum + (parseFloat(c.agreementAmount) || 0), 0);
        const totalPaid = payments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
        const totalRemaining = totalAgreements - totalPaid;

        document.getElementById('kpiTotalClients').textContent = totalClients;
        document.getElementById('kpiTotalAgreements').textContent = formatCurrency(totalAgreements);
        document.getElementById('kpiTotalPaid').textContent = formatCurrency(totalPaid);
        document.getElementById('kpiTotalRemaining').textContent = formatCurrency(totalRemaining);
    }

    // تعبئة قائمة المشاريع
    function populateProjectFilter() {
        const projectFilterSelect = document.getElementById('clientProject');
        if (!projectFilterSelect) return;

        const currentVal = projectFilterSelect.value;
        projectFilterSelect.innerHTML = '<option value="">اختر مشروعاً</option>';

        const uniqueProjects = [...new Set(clients.map(c => c.project).filter(Boolean))];
        uniqueProjects.sort().forEach(project => {
            const option = document.createElement('option');
            option.value = project;
            option.textContent = project;
            projectFilterSelect.appendChild(option);
        });
        projectFilterSelect.value = currentVal;
    }

    // إدارة النوافذ المنبثقة (Modals)
    window.openClientModal = function(clientId = null) {
        const modal = document.getElementById('clientModal');
        const form = document.getElementById('clientForm');
        const title = document.getElementById('clientModalTitle');

        form.reset();
        document.getElementById('clientId').value = '';
        ['bankDetailsSection', 'checkDetailsSection', 'clientOtherPaidToGroup', 'otherBankNameGroup', 'otherBankBranchGroup'].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });

        populateProjectFilter(); // تحديث قائمة المشاريع عند فتح المودال

        if (clientId) {
            title.textContent = 'تعديل بيانات العميل';
            const client = clients.find(c => c.id === clientId);
            if (client) {
                document.getElementById('clientId').value = client.id;
                document.getElementById('dueDate').value = client.dueDate || '';
                document.getElementById('clientName').value = client.name;
                document.getElementById('clientPhone').value = client.phone || '';
                document.getElementById('clientProject').value = client.project;
                document.getElementById('clientUnit').value = client.unit;
                document.getElementById('clientAgreementAmount').value = client.agreementAmount;
                document.getElementById('client_payment_method').value = client.payment_method;
                document.getElementById('client_currency').value = client.currency;

                // التعامل مع حقل "تم الدفع لمن"
                const paidToSelect = document.getElementById('client_paid_to');
                const isStandardOption = [...paidToSelect.options].some(opt => opt.value === client.paid_to);
                if (isStandardOption && client.paid_to !== 'other') {
                    paidToSelect.value = client.paid_to;
                } else if (client.paid_to) {
                    paidToSelect.value = 'other';
                    document.getElementById('clientOtherPaidToGroup').style.display = 'flex';
                    document.getElementById('clientOtherPaidTo').value = client.paid_to;
                }

                // إظهار الأقسام الديناميكية بناءً على طريقة الدفع
                if (client.payment_method === 'bank_transaction') {
                    document.getElementById('bankDetailsSection').style.display = 'grid';
                    const bankNameSelect = document.getElementById('bankName');
                    const isStandardBank = [...bankNameSelect.options].some(opt => opt.value === client.bankDetails?.bankName);
                    if(isStandardBank && client.bankDetails?.bankName !== 'other') {
                        bankNameSelect.value = client.bankDetails.bankName;
                    } else if (client.bankDetails?.bankName) {
                        bankNameSelect.value = 'other';
                        document.getElementById('otherBankNameGroup').style.display = 'flex';
                        document.getElementById('otherBankBranchGroup').style.display = 'flex';
                        document.getElementById('otherBankName').value = client.bankDetails.otherBankName || client.bankDetails.bankName;
                        document.getElementById('otherBankBranch').value = client.bankDetails.otherBankBranch || '';
                    }
                } else if (client.payment_method === 'check') {
                    document.getElementById('checkDetailsSection').style.display = 'grid';
                    if(client.checkDetails) {
                        document.getElementById('checkNumber').value = client.checkDetails.checkNumber || '';
                        document.getElementById('checkBank').value = client.checkDetails.checkBank || '';
                        document.getElementById('checkDueDate').value = client.checkDetails.checkDueDate || '';
                        document.getElementById('checkReceiptDate').value = client.checkDetails.checkReceiptDate || '';
                    }
                }
            }
        } else {
            title.textContent = 'إضافة عميل جديد';
        }
        modal.style.display = 'flex';
    }

    window.closeClientModal = function() {
        document.getElementById('clientModal').style.display = 'none';
    }

    window.openPaymentModal = function(paymentId = null) {
        const modal = document.getElementById('paymentModal');
        const form = document.getElementById('paymentForm');
        const title = document.getElementById('paymentModalTitle');

        form.reset();
        document.getElementById('paymentId').value = '';
        document.getElementById('paymentClientId').value = currentClientId;
        ['paymentBankDetailsSection', 'paymentCheckDetailsSection', 'paymentOtherPaidToGroup', 'paymentOtherBankNameGroup', 'paymentOtherBankBranchGroup'].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });

        if (paymentId) {
            title.textContent = 'تعديل دفعة';
            const payment = payments.find(p => p.id === paymentId);
            if (payment) {
                document.getElementById('paymentId').value = payment.id;
                document.getElementById('paymentClientId').value = payment.clientId;
                document.getElementById('paymentAmount').value = payment.amount;
                document.getElementById('paymentDate').value = payment.date;
                document.getElementById('payment_method').value = payment.method;
                document.getElementById('payment_currency').value = payment.currency;
                document.getElementById('paymentNotes').value = payment.notes || '';

                const paidToSelect = document.getElementById('payment_paid_to');
                const isStandardOption = [...paidToSelect.options].some(opt => opt.value === payment.paid_to);
                if (isStandardOption && payment.paid_to !== 'other') {
                    paidToSelect.value = payment.paid_to;
                } else if (payment.paid_to) {
                    paidToSelect.value = 'other';
                    document.getElementById('paymentOtherPaidToGroup').style.display = 'flex';
                    document.getElementById('paymentOtherPaidTo').value = payment.paid_to;
                }

                if (payment.method === 'bank_transaction') {
                    document.getElementById('paymentBankDetailsSection').style.display = 'grid';
                    const bankNameSelect = document.getElementById('paymentBankName');
                    const isStandardBank = [...bankNameSelect.options].some(opt => opt.value === payment.bankDetails?.bankName);
                     if(isStandardBank && payment.bankDetails?.bankName !== 'other') {
                        bankNameSelect.value = payment.bankDetails.bankName;
                    } else if (payment.bankDetails?.bankName) {
                        bankNameSelect.value = 'other';
                        document.getElementById('paymentOtherBankNameGroup').style.display = 'flex';
                        document.getElementById('paymentOtherBankBranchGroup').style.display = 'flex';
                        document.getElementById('paymentOtherBankName').value = payment.bankDetails.otherBankName || payment.bankDetails.bankName;
                        document.getElementById('paymentOtherBankBranch').value = payment.bankDetails.otherBankBranch || '';
                    }
                } else if (payment.method === 'check') {
                    document.getElementById('paymentCheckDetailsSection').style.display = 'grid';
                    if(payment.checkDetails) {
                        document.getElementById('paymentCheckNumber').value = payment.checkDetails.checkNumber || '';
                        document.getElementById('paymentCheckBank').value = payment.checkDetails.checkBank || '';
                        document.getElementById('paymentCheckDueDate').value = payment.checkDetails.checkDueDate || '';
                        document.getElementById('paymentCheckReceiptDate').value = payment.checkDetails.checkReceiptDate || '';
                    }
                }
            }
        } else {
            title.textContent = 'إضافة دفعة جديدة';
        }
        modal.style.display = 'flex';
    }

    window.closePaymentModal = function() {
        document.getElementById('paymentModal').style.display = 'none';
    }

    //  معالجة النماذج
    if (window.USE_LOCAL_CLIENTS_LOCALSTORAGE) document.getElementById('clientForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const clientId = document.getElementById('clientId').value;
        let paid_to = document.getElementById('client_paid_to').value;
        if (paid_to === 'other') {
            paid_to = document.getElementById('clientOtherPaidTo').value.trim();
            if (!paid_to) { return showError('يرجى إدخال اسم المستلم.'); }
        }

        const agreementAmount = parseFloat(document.getElementById('clientAgreementAmount').value);
        if (isNaN(agreementAmount) || agreementAmount < 0) {
            return showError('المبلغ الإجمالي للاتفاقية يجب أن يكون رقماً صالحاً.');
        }

        const payment_method = document.getElementById('client_payment_method').value;
        let bankDetails = {};
        if (payment_method === 'bank_transaction') {
            let bankName = document.getElementById('bankName').value;
            if (bankName === 'other') {
                bankDetails.otherBankName = document.getElementById('otherBankName').value.trim();
                bankDetails.otherBankBranch = document.getElementById('otherBankBranch').value.trim();
                if (!bankDetails.otherBankName || !bankDetails.otherBankBranch) { return showError('يرجى إدخال اسم البنك وفرعه.'); }
                bankName = bankDetails.otherBankName; // Use the other name as the main name
            }
            bankDetails.bankName = bankName;
        }

        let checkDetails = {};
        if (payment_method === 'check') {
            checkDetails = {
                checkNumber: document.getElementById('checkNumber').value.trim(),
                checkBank: document.getElementById('checkBank').value.trim(),
                checkDueDate: document.getElementById('checkDueDate').value,
                checkReceiptDate: document.getElementById('checkReceiptDate').value
            };
            if (!checkDetails.checkNumber || !checkDetails.checkBank || !checkDetails.checkDueDate || !checkDetails.checkReceiptDate) {
                return showError('يرجى ملء جميع حقول تفاصيل الشيك.');
            }
        }

        const clientData = {
            dueDate: document.getElementById('dueDate').value,
            name: document.getElementById('clientName').value.trim(),
            phone: document.getElementById('clientPhone').value.trim(),
            project: document.getElementById('clientProject').value.trim(),
            unit: document.getElementById('clientUnit').value.trim(),
            agreementAmount,
            payment_method,
            currency: document.getElementById('client_currency').value,
            paid_to,
            bankDetails,
            checkDetails
        };

        const contractFile = document.getElementById('clientContractFile').files[0];
        if (contractFile) {
            clientData.contractFile = URL.createObjectURL(contractFile); // Temporary URL
        }

        if (clientId) {
            const clientIndex = clients.findIndex(c => c.id === clientId);
            if (clientIndex > -1) {
                clients[clientIndex] = { ...clients[clientIndex], ...clientData, id: clientId };
                if (!contractFile) { // Keep old file if no new one is uploaded
                    clients[clientIndex].contractFile = getDB('clients').find(c => c.id === clientId)?.contractFile || '';
                }
                showSuccess('تم تحديث بيانات العميل بنجاح!');
            }
        } else {
            clientData.id = generateUniqueId();
            clients.push(clientData);
            showSuccess('تم إضافة عميل جديد بنجاح!');
        }
        saveData();
        loadData();
        closeClientModal();
    });

    if (window.USE_LOCAL_CLIENTS_LOCALSTORAGE) document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const paymentId = document.getElementById('paymentId').value;
        const clientId = document.getElementById('paymentClientId').value;

        let paid_to = document.getElementById('payment_paid_to').value;
        if (paid_to === 'other') {
            paid_to = document.getElementById('paymentOtherPaidTo').value.trim();
            if (!paid_to) { return showError('يرجى إدخال اسم المستلم.'); }
        }

        const amount = parseFloat(document.getElementById('paymentAmount').value);
        if (isNaN(amount) || amount <= 0) {
            return showError('مبلغ الدفعة يجب أن يكون رقماً موجباً.');
        }

        const method = document.getElementById('payment_method').value;
        let bankDetails = {};
        if (method === 'bank_transaction') {
            let bankName = document.getElementById('paymentBankName').value;
            if (bankName === 'other') {
                bankDetails.otherBankName = document.getElementById('paymentOtherBankName').value.trim();
                bankDetails.otherBankBranch = document.getElementById('paymentOtherBankBranch').value.trim();
                if (!bankDetails.otherBankName || !bankDetails.otherBankBranch) { return showError('يرجى إدخال اسم البنك وفرعه.'); }
                bankName = bankDetails.otherBankName;
            }
            bankDetails.bankName = bankName;
        }

        let checkDetails = {};
        if (method === 'check') {
            checkDetails = {
                checkNumber: document.getElementById('paymentCheckNumber').value.trim(),
                checkBank: document.getElementById('paymentCheckBank').value.trim(),
                checkDueDate: document.getElementById('paymentCheckDueDate').value,
                checkReceiptDate: document.getElementById('paymentCheckReceiptDate').value
            };
            if (!checkDetails.checkNumber || !checkDetails.checkBank || !checkDetails.checkDueDate || !checkDetails.checkReceiptDate) {
                return showError('يرجى ملء جميع حقول تفاصيل الشيك.');
            }
        }

        const paymentData = {
            clientId,
            amount,
            date: document.getElementById('paymentDate').value,
            paid_to,
            method,
            currency: document.getElementById('payment_currency').value,
            notes: document.getElementById('paymentNotes').value.trim(),
            bankDetails,
            checkDetails
        };

        if (paymentId) {
            const paymentIndex = payments.findIndex(p => p.id === paymentId);
            if (paymentIndex > -1) {
                payments[paymentIndex] = { ...payments[paymentIndex], ...paymentData, id: paymentId };
                showSuccess('تم تحديث الدفعة بنجاح!');
            }
        } else {
            paymentData.id = generateUniqueId();
            payments.push(paymentData);
            showSuccess('تم إضافة دفعة جديدة بنجاح!');
        }
        saveData();
        loadData();
        closePaymentModal();
        viewClientFinancialReport(clientId);
    });

    //  وظائف الإجراءات (تعديل، حذف، عرض)
    window.editClient = function(clientId) {
        openClientModal(clientId);
    }

    window.deleteClient = function(clientId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم حذف العميل وجميع مدفوعاته بشكل نهائي!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                clients = clients.filter(c => c.id !== clientId);
                payments = payments.filter(p => p.clientId !== clientId);
                saveData();
                loadData();
                document.getElementById('reportSection').style.display = 'none'; // إخفاء قسم التقرير إذا كان مفتوحاً
                showSuccess('تم حذف العميل بنجاح.');
            }
        });
    }

    window.viewClientFinancialReport = function(clientId) {
        currentClientId = clientId;
        const client = clients.find(c => c.id === clientId);
        if (!client) { return showError('لم يتم العثور على العميل.'); }

        document.getElementById('reportSection').style.display = 'block';
        document.getElementById('reportTitle').textContent = `السجل المالي للعميل: ${client.name}`;

        const contractLinkContainer = document.getElementById('contractLinkContainer');
        contractLinkContainer.innerHTML = '';
        if (client.contractFile) {
            const link = document.createElement('a');
            link.href = client.contractFile;
            link.target = '_blank';
            link.className = 'btn btn-secondary';
            link.innerHTML = '<i class="fas fa-file-contract"></i> عرض العقد';
            contractLinkContainer.appendChild(link);
        }

        renderPaymentsTable(clientId);
        window.scrollTo({ top: document.getElementById('reportSection').offsetTop, behavior: 'smooth' });
    }

    window.editPayment = function(paymentId) {
        openPaymentModal(paymentId);
    }

    window.deletePayment = function(paymentId) {
        const paymentToDelete = payments.find(p => p.id === paymentId);
        if (!paymentToDelete) return;

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم حذف هذه الدفعة بشكل نهائي!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                payments = payments.filter(p => p.id !== paymentId);
                saveData();
                loadData();
                viewClientFinancialReport(paymentToDelete.clientId); // تحديث التقرير
                showSuccess('تم حذف الدفعة بنجاح!');
            }
        });
    }

    // --- التصدير إلى Excel ---
    window.exportClientsToExcel = function() {
        if (typeof XLSX === 'undefined') { return showError('مكتبة التصدير (XLSX) غير متوفرة.'); }
        if (clients.length === 0) { return showInfo('لا يوجد عملاء لتصديرهم.'); }

        const data = clients.map(client => {
            const clientPayments = payments.filter(p => p.clientId === client.id);
            const paidAmount = clientPayments.reduce((sum, p) => sum + (parseFloat(p.amount) || 0), 0);
            const agreementAmount = parseFloat(client.agreementAmount) || 0;
            const remainingAmount = Math.max(0, agreementAmount - paidAmount);
            return {
                'اسم العميل': client.name,
                'المشروع': client.project,
                'الوحدة': client.unit,
                'تاريخ الاستحقاق': client.dueDate || '',
                'الجوال': client.phone || '',
                'طريقة الدفع الرئيسية': client.payment_method,
                'نوع العملة': client.currency,
                'تم الدفع لمن (الدفعة الأولى)': client.paid_to || '',
                'المبلغ الإجمالي للاتفاق': agreementAmount,
                'المبلغ المدفوع': paidAmount,
                'المبلغ المتبقي': remainingAmount,
            };
        });

        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'بيانات العملاء');
        XLSX.writeFile(wb, `بيانات_العملاء_${new Date().toISOString().split('T')[0]}.xlsx`);
    }

    window.exportClientPaymentsToExcel = function() {
        if (!currentClientId) { return showError('يرجى تحديد عميل أولاً.'); }
        if (typeof XLSX === 'undefined') { return showError('مكتبة التصدير (XLSX) غير متوفرة.'); }

        const client = clients.find(c => c.id === currentClientId);
        if (!client) { return showError('لم يتم العثور على العميل.'); }

        const clientPayments = payments.filter(p => p.clientId === currentClientId);
        if (clientPayments.length === 0) { return showInfo('لا توجد مدفوعات لهذا العميل لتصديرها.'); }

        const data = clientPayments.map(payment => ({
            'تاريخ الدفعة': payment.date,
            'المبلغ': payment.amount,
            'لمن تم الدفع': payment.paid_to || '',
            'طريقة الدفع': payment.method,
            'نوع العملة': payment.currency,
            'ملاحظات': payment.notes || '',
        }));

        const ws = XLSX.utils.json_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, `مدفوعات_${client.name}`);
        XLSX.writeFile(wb, `مدفوعات_${client.name}_${new Date().toISOString().split('T')[0]}.xlsx`);
    }

    // إعداد الأحداث عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        if (window.USE_LOCAL_CLIENTS_LOCALSTORAGE) { loadData(); }

         // دالة لإدارة عرض الأقسام الديناميكية
         function setupDynamicSections(methodSelectId, bankSectionId, checkSectionId) {
             document.getElementById(methodSelectId).addEventListener('change', function() {
                 document.getElementById(bankSectionId).style.display = this.value === 'bank_transaction' ? 'grid' : 'none';
                 document.getElementById(checkSectionId).style.display = this.value === 'check' ? 'grid' : 'none';
             });
         }

         // دالة لإدارة عرض حقل "أخرى"
         function setupOtherField(selectId, otherGroupId) {
             document.getElementById(selectId).addEventListener('change', function() {
                 document.getElementById(otherGroupId).style.display = this.value === 'other' ? 'flex' : 'none';
             });
         }

         // تطبيق الإعدادات على نماذج العميل والدفع
         setupDynamicSections('client_payment_method', 'bankDetailsSection', 'checkDetailsSection');
         setupOtherField('client_paid_to', 'clientOtherPaidToGroup');
         setupOtherField('bankName', 'otherBankNameGroup');
         setupOtherField('bankName', 'otherBankBranchGroup');

         setupDynamicSections('payment_method', 'paymentBankDetailsSection', 'paymentCheckDetailsSection');
         setupOtherField('payment_paid_to', 'paymentOtherPaidToGroup');
         setupOtherField('paymentBankName', 'paymentOtherBankNameGroup');
         setupOtherField('paymentBankName', 'paymentOtherBankBranchGroup');

         // إغلاق المودال عند النقر خارجه
         window.addEventListener('click', function(event) {
             const clientModal = document.getElementById('clientModal');
             const paymentModal = document.getElementById('paymentModal');
             if (event.target === clientModal) {
                 closeClientModal();
             }
             if (event.target === paymentModal) {
                 closePaymentModal();
             }
         });
    });

    // اجعلها false لتعطيل تخزين المتصفح والاكتفاء بإرسال النماذج إلى الخادم
    window.USE_LOCAL_CLIENTS_LOCALSTORAGE = false;
</script>
@endsection
