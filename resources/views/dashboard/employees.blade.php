@extends('layouts.container')
@section('title', 'إدارة الموظفين والرواتب')

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
        flex-wrap: wrap;
        gap: 15px;
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

    .header-actions {
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

    .btn-action {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 1.1rem;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        color: var(--primary-color);
        background-color: rgba(79, 70, 229, 0.1);
    }

    /* حاويات الجداول */
    .table-container {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .container-title {
        font-size: 1.5rem;
        color: var(--primary-color);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 15px;
    }

    .container-title i {
        color: var(--primary-color);
    }

    .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    /* أنماط الجداول */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
        background-color: var(--white-bg);
    }

    .data-table th,
    .data-table td {
        padding: 15px 12px;
        text-align: right;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
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

    /* أنماط الحالات */
    .status {
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
    }

    .status-paid {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-unpaid {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .status-partial {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    /* قسم التقرير */
    .report-section {
        display: none;
        margin-top: 30px;
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        border-top: 4px solid var(--primary-color);
    }

    .report-section h2 {
        color: var(--primary-color);
        margin-bottom: 20px;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
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
        max-width: 600px;
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
        margin-bottom: 25px;
        text-align: center;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    /* مجموعات النماذج */
    .form-group {
        margin-bottom: 20px;
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

    /* العناصر المخفية */
    .hidden {
        display: none !important;
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

        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .table-container {
            padding: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 10px 8px;
            font-size: 0.9rem;
        }

        .modal-content {
            width: 95%;
            padding: 20px;
        }
    }

    /* تحسينات إضافية */
    .actions-cell {
        display: flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
    }

    .currency-display {
        font-weight: 600;
        color: var(--success-color);
    }

    .employee-name {
        font-weight: 600;
        color: var(--text-color);
    }

    .position-badge {
        background-color: var(--light-bg);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1><i class="fas fa-users"></i> إدارة الموظفين والرواتب</h1>
        <div class="header-actions">

            <button class="btn btn-primary" onclick="openEmployeeModal()">
                <i class="fas fa-user-plus"></i> إضافة موظف جديد
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

    <!-- قسم التقرير التاريخي -->
    <div id="reportSection" class="report-section">
        <h2 id="reportTitle">
            <i class="fas fa-history"></i>
            السجل التاريخي للرواتب:
        </h2>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>تاريخ الدفع</th>
                        <th>الشهر المدفوع عنه</th>
                        <th>المبلغ المدفوع</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody id="salaryHistoryBody"></tbody>
            </table>
        </div>
    </div>
</main>

<!-- مودال إضافة/تعديل الموظف -->
<div id="employeeModal" class="modal">
    <div class="modal-content">
        <h2 id="employeeModalTitle">
            <i class="fas fa-user-plus"></i>
            إضافة موظف جديد
        </h2>

        <form id="employeeForm" action="{{ route('dashboard.employees.create') }}" method="POST">
            @csrf
            <input type="hidden" id="employeeId" name="id">

            <div class="form-group">
                <label for="employeeName">اسم الموظف</label>
                <input type="text" id="employeeName" name="name" required>
            </div>

            <div class="form-group">
                <label for="employeePosition">المنصب الوظيفي</label>
                <input type="text" id="employeePosition" name="position" placeholder="مثال: مهندس، محاسب" required>
            </div>

            <div class="form-group">
                <label for="employeeGmail">البريد الإلكتروني</label>
                <input type="email" id="employeeGmail" name="email" placeholder="example@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="employeePhone">رقم الجوال</label>
                <input type="tel" id="employeePhone" name="phone" placeholder="0599123456" required>
            </div>

            <div class="form-group">
                <label for="employeeIban">الحساب البنكي (IBAN)</label>
                <input type="text" id="employeeIban" name="iban" placeholder="PS00XXXX0000000000000000000" required>
            </div>

            <div class="form-group">
                <label for="employeeSalary">الراتب الشهري الأساسي (شيكل)</label>
                <input type="number" id="employeeSalary" name="salary" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="currency">العملة</label>
                <select id="currency" name="currency" required>
                    <option value="شيكل">شيكل</option>
                    <option value="دولار">دولار</option>
                    <option value="دينار">دينار</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bankName">محفظة</label>
                <select id="bankName" name="wallet_name" required>
                    <option value="">-- اختر من القائمة --</option>
                    <option value="بنك القاهرة عمان">بال باي </option>
                    <option value="other">أخرى (حدد)</option>
                </select>
            </div>

            <div class="form-group hidden" id="otherBankNameGroup">
                <label for="otherBankName">اسم محفظة (أخرى)</label>
                <input type="text" id="otherBankName" name="wallet_other_name" placeholder="اكتب اسم البنك هنا">
            </div>

            <div class="form-group">
                <label for="bankName">البنك</label>
                <select id="bankName" name="bank_name" required>
                    <option value="">-- اختر من القائمة --</option>
                    <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                    <option value="بنك الصفا">بنك الصفا</option>
                    <option value="بنك فلسطين">بنك فلسطين</option>
                    <option value="البنك العربي">البنك العربي</option>
                    <option value="other">أخرى (حدد)</option>
                </select>
            </div>

            <div class="form-group hidden" id="otherBankNameGroup">
                <label for="otherBankName">اسم البنك (أخرى)</label>
                <input type="text" id="otherBankName" name="other_bank_name" placeholder="اكتب اسم البنك هنا">
            </div>

            <div class="form-group">
                <label for="bankBranch">فرع البنك</label>
                <input type="text" id="bankBranch" name="bank_branch" placeholder="اكتب اسم الفرع هنا">
            </div>

            <div style="display: flex; gap: 15px; margin-top: 25px;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> حفظ الموظف
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()" style="flex: 1;">
                    <i class="fas fa-times"></i> إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<!-- مودال دفع الراتب -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <h2 id="paymentModalTitle">
            <i class="fas fa-money-bill-wave"></i>
            تسجيل دفع الراتب
        </h2>

        <form id="paymentForm" action="{{ "" }}" method="POST">
            @csrf
            <input type="hidden" id="paymentEmployeeId" name="employee_id">

            <div class="form-group">
                <label for="paymentAmount">المبلغ المدفوع (شيكل)</label>
                <input type="number" id="paymentAmount" name="amount" min="0" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="paymentNotes">ملاحظات (اختياري)</label>
                <textarea id="paymentNotes" name="notes" rows="3" placeholder="مثال: خصم 500 شيكل بسبب غياب..."></textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 25px;">
                <button type="submit" class="btn btn-success" style="flex: 1;">
                    <i class="fas fa-check"></i> تأكيد الدفع
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeModal()" style="flex: 1;">
                    <i class="fas fa-times"></i> إلغاء
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    // دوال إدارة قاعدة البيانات المحلية
    function getDB(key) {
        return JSON.parse(localStorage.getItem(key)) || [];
    }

    function setDB(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }

    function formatCurrency(num) {
        return new Intl.NumberFormat('ar-SA', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(num || 0) + ' شيكل';
    }

    // دالة الحصول على الشهر والسنة الحالية
    const getCurrentMonthYear = () => {
        const now = new Date();
        return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
    };

    // دالة عرض قائمة الموظفين
    function renderEmployeesList() {
        const employees = getDB('employees');
        const salary_payments = getDB('salary_payments');
        const listBody = document.getElementById('employeesListBody');
        listBody.innerHTML = '';

        const currentMonth = getCurrentMonthYear();
        const monthName = new Date().toLocaleString('ar', { month: 'long' });
        const year = new Date().getFullYear();
        document.getElementById('currentMonthTitle').innerHTML = `<i class="fas fa-calendar-alt"></i> كشف رواتب شهر: ${monthName} ${year}`;

        if (employees.length === 0) {
            listBody.innerHTML = '<tr><td colspan="8" style="text-align:center; color: var(--text-muted); padding: 40px;">لا توجد موظفين مسجلين بعد. اضغط على "إضافة موظف جديد" لبدء الإضافة.</td></tr>';
            return;
        }

        employees.forEach(emp => {
            const paymentThisMonth = salary_payments.find(p => p.employeeId === emp.id && p.monthYear === currentMonth);
            let statusHtml;

            if (paymentThisMonth) {
                const paidAmount = parseFloat(paymentThisMonth.amount);
                const baseSalary = parseFloat(emp.salary);
                if (paidAmount >= baseSalary) {
                    statusHtml = `<span class="status status-paid">تم الدفع (${formatCurrency(paidAmount)})</span>`;
                } else {
                    statusHtml = `<span class="status status-partial">دفع جزئي (${formatCurrency(paidAmount)})</span>`;
                }
            } else {
                statusHtml = `
                    <span class="status status-unpaid">لم يتم الدفع</span>
                    <br><br>
                    <button class="btn btn-success" style="padding: 8px 12px; font-size: 0.85rem;" onclick="openPaymentModal(${emp.id})">
                        <i class="fas fa-money-bill-wave"></i> دفع الآن
                    </button>
                `;
            }

            const row = `
                <tr>
                    <td><span class="employee-name">${emp.name}</span></td>
                    <td><span class="position-badge">${emp.position}</span></td>
                    <td>${emp.gmail || '-'}</td>
                    <td>${emp.phone || '-'}</td>
                    <td>${emp.iban || '-'}</td>
                    <td><span class="currency-display">${formatCurrency(emp.salary)}</span></td>
                    <td>${statusHtml}</td>
                    <td>
                        <div class="actions-cell">
                            <button class="btn-action" title="عرض السجل التاريخي" onclick="renderSalaryHistory(${emp.id})">
                                <i class="fas fa-history"></i>
                            </button>
                            <button class="btn-action" title="تعديل بيانات الموظف" onclick="openEmployeeModal(${emp.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action" title="حذف الموظف" onclick="deleteEmployee(${emp.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            listBody.innerHTML += row;
        });
    }

    // دالة فتح مودال الموظف
    function openEmployeeModal(id = null) {
        const form = document.getElementById('employeeForm');
        form.reset();
        document.getElementById('employeeId').value = '';

        if (id !== null) {
            document.getElementById('employeeModalTitle').innerHTML = '<i class="fas fa-user-edit"></i> تعديل بيانات الموظف';
            const employee = getDB('employees').find(e => e.id === id);
            if (employee) {
                document.getElementById('employeeId').value = employee.id;
                document.getElementById('employeeName').value = employee.name;
                document.getElementById('employeePosition').value = employee.position;
                document.getElementById('employeeGmail').value = employee.gmail || '';
                document.getElementById('employeePhone').value = employee.phone || '';
                document.getElementById('employeeIban').value = employee.iban || '';
                document.getElementById('employeeSalary').value = employee.salary;
                document.getElementById('currency').value = employee.currency || 'شيكل';
                document.getElementById('bankName').value = employee.bankName || '';
                document.getElementById('bankBranch').value = employee.bankBranch || '';

                // إظهار حقل البنك الآخر إذا كان محدداً
                if (employee.bankName === 'other') {
                    document.getElementById('otherBankNameGroup').classList.remove('hidden');
                    document.getElementById('otherBankName').value = employee.otherBankName || '';
                }
            }
        } else {
            document.getElementById('employeeModalTitle').innerHTML = '<i class="fas fa-user-plus"></i> إضافة موظف جديد';
        }
        openModal('employeeModal');
    }

    // دالة حفظ الموظف
    function saveEmployee(e) {
        e.preventDefault();
        let employees = getDB('employees');
        const id = document.getElementById('employeeId').value;

        const employeeData = {
            name: document.getElementById('employeeName').value,
            position: document.getElementById('employeePosition').value,
            gmail: document.getElementById('employeeGmail').value,
            phone: document.getElementById('employeePhone').value,
            iban: document.getElementById('employeeIban').value,
            salary: document.getElementById('employeeSalary').value,
            currency: document.getElementById('currency').value,
            bankName: document.getElementById('bankName').value,
            bankBranch: document.getElementById('bankBranch').value,
        };

        // إضافة اسم البنك الآخر إذا كان محدداً
        if (employeeData.bankName === 'other') {
            employeeData.otherBankName = document.getElementById('otherBankName').value;
        }

        if (id) {
            // تحديث موظف موجود
            const index = employees.findIndex(e => e.id == id);
            employees[index] = { ...employees[index], ...employeeData };
        } else {
            // إضافة موظف جديد
            employeeData.id = Date.now();
            employees.push(employeeData);
        }

        setDB('employees', employees);

        if (typeof Swal !== 'undefined') {
            Swal.fire('تم', 'تم حفظ بيانات الموظف بنجاح', 'success');
        } else {
            alert('تم حفظ بيانات الموظف بنجاح');
        }

        closeModal();
        renderEmployeesList();
    }

    // دالة حذف الموظف
    function deleteEmployee(id) {
        const confirmDelete = () => {
            let employees = getDB('employees').filter(e => e.id !== id);
            setDB('employees', employees);
            let payments = getDB('salary_payments').filter(p => p.employeeId !== id);
            setDB('salary_payments', payments);

            if (typeof Swal !== 'undefined') {
                Swal.fire('تم الحذف!', 'تم حذف الموظف وبياناته.', 'success');
            } else {
                alert('تم حذف الموظف وبياناته.');
            }

            renderEmployeesList();
            document.getElementById('reportSection').style.display = 'none';
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم حذف الموظف وكل سجلات رواتبه!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger-color)',
                cancelButtonText: 'إلغاء',
                confirmButtonText: 'نعم، احذف!'
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmDelete();
                }
            });
        } else {
            if (confirm('هل أنت متأكد من حذف هذا الموظف؟ سيتم حذف جميع بياناته!')) {
                confirmDelete();
            }
        }
    }

    // دالة عرض السجل التاريخي للرواتب
    function renderSalaryHistory(employeeId) {
        const reportSection = document.getElementById('reportSection');
        reportSection.style.display = 'block';

        const employee = getDB('employees').find(e => e.id === employeeId);
        const payments = getDB('salary_payments')
            .filter(p => p.employeeId === employeeId)
            .sort((a, b) => new Date(b.paymentDate) - new Date(a.paymentDate));

        document.getElementById('reportTitle').innerHTML = `<i class="fas fa-history"></i> السجل التاريخي للرواتب: ${employee.name}`;

        const tableBody = document.getElementById('salaryHistoryBody');
        tableBody.innerHTML = '';

        if (payments.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" style="text-align:center; color: var(--text-muted); padding: 40px;">لا توجد مدفوعات مسجلة لهذا الموظف بعد.</td></tr>';
            return;
        }

        payments.forEach(p => {
            const monthName = new Date(p.monthYear + '-01').toLocaleString('ar', { month: 'long', year: 'numeric' });
            const paymentDate = new Date(p.paymentDate).toLocaleDateString('ar-EG');
            const row = `
                <tr>
                    <td>${paymentDate}</td>
                    <td>${monthName}</td>
                    <td><span class="currency-display">${formatCurrency(p.amount)}</span></td>
                    <td>${p.notes || '-'}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });

        // التمرير إلى قسم التقرير
        reportSection.scrollIntoView({ behavior: 'smooth' });
    }

    // دالة فتح مودال الدفع
    function openPaymentModal(employeeId) {
        const form = document.getElementById('paymentForm');
        form.reset();

        const employee = getDB('employees').find(e => e.id === employeeId);
        document.getElementById('paymentEmployeeId').value = employeeId;
        document.getElementById('paymentAmount').value = employee.salary;

        const monthName = new Date().toLocaleString('ar', { month: 'long' });
        document.getElementById('paymentModalTitle').innerHTML = `<i class="fas fa-money-bill-wave"></i> دفع راتب ${employee.name} لشهر ${monthName}`;

        openModal('paymentModal');
    }

    // دالة تسجيل الدفع
    function recordPayment(e) {
        e.preventDefault();

        const employeeId = parseInt(document.getElementById('paymentEmployeeId').value);
        const amount = parseFloat(document.getElementById('paymentAmount').value);
        const notes = document.getElementById('paymentNotes').value;
        const currentMonth = getCurrentMonthYear();

        let payments = getDB('salary_payments');

        // التحقق من وجود دفعة سابقة لنفس الشهر
        const existingPaymentIndex = payments.findIndex(p => p.employeeId === employeeId && p.monthYear === currentMonth);

        const paymentData = {
            employeeId: employeeId,
            monthYear: currentMonth,
            amount: amount,
            paymentDate: new Date().toISOString().split('T')[0],
            notes: notes
        };

        if (existingPaymentIndex > -1) {
            // تحديث الدفعة الموجودة
            payments[existingPaymentIndex] = { ...payments[existingPaymentIndex], ...paymentData };
        } else {
            // إضافة دفعة جديدة
            paymentData.id = Date.now();
            payments.push(paymentData);
        }

        setDB('salary_payments', payments);

        if (typeof Swal !== 'undefined') {
            Swal.fire('تم', 'تم تسجيل دفع الراتب بنجاح', 'success');
        } else {
            alert('تم تسجيل دفع الراتب بنجاح');
        }

        closeModal();
        renderEmployeesList();
    }

    // دوال المودال
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    }

    function closeModal() {
        document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
        // إخفاء حقل البنك الآخر عند إغلاق المودال
        document.getElementById('otherBankNameGroup').classList.add('hidden');
    }

    // دالة التصدير إلى Excel
    function exportToExcel() {
        if (typeof XLSX === 'undefined') {
            alert('مكتبة التصدير إلى Excel غير متوفرة');
            return;
        }

        const employees = getDB('employees');
        const payments = getDB('salary_payments');

        // إعداد بيانات الموظفين
        const employeesData = employees.map(emp => ({
            'اسم الموظف': emp.name,
            'المنصب': emp.position,
            'البريد الإلكتروني': emp.gmail,
            'رقم الجوال': emp.phone,
            'الحساب البنكي': emp.iban,
            'الراتب الأساسي': emp.salary,
            'العملة': emp.currency,
            'البنك': emp.bankName === 'other' ? emp.otherBankName : emp.bankName,
            'الفرع': emp.bankBranch
        }));

        // إعداد بيانات المدفوعات
        const paymentsData = payments.map(payment => {
            const employee = employees.find(e => e.id === payment.employeeId);
            return {
                'اسم الموظف': employee ? employee.name : 'غير معروف',
                'الشهر': payment.monthYear,
                'تاريخ الدفع': payment.paymentDate,
                'المبلغ المدفوع': payment.amount,
                'ملاحظات': payment.notes || '-'
            };
        });

        const wb = XLSX.utils.book_new();
        const employeesSheet = XLSX.utils.json_to_sheet(employeesData);
        const paymentsSheet = XLSX.utils.json_to_sheet(paymentsData);

        XLSX.utils.book_append_sheet(wb, employeesSheet, "الموظفين");
        XLSX.utils.book_append_sheet(wb, paymentsSheet, "المدفوعات");

        const today = new Date().toISOString().split('T')[0];
        XLSX.writeFile(wb, `تقرير_الموظفين_والرواتب_${today}.xlsx`);
    }

    // التهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // عرض قائمة الموظفين
        renderEmployeesList();

        // إضافة مستمعي الأحداث للنماذج
        document.getElementById('employeeForm').addEventListener('submit', saveEmployee);
        document.getElementById('paymentForm').addEventListener('submit', recordPayment);

        // إضافة مستمع لتغيير البنك
        document.getElementById('bankName').addEventListener('change', function() {
            const otherBankGroup = document.getElementById('otherBankNameGroup');
            if (this.value === 'other') {
                otherBankGroup.classList.remove('hidden');
                document.getElementById('otherBankName').required = true;
            } else {
                otherBankGroup.classList.add('hidden');
                document.getElementById('otherBankName').required = false;
            }
        });

        // إغلاق المودال عند النقر خارجه
        window.addEventListener('click', (event) => {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        });
    });
</script>
@endsection
