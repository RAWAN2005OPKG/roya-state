@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('styles')
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
<form id="chequeForm" action="{{ route('dashboard.cheques.store') }}" method="POST">
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
