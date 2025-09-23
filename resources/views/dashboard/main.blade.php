@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
<style>
    :root {
        --primary-color: #1abc9c;
        --dark-bg-1: #ffffff;
        --dark-bg-2: #f8f9fa;
        --text-color: #333333;
        --text-muted: #666666;
        --border-color: #dee2e6;
        --success: #2ecc71;
        --danger: #e74c3c;
        --warning: #f39c12;
        --info: #3498db;
    }

    body {
        background-color: var(--dark-bg-1);
        color: var(--text-color);
        font-family: 'Cairo', sans-serif;
        margin: 0;
        padding: 15px;
    }

    .main-content {
        max-width: 1200px;
        margin: 15px auto;
    }

    .page-header h1 {
        font-size: 2.2rem;
        color: var(--text-color);
        text-align: center;
        margin-bottom: 25px;
    }

    /* صندوق البحث العام */
    .search-container {
        background-color: var(--dark-bg-2);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .search-container h3 {
        color: var(--text-color);
        margin-bottom: 15px;
    }

    .search-box {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .search-input {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background-color: var(--dark-bg-1);
        color: var(--text-color);
        font-size: 1rem;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.1);
    }

    .search-btn {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .search-btn:hover {
        background-color: #16a085;
    }

    .search-results {
        margin-top: 15px;
        max-height: 200px;
        overflow-y: auto;
    }

    .search-result-item {
        padding: 10px;
        background-color: var(--dark-bg-1);
        margin-bottom: 5px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s;
        border: 1px solid var(--border-color);
    }

    .search-result-item:hover {
        background-color: #f1f3f4;
    }

    .search-result-title {
        font-weight: bold;
        color: var(--primary-color);
    }

    .search-result-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .main-kpi-card {
        background: linear-gradient(45deg, #16a085, #1abc9c);
        color: #fff;
        padding: 25px;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 25px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .main-kpi-card .label {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .main-kpi-card .value {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .sub-boxes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .sub-box {
        background-color: var(--dark-bg-2);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        color: var(--text-color);
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .sub-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: var(--text-color);
    }

    .sub-box .icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .sub-box .label {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .actions-container,
    .transfers-container {
        background-color: var(--dark-bg-2);
        padding: 25px;
        border-radius: 16px;
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .actions-container h2,
    .transfers-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: var(--text-color);
    }

    .actions-grid {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-action {
        background-color: #34495e;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        border: none;
        font-size: 1rem;
        flex-grow: 1;
        max-width: 250px;
        transition: background-color 0.3s;
    }

    .btn-action:hover {
        background-color: #2c3e50;
    }

    .btn-export {
        background-color: #16a085;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: background-color 0.3s;
    }

    .btn-export:hover {
        background-color: #138d75;
        text-decoration: none;
        color: white;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        padding: 10px 0;
    }

    .modal-content {
        background-color: var(--dark-bg-1);
        padding: 25px;
        border-radius: 12px;
        width: 95%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-sizing: border-box;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .modal-content h2 {
        color: var(--text-color);
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: var(--text-color);
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background-color: var(--dark-bg-1);
        color: var(--text-color);
        box-sizing: border-box;
        font-size: 1rem;
    }

    input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 188, 156, 0.1);
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #16a085;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-left: 10px;
        transition: background-color 0.3s;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .hidden {
        display: none;
    }

    .dynamic-section {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
    }

    .dynamic-section h4 {
        margin-top: 0;
        margin-bottom: 15px;
        color: var(--primary-color);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 12px;
        text-align: right;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    th {
        background-color: #f1f3f4;
        color: var(--text-muted);
        font-weight: 600;
    }

    td {
        color: var(--text-color);
    }

    tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .actions a {
        color: var(--text-muted);
        margin: 0 5px;
        cursor: pointer;
        font-size: 1.1rem;
        transition: color 0.3s;
    }

    .actions a:hover {
        color: var(--primary-color);
    }

    .transfer-funds {
        color: var(--primary-color);
        font-weight: bold;
    }

    .transfer-project {
        color: var(--warning);
        font-weight: bold;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    @media (max-width: 768px) {
        body {
            padding: 10px;
        }

        .main-content {
            margin: 10px auto;
        }

        .page-header h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .main-kpi-card {
            padding: 20px;
        }

        .main-kpi-card .value {
            font-size: 2rem;
        }

        .actions-grid {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-action {
            max-width: none;
        }

        .modal-content {
            padding: 20px;
            width: 95%;
        }
    }
</style>
@endsection

@section('content')

<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-vault"></i> الخزينة العامة</h1>
    </div>

    <!-- صندوق البحث العام -->
    <div class="search-container">
        <h3><i class="fas fa-search"></i> البحث العام</h3>
        <div class="search-box">
            <input type="text" id="globalSearchInput" class="search-input" placeholder="ابحث عن عميل، مورد، مشروع، أو بنك...">
            <button class="search-btn" onclick="performGlobalSearch()"><i class="fas fa-search"></i></button>
        </div>
        <div id="searchResults" class="search-results hidden"></div>
    </div>

    <div class="main-kpi-card">
        <div class="label">إجمالي السيولة المتاحة (كاش + بنك)</div>
        <div class="value" id="totalLiquidity">0 شيكل</div>
    </div>

    <div class="sub-boxes-grid">
        <a href="/dashboard/cash" class="sub-box">
            <div class="icon" style="color: #2ecc71;"><i class="fas fa-money-bill-wave"></i></div>
            <div class="label">صندوق الكاش</div>
        </a>

        <a href="/dashboard/bank" class="sub-box">
            <div class="icon" style="color: #3498db;"><i class="fas fa-landmark"></i></div>
            <div class="label">الحسابات البنكية</div>
        </a>

        <a href="/dashboard/cheques" class="sub-box">
            <div class="icon" style="color: #f1c40f;"><i class="fas fa-money-check-alt"></i></div>
            <div class="label">محفظة الشيكات</div>
        </a>

        <a href="/dashboard/payments" class="sub-box">
            <div class="icon" style="color: #e74c3c;"><i class="fas fa-file-invoice-dollar"></i></div>
            <div class="label" > صندوق السند و القبضٍ</div>
        </a>
    </div>
    <div class="sub-boxes-grid">
        <a href="/dashboard/cash" class="sub-box">
            <div class="icon" style="color:rgb(126, 6, 110);"><i class="fas fa-money-bill-wave"></i></div>
            <div class="label">صندوق الدفوعات</div>
        </a>
    <div class="actions-container">
        <h2>إدارة التحويلات</h2>
        <div class="actions-grid">
            <button class="btn-action" onclick="openModal('fundsTransferModal')">
                <i class="fas fa-exchange-alt"></i> تحويل بين الصناديق
            </button>
            <button class="btn-action" onclick="openModal('projectTransferModal')">
                <i class="fas fa-project-diagram"></i> تحويل بين المشاريع
            </button>
        </div>
    </div>


								<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header flex-wrap border-0 pt-6 pb-0">
										<div class="card-title">
											<h3 class="card-label">Complex Header
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
												<!--end::Dropdown Menu-->
											</div>
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
				
<!-- نافذة تحويل الصناديق -->
<div id="fundsTransferModal" class="modal">
    <div class="modal-content">
        <h2><i class="fas fa-exchange-alt"></i> تحويل بين الصناديق</h2>
        <form id="fundsTransferForm">
            <div class="form-group">
                <label for="transferDate">التاريخ *</label>
                <input type="date" id="transferDate" required>
            </div>
            <div class="form-group">
                <label for="fromAccount">من الصندوق *</label>
                <select id="fromAccount" required>
                    <option value="">اختر الصندوق</option>
                    <option value="cash">صندوق الكاش</option>
                    <option value="cheques">صندوق الشيك</option>
                    <option value="bank">الحساب البنكي</option>
                </select>
            </div>
            <div class="form-group">
                <label for="toAccount">إلى الصندوق *</label>
                <select id="toAccount" required>
                    <option value="">اختر الصندوق</option>
                    <option value="cash">صندوق الكاش</option>
                    <option value="cheques">صندوق الشيك</option>
                    <option value="bank">الحساب البنكي</option>
                </select>
            </div>
            <div class="form-group">
                <label for="contactName">الاسم *</label>
                <input type="text" id="contactName" name="name" required>
            </div>
           
            <div class="form-group">
                <label for="transferIdNumber">رقم الهوية</label>
                <input type="text" id="transferIdNumber" name="id_number">
            </div>
            <div class="form-group">
                <label for="transferPhone">رقم الجوال</label>
                <input type="text" id="transferPhone" name="phone">
            </div>
            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" required>
                    <option value="شيكل">شيكل</option>
                    <option value="دولار">دولار</option>
                    <option value="دينار">دينار</option>
                </select>
            </div>
            <div class="form-group">
                <label for="transferAmount">المبلغ *</label>
                <input type="number" id="transferAmount" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="transferNotes">ملاحظات</label>
                <textarea id="transferNotes" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-primary">تنفيذ التحويل</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<!-- نافذة تحويل المشاريع -->
<div id="projectTransferModal" class="modal">
    <div class="modal-content">
        <h2><i class="fas fa-project-diagram"></i> تحويل بين المشاريع</h2>
        <form id="projectTransferForm">
        <div class="form-group">
                <label for="projectTransferDate">التاريخ *</label>
                <input type="date" id="projectTransferDate" required>
            </div>
            <div class="form-group">
                <label for="projectContactName">الاسم *</label>
                <input type="text" id="projectContactName" name="name" required>
            </div>
            <div class="form-group">
                <label for="projectTransferIdNumber">رقم الهوية</label>
                <input type="text" id="projectTransferIdNumber" name="id_number">
            </div>
            <div class="form-group">
                <label for="projectTransferPhone">رقم الجوال</label>
                <input type="text" id="projectTransferPhone" name="phone">
            </div>
           
            <div class="form-group">
                <label for="fromProject">من المشروع *</label>
                <select id="fromProject" required>
                    <option value="">اختر المشروع...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="toProject">إلى المشروع *</label>
                <select id="toProject" required>
                    <option value="">اختر المشروع...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" required>
                    <option value="شيكل">شيكل</option>
                    <option value="دولار">دولار</option>
                    <option value="دينار">دينار</option>
                </select>
            </div>
            <div class="form-group">
                <label for="projectTransferAmount">المبلغ *</label>
                <input type="number" id="projectTransferAmount" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="projectTransferNotes">ملاحظات</label>
                <textarea id="projectTransferNotes" rows="3"></textarea>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn-primary">تنفيذ التحويل</button>
                <button type="button" class="btn-secondary" onclick="closeModal()">إلغاء</button>
            </div>
        </form>
    </div>
</div>

          

@endsection

@section('script')
<script>
    // البيانات المحلية للتجربة
    let mockData = {
        transfers: [],
        projects: [
            { id: 1, project_name: 'مشروع الأبراج السكنية' },
            { id: 2, project_name: 'مشروع المجمع التجاري' },
            { id: 3, project_name: 'مشروع الفيلات الفاخرة' }
        ],
        contacts: []
    };

    // البحث العام
    function performGlobalSearch() {
        const searchTerm = document.getElementById('globalSearchInput').value.trim();
        const resultsContainer = document.getElementById('searchResults');

        if (!searchTerm) {
            resultsContainer.classList.add('hidden');
            return;
        }

        // محاكاة نتائج البحث
        const mockResults = [
            { id: 1, type: 'client', title: 'أحمد محمد', subtitle: 'عميل - 0599123456' },
            { id: 2, type: 'supplier', title: 'شركة البناء المتقدم', subtitle: 'مورد - 0598765432' },
            { id: 3, type: 'project', title: 'مشروع الأبراج السكنية', subtitle: 'مشروع نشط' },
            { id: 4, type: 'bank', title: 'بنك فلسطين', subtitle: 'حساب بنكي' }
        ].filter(item =>
            item.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (item.subtitle && item.subtitle.toLowerCase().includes(searchTerm.toLowerCase()))
        );

        if (mockResults.length === 0) {
            resultsContainer.innerHTML = '<div class="search-result-item">لا توجد نتائج</div>';
        } else {
            resultsContainer.innerHTML = mockResults.map(item => `
                <div class="search-result-item" onclick="selectSearchResult('${item.type}', ${item.id})">
                    <div class="search-result-title">${item.title}</div>
                    <div class="search-result-subtitle">${item.subtitle || ''}</div>
                </div>
            `).join('');
        }

        resultsContainer.classList.remove('hidden');
    }

    function selectSearchResult(type, id) {
        console.log(`تم اختيار ${type} بالمعرف ${id}`);
        document.getElementById('searchResults').classList.add('hidden');
        document.getElementById('globalSearchInput').value = '';
        showAlert(`تم اختيار عنصر من نوع '${type}'`, 'success');
    }

    // تحميل المشاريع في النوافذ المنبثقة
    function loadProjects() {
        const fromProject = document.getElementById('fromProject');
        const toProject = document.getElementById('toProject');

        if (fromProject && toProject) {
            const currentFromValue = fromProject.value;
            const currentToValue = toProject.value;

            fromProject.innerHTML = '<option value="">اختر المشروع...</option>';
            toProject.innerHTML = '<option value="">اختر المشروع...</option>';

            mockData.projects.forEach(project => {
                fromProject.innerHTML += `<option value="${project.id}">${project.project_name}</option>`;
                toProject.innerHTML += `<option value="${project.id}">${project.project_name}</option>`;
            });

            fromProject.value = currentFromValue;
            toProject.value = currentToValue;
        }
    }

    // إدارة النوافذ المنبثقة
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.style.display = 'flex';

        // تعيين التاريخ الحالي
        const today = new Date().toISOString().split('T')[0];
        const dateInput = modal.querySelector('input[type="date"]');
        if (dateInput) {
            dateInput.value = today;
        }

        if (modalId === 'projectTransferModal') {
            loadProjects();
        }
    }

    function closeModal() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
            // إعادة تعيين النماذج داخل النوافذ المنبثقة
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
            }
            // إخفاء جميع الأقسام الديناميكية
            modal.querySelectorAll('.dynamic-section').forEach(section => {
                section.classList.add('hidden');
            });
        });
    }

    // معالجة تحويل الصناديق
    document.getElementById('fundsTransferForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const fromAccount = document.getElementById('fromAccount').value;
        const toAccount = document.getElementById('toAccount').value;
        const amount = parseFloat(document.getElementById('transferAmount').value);
        const date = document.getElementById('transferDate').value;
        const notes = document.getElementById('transferNotes').value;

        if (fromAccount === toAccount) {
            showAlert('لا يمكن التحويل من وإلى نفس الصندوق.', 'error');
            return;
        }

        if (!amount || amount <= 0) {
            showAlert('يرجى إدخال مبلغ صحيح وموجب.', 'error');
            return;
        }

        const transfer = {
            id: Date.now(),
            date: date,
            type: 'تحويل صناديق',
            amount: amount,
            from: fromAccount === 'cash' ? 'صندوق الكاش' :'صندوق الشيك': 'الحساب البنكي',
            to: toAccount === 'cash' ? 'صندوق الكاش' :'صندوق الشيك': 'الحساب البنكي',
            notes: notes
        };

        mockData.transfers.push(transfer);
        renderTransfers();
        closeModal();
        showAlert('تم تنفيذ تحويل الصناديق بنجاح.', 'success');
    });

    // معالجة تحويل المشاريع
    document.getElementById('projectTransferForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const fromProjectId = parseInt(document.getElementById('fromProject').value);
        const toProjectId = parseInt(document.getElementById('toProject').value);
        const amount = parseFloat(document.getElementById('projectTransferAmount').value);
        const date = document.getElementById('projectTransferDate').value;
        const notes = document.getElementById('projectTransferNotes').value;

        if (fromProjectId === toProjectId) {
            showAlert('لا يمكن التحويل من وإلى نفس المشروع.', 'error');
            return;
        }

        if (!amount || amount <= 0) {
            showAlert('يرجى إدخال مبلغ صحيح وموجب.', 'error');
            return;
        }

        const fromProject = mockData.projects.find(p => p.id === fromProjectId);
        const toProject = mockData.projects.find(p => p.id === toProjectId);

        if (!fromProject || !toProject) {
            showAlert('يرجى اختيار مشروع صالح من القائمة.', 'error');
            return;
        }

        const transfer = {
            id: Date.now(),
            date: date,
            type: 'تحويل مشاريع',
            amount: amount,
            from: fromProject.project_name,
            to: toProject.project_name,
            notes: notes
        };

        mockData.transfers.push(transfer);
        renderTransfers();
        closeModal();
        showAlert('تم تنفيذ تحويل المشاريع بنجاح.', 'success');
    });

    // عرض التحويلات في الجدول
    function renderTransfers() {
        const tbody = document.getElementById('transfersTableBody');
        
        if (mockData.transfers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">لا توجد تحويلات حالية</td></tr>';
            return;
        }

        tbody.innerHTML = mockData.transfers.map(transfer => `
            <tr>
                <td>${transfer.date}</td>
                <td><span class="${transfer.type === 'تحويل صناديق' ? 'transfer-funds' : 'transfer-project'}">${transfer.type}</span></td>
                <td>${transfer.amount.toLocaleString()} شيكل</td>
                <td>${transfer.from}</td>
                <td>${transfer.to}</td>
                <td class="actions">
                    <a onclick="viewTransfer(${transfer.id})" title="عرض"><i class="fas fa-eye"></i></a>
                    <a onclick="editTransfer(${transfer.id})" title="تعديل"><i class="fas fa-edit"></i></a>
                    <a onclick="deleteTransfer(${transfer.id})" title="حذف"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        `).join('');
    }

    // عرض تفاصيل التحويل
    function viewTransfer(id) {
        const transfer = mockData.transfers.find(t => t.id === id);
        if (transfer) {
            const content = `
                <strong>التاريخ:</strong> ${transfer.date}  

                <strong>النوع:</strong> ${transfer.type}  

                <strong>المبلغ:</strong> ${transfer.amount.toLocaleString()} شيكل  

                <strong>من:</strong> ${transfer.from}  

                <strong>إلى:</strong> ${transfer.to}  

                <strong>ملاحظات:</strong> ${transfer.notes || 'لا توجد'}
            `;
            // استخدام نظام التنبيهات لعرض التفاصيل
            showAlert(content, 'info');
        }
    }

    // تعديل التحويل (وظيفة وهمية)
    function editTransfer(id) {
        showAlert('ميزة التعديل قيد التطوير حالياً.', 'info');
    }

    // حذف التحويل
    function deleteTransfer(id) {
        if (confirm('هل أنت متأكد من رغبتك في حذف هذا التحويل؟')) {
            mockData.transfers = mockData.transfers.filter(t => t.id !== id);
            renderTransfers();
            showAlert('تم حذف التحويل بنجاح.', 'success');
        }
    }

    // إظهار/إخفاء تفاصيل الدفع (شيك أو حوالة)
    function toggleDetails(selectElement, formType) {
        const paymentMethod = selectElement.value;
        const bankDetails = document.getElementById(`${formType}BankDetails`);
        const chequeDetails = document.getElementById(`${formType}ChequeDetails`);

        if (bankDetails) {
            bankDetails.classList.toggle("hidden", paymentMethod !== "transfer");
        }
        if (chequeDetails) {
            chequeDetails.classList.toggle("hidden", paymentMethod !== "cheque");
        }
    }

    // معالجة نماذج سندات القبض والصرف
    document.getElementById("receiptVoucherForm")?.addEventListener("submit", function(e) {
        e.preventDefault();
        showAlert('تم حفظ سند القبض بنجاح (محاكاة).', 'success');
        closeModal();
    });

    document.getElementById("paymentVoucherForm")?.addEventListener("submit", function(e) {
        e.preventDefault();
        showAlert('تم حفظ سند الصرف بنجاح (محاكاة).', 'success');
        closeModal();
    });

    // نظام التنبيهات الديناميكي
    function showAlert(message, type = 'success') {
        const alertContainer = document.querySelector('.main-content');
        if (!alertContainer) return;

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = message;
        alertDiv.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2000;
            min-width: 300px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.5s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 500);
        }, 5000);
    }

    // إغلاق النوافذ المنبثقة عند النقر خارجها أو الضغط على مفتاح الهروب
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });

    // تحديث إجمالي السيولة (محاكاة)
    function updateTotalLiquidity() {
        const totalLiquidity = 150000; // مثال
        const liquidityElement = document.getElementById('totalLiquidity');
        if (liquidityElement) {
            liquidityElement.textContent = totalLiquidity.toLocaleString() + ' شيكل';
        }
    }

    // التهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        renderTransfers();
        updateTotalLiquidity();
    });
</script>
@endsection


