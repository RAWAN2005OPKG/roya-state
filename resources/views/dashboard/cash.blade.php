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
<form id="cashForm" action="{{ route('dashboard.cash.store') }}" method="POST">
                @csrf
                <div class="form-group"><label for="cash_date">تاريخ الحركة</label><input type="date" id="cash_date" name="date" required></div>
                <div class="form-group"><label for="cash_type">نوع الحركة</label><select id="cash_type" name="type" required><option value="deposit">إيداع</option><option value="withdrawal">سحب نقدي</option><option value="personal_withdrawal">مسحوبات شخصية</option></select></div>
                <div class="form-group"><label for="cheque_project_name">اسم صاحب المبلغ</label><input type="text" id="cheque_project_name" name="project_name" placeholder="المشروع المرتبط بالشيك"></div>
                <div class="form-group"><label for="payer_id_number">رقم الهوية</label><input type="text" id="payer_id_number" name="payer_id_number"></div>
                <div class="form-group"><label for="client_phone">رقم الجوال</label><input type="text" id="client_phone" name="client_phone"></div>
                <div class="form-group"><label for="beneficiary_name">المستلم</label><select id="beneficiary_name" name="beneficiary" required><option value="خالد">خالد</option><option value="محمد">محمد</option></select></div>
                <div class="form-group"><label for="cash_operator_role">وظيفته</label><input type="text" id="cash_operator_role" name="operator_role" placeholder="مثال: مدير مالي"></div>
                <div class="form-group"><label for="cheque_currency">العملة</label><select id="cheque_currency" name="currency" required><option value="شيكل">شيكل</option><option value="دولار">دولار</option><option value="دينار">دينار</option></select></div>
                <div class="form-group"><label for="cheque_amount">المبلغ</label><input type="number" id="cheque_amount" name="amount" step="0.01" required></div>
                <div class="form-group full-width"><label for="cash_details">تفاصيل</label><input type="text" id="cash_details" name="details"></div>
                <div class="form-group full-width"><label for="cash_notes">ملاحظات</label><input type="text" id="cash_notes" name="notes"></div>
                <button type="submit">حفظ الحركة</button>
            </form>
        </div>

<!--begin::Card-->
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
