@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
{{-- يمكنك إضافة أي تنسيقات CSS خاصة بهذه الصفحة هنا إذا احتجت --}}
   <style>
        :root {
            --primary-color: #00aaff;
            --dark-bg-1: #ffffff;
            --dark-bg-2: #f8f9fa;
            --text-color: #333333;
            --text-muted: #666666;
            --border-color: rgba(0, 0, 0, 0.1);
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--dark-bg-1);
            color: var(--text-color);
            margin: 0;
            padding-bottom: 40px;
        }
        .main-content {
            width: 100%;
            padding: 40px;
            box-sizing: border-box;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-header h1 {
            font-size: 2.2rem;
            color: var(--text-color);
            margin: 0;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: #fff;
        }
        .btn-primary:hover {
            background-color: #0088cc;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-action {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
        }
        .btn-action:hover {
            color: var(--primary-color);
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .kpi-card {
            background-color: var(--dark-bg-2);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .kpi-card .label {
            color: var(--text-muted);
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        .kpi-card .value {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
        }
        .table-container {
            background-color: var(--dark-bg-2);
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .container-title {
            font-size: 1.3rem;
            color: var(--text-color);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }
        .data-table th, .data-table td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }
        .data-table th {
            color: var(--text-muted);
            background-color: #f1f3f4;
            font-weight: 600;
        }
        .data-table td {
            color: var(--text-color);
        }
        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: var(--dark-bg-1);
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }
        .modal-header h2 {
            margin: 0;
            color: var(--text-color);
            font-size: 1.5rem;
        }
        .close-btn {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
        }
        .close-btn:hover {
            color: var(--text-color);
            background-color: #f1f3f4;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            background-color: var(--dark-bg-1);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-color);
            box-sizing: border-box;
            font-size: 0.9rem;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 255, 0.1);
        }
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .search-input-container {
            position: relative;
            flex-grow: 1;
            max-width: 400px;
        }
        .search-input-container .search-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        .search-input {
            width: 100%;
            padding: 10px 40px 10px 15px;
            background-color: var(--dark-bg-1);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-color);
            box-sizing: border-box;
            font-size: 1rem;
        }
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 255, 0.1);
        }
        #investorInvestmentsTable {
            margin-top: 20px;
        }
        .hidden {
            display: none;
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
    </style>
@endsection


@section('content')

    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-users-cog"></i> إدارة المستثمرين والشركاء</h1>
            <div style="display: flex; gap: 15px;">
                <button class="btn btn-primary" onclick="openInvestorModal()">
                    <i class="fas fa-user-plus"></i> إضافة مستثمر
                </button>
                <button class="btn btn-secondary" onclick="openInvestmentModal()">
                    <i class="fas fa-money-bill-wave"></i> إضافة استثمار
                </button>
            </div>
        </div>

        <div class="dashboard">
            <div class="kpi-card">
                <div class="label">إجمالي عدد المستثمرين</div>
                <div class="value" id="totalInvestors">0</div>
            </div>
            <div class="kpi-card">
                <div class="label">إجمالي رأس المال المستثمر</div>
                <div class="value" id="totalInvestedCapital">0</div>
            </div>
            <div class="kpi-card">
                <div class="label">إجمالي الأرباح الموزعة</div>
                <div class="value" id="totalProfitsDistributed" style="color: var(--success-color);">0</div>
            </div>
            <div class="kpi-card">
                <div class="label">متوسط نسبة الحصة</div>
                <div class="value" id="avgSharePercentage">0%</div>
            </div>
        </div>
	<div class="card card-custom gutter-b">
									<div class="card-header flex-wrap border-0 pt-6 pb-0">
										<div class="card-title">
											<h3 class="card-label">Scrollable Table
											<span class="d-block text-muted pt-2 font-size-sm">scrollable datatable with fixed height</span></h3>
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
<a href="" class="btn btn-primary font-weight-bolder">												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<circle fill="#000000" cx="9" cy="15" r="6" />
														<path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span >New Record</a>
											<!--end::Button-->
										</div>
									</div>
									<div class="card-body">
										<!--begin: Datatable-->
										<table class="table table-separate table-head-custom table-checkable" id="kt_datatable1">
											<thead>
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

											</tbody>
										</table>
										<!--end: Datatable-->
									</div>
								</div>
								<!--end::Card-->

    <!-- Modals -->
    <div id="investorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="investorModalTitle">إضافة مستثمر جديد</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="investorForm">
                <input type="hidden" id="investorId">
                <div class="form-grid">
                    <div class="form-group"><label for="investorName">اسم المستثمر *</label><input type="text" id="investorName" required></div>
                    <div class="form-group"><label for="investorIdNumber">رقم الهوية</label><input type="text" id="investorIdNumber"></div>
                    <div class="form-group"><label for="investorPhone">رقم الجوال</label><input type="tel" id="investorPhone"></div>
                    <div class="form-group"><label for="investorEmail">البريد الإلكتروني</label><input type="email" id="investorEmail"></div>
                    <div class="form-group full-width"><label for="investorAddress">العنوان</label><input type="text" id="investorAddress"></div>
                    <div class="form-group full-width"><label for="investorNotes">ملاحظات</label><textarea id="investorNotes" rows="3"></textarea></div>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">حفظ المستثمر</button>
            </form>

            <div id="investorDetailsSection" style="display:none; margin-top: 30px;">
                <h3 style="color: var(--primary-color); border-top: 1px solid var(--border-color); padding-top: 20px;">استثمارات المستثمر</h3>
                <div class="table-wrapper">
                    <table class="data-table" id="investorInvestmentsTable">
                        <thead><tr><th>المشروع</th><th>المبلغ</th><th>الحصة</th><th>وظيفة المستثمر</th><th>التاريخ</th><th>الإجراءات</th></tr></thead>
                        <tbody id="investorInvestmentsBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="investmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="investmentModalTitle">إضافة استثمار جديد</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="investmentForm">
                <input type="hidden" id="investmentId">
                <div class="form-grid">
                    <div class="form-group"><label for="investmentDate">تاريخ الاستثمار *</label><input type="date" id="investmentDate" required></div>
                    <div class="form-group"><label for="investmentInvestor">اسم المستثمر *</label><select id="investmentInvestor" required><option value="">اختر المستثمر</option></select></div>
                    <div class="form-group"><label for="investmentPhone">رقم الجوال</label><input type="tel" id="investmentPhone"></div>
                    <div class="form-group"><label for="investmentIdNumber">رقم الهوية</label><input type="text" id="investmentIdNumber"></div>
                    <div class="form-group"><label for="investmentJob">وظيفته</label><input type="text" id="investmentJob"></div>
                    <div class="form-group"><label for="investmentProject">المشروع *</label><input type="text" id="investmentProject" required></div>
                    <div class="form-group"><label for="investmentType">بماذا تم الاستثمار (شقة/أرض/مبلغ)</label><input type="text" id="investmentType" required></div>
                    <div class="form-group"><label for="cheque_currency">العملة</label><select id="cheque_currency"><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select></div>
                    <div class="form-group"><label for="investmentAmount">المبلغ *</label><input type="number" id="investmentAmount" required step="0.01" min="0"></div>
                    <div class="form-group"><label for="investmentShare">حصته من الأرباح (%) *</label><input type="number" id="investmentShare" required step="0.01" min="0" max="100"></div>
                    <div class="form-group"><label for="investmentStatus">حالة الاستثمار *</label><select id="investmentStatus" required><option value="active">نشط</option><option value="completed">مكتمل</option><option value="cancelled">ملغي</option></select></div>
                    <div class="form-group">
                        <label for="investmentPaymentMethod">طريقة الدفع *</label>
                        <select id="investmentPaymentMethod" required>
                            <option value="">اختر الطريقة</option>
                            <option value="كاش">كاش</option>
                            <option value="شيك">شيك</option>
                            <option value="معاملة بنكية">معاملة بنكية</option>
                            <option value="تقسيط">تقسيط</option>
                        </select>
                    </div>
                    <div class="form-group"><label for="investmentPayee">لمن تم الدفع *</label><select id="investmentPayee" required><option value="">اختر الشخص</option><option value="خالد">خالد</option><option value="محمد">محمد</option></select></div>
                    <div class="form-group"><label for="investmentPaymentDate">تاريخ الدفع *</label><input type="date" id="investmentPaymentDate" required></div>

                    <!-- Cash Details Section -->
                    <div class="form-group full-width hidden" id="investmentCashDetails">
                        <h4 style="color: var(--primary-color); margin-bottom: 15px;">تفاصيل الدفع النقدي</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="investmentCashReceiver">من استلم المبلغ</label>
                                <select id="investmentCashReceiver" name="cash_receiver">
                                    <option value="">-- اختر المستلم --</option>
                                    <option value="محمد">محمد</option>
                                    <option value="خالد">خالد</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Cheque Details Section -->
                    <div class="form-group full-width hidden" id="investmentChequeDetails">
                        <h4 style="color: var(--primary-color); margin-bottom: 15px;">تفاصيل الشيك</h4>
                        <div class="form-grid">
                             <div class="form-group">
                                <label for="investmentChequeDueDate">تاريخ استحقاق الشيك</label>
                                <input type="date" id="investmentChequeDueDate" name="cheque_due_date">
                            </div>
                            <div class="form-group">
                                <label for="investmentChequeNumber">رقم الشيك</label>
                                <input type="text" id="investmentChequeNumber" name="cheque_number" placeholder="أدخل رقم الشيك">
                            </div>
                             <div class="form-group">
                                <label for="investmentChequeNumber">اسم مالك الشيك</label>
                                <input type="text" id="investmentChequeNumber" name="cheque_number" placeholder="أدخل رقم الشيك">
                            </div> <div class="form-group">
                                <label for="investmentChequeNumber">اسم صاحب الشيك</label>
                                <input type="text" id="investmentChequeNumber" name="cheque_number" placeholder="أدخل رقم الشيك">
                            </div>
                            <div class="form-group">
                                <label for="investmentChequeDueDate">تاريخ استلام الشيك</label>
                                <input type="date" id="investmentChequeDueDate" name="cheque_due_date">
                            </div>
                        </div>
                    </div>

                    <!-- Bank Details Section -->
                    <div class="form-group full-width hidden" id="investmentBankDetails">
                        <h4 style="color: var(--primary-color); margin-bottom: 15px;">تفاصيل المعاملة البنكية</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="investmentBankName">اختر البنك</label>
                                <select id="investmentBankName">
                                    <option value="">-- اختر من القائمة --</option>
                                    <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                    <option value="بنك الصفا">بنك الصفا</option>
                                    <option value="بنك فلسطين">بنك فلسطين</option>
                                    <option value="البنك العربي">البنك العربي</option>
                                    <option value="other">أخرى (حدد)</option>
                                </select>
                            </div>
                             <div class="form-group hidden" id="investmentOtherBankGroup"><label for="investmentOtherBankName">اسم البنك (أخرى)</label><input type="text" id="investmentOtherBankName" placeholder="اكتب اسم البنك هنا"></div>
                            <div class="form-group hidden" id="investmentOtherBankGroup"><label for="investmentOtherBankName">اسم فرع البنك</label><input type="text" id="investmentOtherBankName" placeholder="اكتب اسم البنك هنا"></div>

                            <div class="form-group"><label for="investmentTransactionId">رقم التحويلة</label><input type="text" id="investmentTransactionId" placeholder="أدخل رقم التحويلة"></div>
                          <div class="form-group">
                                <label for="investmentBankName">اختر البنك</label>
                                <select id="investmentBankName">
                                    <option value="">-- اختر من القائمة --</option>
                                    <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                    <option value="بنك الصفا">بنك الصفا</option>
                                    <option value="بنك فلسطين">بنك فلسطين</option>
                                    <option value="البنك العربي">البنك العربي</option>
                                    <option value="other">أخرى (حدد)</option>
                                </select>
                            </div>
                          <div class="form-group hidden" id="investmentOtherBankGroup"><label for="investmentOtherBankName">اسم البنك (أخرى)</label><input type="text" id="investmentOtherBankName" placeholder="اكتب اسم البنك هنا"></div>
                            <div class="form-group hidden" id="investmentOtherBankGroup"><label for="investmentOtherBankName">اسم فرع البنك</label><input type="text" id="investmentOtherBankName" placeholder="اكتب اسم البنك هنا"></div>

                        </div>
                    </div>

                    <div class="form-group"><label for="investmentContractId">رقم العقد/الإيصال</label><input type="text" id="investmentContractId"></div>
                    <div class="form-group"><label for="investmentContractFile">مرفق العقد</label><input type="file" id="investmentContractFile" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"></div>
                </div>
                <div class="form-group full-width"><label for="investmentNotes">اتفاق خاص (شقق/غيره)</label><textarea id="investmentNotes" rows="3" placeholder="أدخل أي ملاحظات أو اتفاقات خاصة..."></textarea></div>
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">حفظ الاستثمار</button>
            </form>
        </div>
    </div>

@endsection


@section('script')
   <script>
        // --- UTILITY FUNCTIONS ---
        const getDB = (key) => JSON.parse(localStorage.getItem(key)) || [];
        const setDB = (key, data) => localStorage.setItem(key, JSON.stringify(data));
        const formatCurrency = (num) => new Intl.NumberFormat('ar-SA').format(num || 0) + ' شيكل';

        // --- MODAL CONTROLS ---
        function openInvestorModal(id = null) {
            const form = document.getElementById('investorForm');
            form.reset();
            document.getElementById('investorId').value = '';
            const title = document.getElementById('investorModalTitle');
            const detailsSection = document.getElementById('investorDetailsSection');
            detailsSection.style.display = 'none';

            if (id) {
                title.textContent = 'تعديل بيانات مستثمر';
                const investor = getDB('investors').find(inv => inv.id === id);
                if (investor) {
                    document.getElementById('investorId').value = investor.id;
                    document.getElementById('investorName').value = investor.name || '';
                    document.getElementById('investorIdNumber').value = investor.idNumber || '';
                    document.getElementById('investorPhone').value = investor.phone || '';
                    document.getElementById('investorEmail').value = investor.email || '';
                    document.getElementById('investorAddress').value = investor.address || '';
                    document.getElementById('investorNotes').value = investor.notes || '';
                    detailsSection.style.display = 'block';
                    renderInvestorInvestments(id);
                }
            } else {
                title.textContent = 'إضافة مستثمر جديد';
            }
            document.getElementById('investorModal').style.display = 'flex';
        }

        function openInvestmentModal(investmentId = null) {
            const form = document.getElementById('investmentForm');
            form.reset();
            document.getElementById('investmentId').value = '';
            const title = document.getElementById('investmentModalTitle');
            populateInvestorsDropdown();
            toggleInvestmentPaymentDetails(); // Reset all dynamic fields

            if (investmentId) {
                title.textContent = 'تعديل بيانات الاستثمار';
                const investment = getDB('investments').find(i => i.id === investmentId);
                if (investment) {
                    // Populate form fields...
                    document.getElementById('investmentId').value = investment.id;
                    document.getElementById('investmentInvestor').value = investment.investorId || '';
                    document.getElementById('investmentProject').value = investment.project || '';
                    document.getElementById('investmentAmount').value = investment.amount || '';
                    document.getElementById('investmentShare').value = investment.share || '';
                    document.getElementById('investmentDate').value = investment.date || '';
                    document.getElementById('investmentStatus').value = investment.status || 'active';
                    document.getElementById('investmentPaymentMethod').value = investment.paymentMethod || '';
                    // ... and so on for all fields

                    // Update dynamic sections based on loaded data
                    toggleInvestmentPaymentDetails();
                }
            } else {
                title.textContent = 'إضافة استثمار جديد';
            }
            document.getElementById('investmentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('investorModal').style.display = 'none';
            document.getElementById('investmentModal').style.display = 'none';
        }

        // --- FORM HANDLERS ---
        document.getElementById('investorForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // ... (Investor form submission logic)
            showAlert('تم حفظ بيانات المستثمر بنجاح', 'success');
        });

        document.getElementById('investmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // ... (Investment form submission logic)
            showAlert('تم حفظ بيانات الاستثمار بنجاح', 'success');
        });

        // --- DYNAMIC FIELD HANDLERS ---
        document.getElementById('investmentPaymentMethod').addEventListener('change', toggleInvestmentPaymentDetails);

        function toggleInvestmentPaymentDetails() {
            const paymentMethod = document.getElementById('investmentPaymentMethod').value;

            // Hide all sections first
            document.getElementById('investmentCashDetails').classList.add('hidden');
            document.getElementById('investmentBankDetails').classList.add('hidden');
            document.getElementById('investmentChequeDetails').classList.add('hidden');

            // Show the relevant section
            if (paymentMethod === 'كاش') {
                document.getElementById('investmentCashDetails').classList.remove('hidden');
            } else if (paymentMethod === 'معاملة بنكية') {
                document.getElementById('investmentBankDetails').classList.remove('hidden');
            } else if (paymentMethod === 'شيك') {
                document.getElementById('investmentChequeDetails').classList.remove('hidden');
            }
        }

        document.getElementById('investmentBankName').addEventListener('change', function() {
            const otherBankGroup = document.getElementById('investmentOtherBankGroup');
            otherBankGroup.classList.toggle('hidden', this.value !== 'other');
        });

        // --- RENDER FUNCTIONS ---
        function renderInvestors() {
            const investors = getDB('investors');
            const investments = getDB('investments');
            const tbody = document.getElementById('investorsListBody');
            tbody.innerHTML = '';

            if (investors.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: var(--text-muted);">لا توجد بيانات مستثمرين</td></tr>`;
                updateKPIs();
                return;
            }

            investors.forEach(investor => {
                const investorInvestments = investments.filter(inv => inv.investorId === investor.id);
                const totalInvestment = investorInvestments.reduce((sum, inv) => sum + (inv.amount || 0), 0);
                const row = `
                    <td>${investor.name || '-'}</td>
                    <td>${investor.idNumber || '-'}</td>
                    <td>${investor.phone || '-'}</td>
                    <td>${investor.email || '-'}</td>
                    <td>${formatCurrency(totalInvestment)}</td>
                    <td>
                        <button class="btn-action" onclick="openInvestorModal('${investor.id}')" title="تعديل"><i class="fas fa-edit"></i></button>
                        <button class="btn-action" onclick="deleteInvestor('${investor.id}')" title="حذف"><i class="fas fa-trash"></i></button>
                    </td>`;
                tbody.innerHTML += `<tr>${row}</tr>`;
            });
            updateKPIs();
        }

        function renderInvestorInvestments(investorId) {
            // ... (Render investments for a specific investor)
        }

        function populateInvestorsDropdown() {
            const investors = getDB('investors');
            const select = document.getElementById('investmentInvestor');
            select.innerHTML = '<option value="">اختر المستثمر</option>';
            investors.forEach(investor => {
                select.innerHTML += `<option value="${investor.id}">${investor.name}</option>`;
            });
        }

        function updateKPIs() {
            // ... (Update Key Performance Indicators)
        }

        // --- DELETE FUNCTIONS ---
        function deleteInvestor(id) {
            // ... (Delete investor logic)
        }

        function deleteInvestment(id) {
            // ... (Delete investment logic)
        }

        // --- ALERT FUNCTION ---
        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            const mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            setTimeout(() => alertDiv.remove(), 3000);
        }

        // --- INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', function() {
            renderInvestors();
        });

        // --- EVENT LISTENERS FOR MODAL ---
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) closeModal();
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') closeModal();
        });
    </script>
@endsection
