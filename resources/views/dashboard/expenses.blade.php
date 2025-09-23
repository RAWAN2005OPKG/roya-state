@extends('layouts.container')
@section('title', 'سجل المصروفات الشامل')

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
        text-align: center;
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
        justify-content: center;
        gap: 15px;
    }

    .page-header h1 i {
        color: var(--primary-color);
    }

    /* حاويات النماذج والجداول */
    .form-container,
    .table-container {
        background-color: var(--white-bg);
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .container-title {
        font-size: 1.8rem;
        color: var(--primary-color);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .container-title i {
        color: var(--primary-color);
    }

    /* شبكة النماذج */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
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

    /* أزرار الإرسال */
    .btn-submit {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        background-color: var(--primary-color);
        color: #fff;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        grid-column: 1 / -1;
        margin-top: 20px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* العناصر المخفية */
    .hidden {
        display: none !important;
    }

    /* عناصر التحكم بالجدول */
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
        padding: 12px 45px 12px 15px;
        background-color: var(--white-bg);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        box-sizing: border-box;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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

    .btn-secondary {
        background-color: var(--text-muted);
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    /* أنماط الجداول */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
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

    /* أقسام الحقول الديناميكية */
    .dynamic-section {
        grid-column: 1 / -1;
        padding: 20px;
        background-color: var(--light-bg);
        border-radius: 12px;
        border: 2px solid var(--border-color);
        margin-top: 15px;
    }

    .dynamic-section h4 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dynamic-section .form-grid {
        gap: 15px;
    }

    /* تصميم متجاوب */
    @media (max-width: 768px) {
        .main-content {
            padding: 10px;
            margin: 10px auto;
        }

        .page-header {
            padding: 20px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .form-container,
        .table-container {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .table-controls {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-container {
            max-width: none;
        }

        .data-table th,
        .data-table td {
            padding: 10px 8px;
            font-size: 0.9rem;
        }
    }

    /* تحسينات إضافية */
    .currency-display {
        font-weight: 600;
        color: var(--success-color);
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-success {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .status-warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning-color);
    }

    .status-danger {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1><i class="fas fa-file-invoice-dollar"></i> سجل المصروفات الشامل</h1>
    </div>

    <!-- نموذج تسجيل مصروف جديد -->
    <div class="form-container">
        <h2 class="container-title">
            <i class="fas fa-plus-circle"></i>
            تسجيل مصروف جديد
        </h2>
        
        <form id="expenseForm" class="form-grid" action="{{ route('dashboard.expenses.store') }}" method="POST">
            @csrf
            <!-- الحقول الأساسية -->
            <div class="form-group">
                <label for="expenseDate">تاريخ الدفع</label>
                <input type="date" id="expenseDate" name="date" required>
            </div>
            
            <div class="form-group">
                <label for="expensePayee">اسم المستفيد</label>
                <input type="text" id="expensePayee" name="payee" placeholder="اسم الشخص أو الشركة" required>
            </div>
            
            <div class="form-group">
                <label for="expensePhone">رقم الجوال</label>
                <input type="tel" id="expensePhone" name="phone" placeholder="0599123456">
            </div>
            
            <div class="form-group">
                <label for="expenseJob">العمل/المهنة</label>
                <input type="text" id="expenseJob" name="job" placeholder="مثال: مقاول، مهندس">
            </div>
            
            <div class="form-group">
                <label for="expenseIdNumber">رقم الهوية</label>
                <input type="text" id="expenseIdNumber" name="id_number" placeholder="رقم الهوية الشخصية">
            </div>
            
            <div class="form-group">
                <label for="expenseProject">المشروع</label>
                <select id="expenseProject" name="project_id" required>
                    <option value="">-- اختر المشروع --</option>
                    <option value="0">مصروف عام</option>
                </select>
            </div>
            
       
            <div class="form-group">
                <label for="expenseAmount">المبلغ</label>
                <input type="number" id="expenseAmount" name="amount" min="0" step="0.01" placeholder="0.00" required>
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
                <label for="paymentMethod">طريقة الدفع</label>
                <select id="paymentMethod" name="payment_method" required>
                    <option value="">-- اختر طريقة الدفع --</option>
                    <option value="نقداً">نقداً</option>
                    <option value="تحويل بنكي">تحويل بنكي</option>
                    <option value="شيك">شيك</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="paymentSource">مصدر الدفع</label>
                <select id="paymentSource" name="payment_source" required>
                    <option value="">-- اختر المصدر --</option>
                    <option value="خزينة">من الخزينة</option>
                    <option value="بنك">من حساب بنكي</option>
                </select>
            </div>

            <!-- قسم تفاصيل الدفع النقدي -->
            <div id="cashDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-money-bill-wave"></i> تفاصيل الدفع النقدي</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="cashReceiver">من استلم المبلغ</label>
                        <select id="cashReceiver" name="cash_receiver">
                            <option value="">-- اختر المستلم --</option>
                            <option value="محمد">محمد</option>
                            <option value="خالد">خالد</option>
                            <option value="أخرى">أخرى (حدد)</option>
                        </select>
                    </div>
                    
                    <div class="form-group hidden" id="otherReceiverGroup">
                        <label for="otherReceiver">اسم المستلم (أخرى)</label>
                        <input type="text" id="otherReceiver" name="cash_receiver_other" placeholder="اكتب اسم المستلم">
                    </div>
                    
                    <div class="form-group">
                        <label for="receiverJob">وظيفة المستلم</label>
                        <input type="text" id="receiverJob" name="receiver_job" placeholder="مثال: محاسب، مدير">
                    </div>
                </div>
            </div>

            <!-- قسم تفاصيل البنك -->
            <div id="bankDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-university"></i> تفاصيل البنك</h4>
                <div class="form-grid">
                    <!-- البنك المرسل -->
                    <div class="form-group">
                        <label for="senderBank">البنك المرسل</label>
                        <select id="senderBank" name="sender_bank">
                            <option value="">-- اختر البنك المرسل --</option>
                            <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                            <option value="بنك الصفا">بنك الصفا</option>
                            <option value="بنك فلسطين">بنك فلسطين</option>
                            <option value="البنك العربي">البنك العربي</option>
                            <option value="other">أخرى (حدد)</option>
                        </select>
                    </div>
                    
                    <div class="form-group hidden" id="otherSenderBankGroup">
                        <label for="otherSenderBank">اسم البنك المرسل (أخرى)</label>
                        <input type="text" id="otherSenderBank" name="other_sender_bank" placeholder="اكتب اسم البنك">
                    </div>
                    
                    <div class="form-group hidden" id="senderBranchGroup">
                        <label for="senderBranch">فرع البنك المرسل</label>
                        <input type="text" id="senderBranch" name="sender_branch" placeholder="اكتب اسم الفرع">
                    </div>
                    
                    <!-- البنك المستقبل -->
                    <div class="form-group">
                        <label for="receiverBank">البنك المستقبل</label>
                        <select id="receiverBank" name="receiver_bank">
                            <option value="">-- اختر البنك المستقبل --</option>
                            <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                            <option value="بنك الصفا">بنك الصفا</option>
                            <option value="بنك فلسطين">بنك فلسطين</option>
                            <option value="البنك العربي">البنك العربي</option>
                            <option value="other">أخرى (حدد)</option>
                        </select>
                    </div>
                    
                    <div class="form-group hidden" id="otherReceiverBankGroup">
                        <label for="otherReceiverBank">اسم البنك المستقبل (أخرى)</label>
                        <input type="text" id="otherReceiverBank" name="other_receiver_bank" placeholder="اكتب اسم البنك">
                    </div>
                    
                    <div class="form-group hidden" id="receiverBranchGroup">
                        <label for="receiverBranch">فرع البنك المستقبل</label>
                        <input type="text" id="receiverBranch" name="receiver_branch" placeholder="اكتب اسم الفرع">
                    </div>
                    
                    <div class="form-group">
                        <label for="transactionId">رقم التحويلة</label>
                        <input type="text" id="transactionId" name="transaction_id" placeholder="أدخل رقم التحويلة">
                    </div>
                </div>
            </div>

            <!-- قسم تفاصيل الشيك -->
            <div id="checkDetailsSection" class="dynamic-section hidden">
                <h4><i class="fas fa-money-check"></i> تفاصيل الشيك</h4>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="checkNumber">رقم الشيك</label>
                        <input type="text" id="checkNumber" name="check_number" placeholder="رقم أو اسم الشيك">
                    </div>
                    
                    <div class="form-group">
                        <label for="checkOwner">اسم صاحب الشيك</label>
                        <input type="text" id="checkOwner" name="check_owner" placeholder="اسم صاحب الشيك">
                    </div>
                    
                    <div class="form-group">
                        <label for="checkHolder">مالك الشيك</label>
                        <input type="text" id="checkHolder" name="check_holder" placeholder="اسم مالك الشيك">
                    </div>
                    
                    <div class="form-group">
                        <label for="checkDueDate">تاريخ الاستحقاق</label>
                        <input type="date" id="checkDueDate" name="check_due_date">
                    </div>
                    
                    <div class="form-group">
                        <label for="checkReceiveDate">تاريخ الاستلام</label>
                        <input type="date" id="checkReceiveDate" name="check_receive_date">
                    </div>
                </div>
            </div>

            <!-- ملاحظات -->
            <div class="form-group" style="grid-column: 1 / -1;">
                <label for="expenseNotes">ملاحظات</label>
                <textarea id="expenseNotes" name="notes" rows="3" placeholder="أي ملاحظات إضافية..."></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> حفظ المصروف
            </button>
        </form>
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
    
    function formatCurrency(num, currency = 'شيكل') { 
        return new Intl.NumberFormat('ar-SA', { 
            minimumFractionDigits: 0, 
            maximumFractionDigits: 2 
        }).format(num || 0) + ' ' + currency; 
    }

    // دالة عرض المصروفات
    function renderExpenses() {
        const expenses = getDB('expenses').sort((a, b) => new Date(b.date) - new Date(a.date));
        const projects = getDB('projects');
        const tableBody = document.getElementById('expensesTableBody');
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        tableBody.innerHTML = '';

        const filteredExpenses = expenses.filter(exp => {
            const project = projects.find(p => p.id == exp.projectId);
            const projectName = exp.projectId === "0" ? "مصروف عام" : (project ? project.name : "مشروع محذوف");

            const searchString = [
                new Date(exp.date).toLocaleDateString('ar-EG'),
                projectName,
                exp.mainCategory,
                exp.payee,
                exp.amount,
                exp.paymentMethod,
                exp.paymentSource,
                exp.notes
            ].join(' ').toLowerCase();

            return searchString.includes(searchTerm);
        });

        if (filteredExpenses.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center; color: var(--text-muted); padding: 40px;">لا توجد مصروفات مسجلة بعد.</td></tr>';
            return;
        }

        filteredExpenses.forEach(exp => {
            const project = projects.find(p => p.id == exp.projectId);
            const projectName = exp.projectId === "0" ? "مصروف عام" : (project ? project.name : "مشروع محذوف");

            // تحديد تفاصيل الدفع
            let paymentDetails = '';
            if (exp.paymentMethod === 'نقداً') {
                const receiver = exp.cashReceiver === 'أخرى' ? exp.otherReceiver : exp.cashReceiver;
                paymentDetails = `استلم: ${receiver || 'غير محدد'}`;
                if (exp.receiverJob) {
                    paymentDetails += ` (${exp.receiverJob})`;
                }
            } else if (exp.paymentMethod === 'تحويل بنكي') {
                const senderBank = exp.senderBank === 'other' ? exp.otherSenderBank : exp.senderBank;
                const receiverBank = exp.receiverBank === 'other' ? exp.otherReceiverBank : exp.receiverBank;
                paymentDetails = `من: ${senderBank || 'غير محدد'} إلى: ${receiverBank || 'غير محدد'}`;
                if (exp.transactionId) {
                    paymentDetails += ` (${exp.transactionId})`;
                }
            } else if (exp.paymentMethod === 'شيك') {
                paymentDetails = `شيك رقم: ${exp.checkNumber || 'غير محدد'}`;
                if (exp.checkOwner) {
                    paymentDetails += ` - صاحب: ${exp.checkOwner}`;
                }
            }

            // تحديد مصدر الدفع
            let paymentSourceText = exp.paymentSource;
            if (exp.paymentSource === 'بنك' && exp.senderBank) {
                const bankName = exp.senderBank === 'other' ? exp.otherSenderBank : exp.senderBank;
                paymentSourceText = `بنك (${bankName})`;
            }

            const row = `
                <tr>
                    <td>${new Date(exp.date).toLocaleDateString('ar-EG')}</td>
                    <td>${projectName}</td>
                    <td><span class="status-badge status-success">${exp.mainCategory}</span></td>
                    <td>${exp.payee}</td>
                    <td><span class="currency-display">${formatCurrency(exp.amount, exp.currency)}</span></td>
                    <td>${exp.paymentMethod}</td>
                    <td>${paymentSourceText}</td>
                    <td>${paymentDetails}</td>
                    <td>${exp.notes || '-'}</td>
                </tr>
            `;
            tableBody.innerHTML += row;
        });
    }

    // دالة حفظ المصروف
    async function saveExpense(e) {
        e.preventDefault();
        let expenses = getDB('expenses');

        const expenseData = {
            id: Date.now(),
            date: document.getElementById('expenseDate').value,
            projectId: document.getElementById('expenseProject').value,
            payee: document.getElementById('expensePayee').value,
            phone: document.getElementById('expensePhone').value,
            job: document.getElementById('expenseJob').value,
            idNumber: document.getElementById('expenseIdNumber').value,
            mainCategory: document.getElementById('mainCategory').value,
            amount: parseFloat(document.getElementById('expenseAmount').value),
            currency: document.getElementById('currency').value,
            paymentMethod: document.getElementById('paymentMethod').value,
            paymentSource: document.getElementById('paymentSource').value,
            notes: document.getElementById('expenseNotes').value,
        };

        // إضافة تفاصيل الدفع النقدي
        if (expenseData.paymentMethod === 'نقداً') {
            expenseData.cashReceiver = document.getElementById('cashReceiver').value;
            expenseData.otherReceiver = document.getElementById('otherReceiver').value;
            expenseData.receiverJob = document.getElementById('receiverJob').value;
        }

        // إضافة تفاصيل البنك
        if (expenseData.paymentMethod === 'تحويل بنكي' || expenseData.paymentSource === 'بنك') {
            expenseData.senderBank = document.getElementById('senderBank').value;
            expenseData.otherSenderBank = document.getElementById('otherSenderBank').value;
            expenseData.senderBranch = document.getElementById('senderBranch').value;
            expenseData.receiverBank = document.getElementById('receiverBank').value;
            expenseData.otherReceiverBank = document.getElementById('otherReceiverBank').value;
            expenseData.receiverBranch = document.getElementById('receiverBranch').value;
            expenseData.transactionId = document.getElementById('transactionId').value;
        }

        // إضافة تفاصيل الشيك
        if (expenseData.paymentMethod === 'شيك') {
            expenseData.checkNumber = document.getElementById('checkNumber').value;
            expenseData.checkOwner = document.getElementById('checkOwner').value;
            expenseData.checkHolder = document.getElementById('checkHolder').value;
            expenseData.checkDueDate = document.getElementById('checkDueDate').value;
            expenseData.checkReceiveDate = document.getElementById('checkReceiveDate').value;
        }

        expenses.push(expenseData);
        setDB('expenses', expenses);
        
        if (typeof Swal !== 'undefined') {
            Swal.fire('تم', 'تم تسجيل المصروف بنجاح', 'success');
        } else {
            alert('تم تسجيل المصروف بنجاح');
        }
        
        e.target.reset();
        document.getElementById('expenseDate').valueAsDate = new Date();
        hideAllDynamicSections();
        renderExpenses();
    }

    // دالة التصدير إلى Excel
    function exportToExcel() {
        if (typeof XLSX === 'undefined') {
            alert('مكتبة التصدير إلى Excel غير متوفرة');
            return;
        }

        const table = document.getElementById("expensesTable");
        const wb = XLSX.utils.table_to_book(table, {sheet: "سجل المصروفات"});
        const today = new Date().toISOString().split('T')[0];
        XLSX.writeFile(wb, `سجل_المصروفات_${today}.xlsx`);
    }

    // دوال إدارة الحقول الديناميكية
    function hideAllDynamicSections() {
        document.getElementById('cashDetailsSection').classList.add('hidden');
        document.getElementById('bankDetailsSection').classList.add('hidden');
        document.getElementById('checkDetailsSection').classList.add('hidden');
        
        // إخفاء الحقول الفرعية
        document.getElementById('otherReceiverGroup').classList.add('hidden');
        document.getElementById('otherSenderBankGroup').classList.add('hidden');
        document.getElementById('senderBranchGroup').classList.add('hidden');
        document.getElementById('otherReceiverBankGroup').classList.add('hidden');
        document.getElementById('receiverBranchGroup').classList.add('hidden');
    }

    function togglePaymentMethodFields() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        hideAllDynamicSections();

        if (paymentMethod === 'نقداً') {
            document.getElementById('cashDetailsSection').classList.remove('hidden');
        } else if (paymentMethod === 'تحويل بنكي') {
            document.getElementById('bankDetailsSection').classList.remove('hidden');
        } else if (paymentMethod === 'شيك') {
            document.getElementById('checkDetailsSection').classList.remove('hidden');
        }
    }

    function togglePaymentSourceFields() {
        const paymentSource = document.getElementById('paymentSource').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        
        if (paymentSource === 'بنك' && paymentMethod !== 'تحويل بنكي') {
            document.getElementById('bankDetailsSection').classList.remove('hidden');
        }
    }

    function toggleCashReceiverFields() {
        const cashReceiver = document.getElementById('cashReceiver').value;
        const otherReceiverGroup = document.getElementById('otherReceiverGroup');
        
        if (cashReceiver === 'أخرى') {
            otherReceiverGroup.classList.remove('hidden');
        } else {
            otherReceiverGroup.classList.add('hidden');
        }
    }

    function toggleSenderBankFields() {
        const senderBank = document.getElementById('senderBank').value;
        const otherSenderBankGroup = document.getElementById('otherSenderBankGroup');
        const senderBranchGroup = document.getElementById('senderBranchGroup');
        
        if (senderBank === 'other') {
            otherSenderBankGroup.classList.remove('hidden');
        } else {
            otherSenderBankGroup.classList.add('hidden');
        }
        
        if (senderBank && senderBank !== '') {
            senderBranchGroup.classList.remove('hidden');
        } else {
            senderBranchGroup.classList.add('hidden');
        }
    }

    function toggleReceiverBankFields() {
        const receiverBank = document.getElementById('receiverBank').value;
        const otherReceiverBankGroup = document.getElementById('otherReceiverBankGroup');
        const receiverBranchGroup = document.getElementById('receiverBranchGroup');
        
        if (receiverBank === 'other') {
            otherReceiverBankGroup.classList.remove('hidden');
        } else {
            otherReceiverBankGroup.classList.add('hidden');
        }
        
        if (receiverBank && receiverBank !== '') {
            receiverBranchGroup.classList.remove('hidden');
        } else {
            receiverBranchGroup.classList.add('hidden');
        }
    }

    // دالة تحميل المشاريع
    function loadProjects() {
        const projects = getDB('projects');
        const projectSelect = document.getElementById('expenseProject');
        
        // مسح الخيارات الموجودة (عدا الخيار الافتراضي)
        while (projectSelect.children.length > 2) {
            projectSelect.removeChild(projectSelect.lastChild);
        }
        
        // إضافة المشاريع
        projects.forEach(project => {
            const option = document.createElement('option');
            option.value = project.id;
            option.textContent = project.name;
            projectSelect.appendChild(option);
        });
    }

    // التهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // تعيين التاريخ الحالي
        document.getElementById('expenseDate').valueAsDate = new Date();
        
        // تحميل المشاريع
        loadProjects();
        
        // عرض المصروفات
        renderExpenses();
        
        // إضافة مستمعي الأحداث للنموذج
        document.getElementById('expenseForm').addEventListener('submit', saveExpense);
        
        // إضافة مستمعي الأحداث للحقول الديناميكية
        document.getElementById('paymentMethod').addEventListener('change', togglePaymentMethodFields);
        document.getElementById('paymentSource').addEventListener('change', togglePaymentSourceFields);
        document.getElementById('cashReceiver').addEventListener('change', toggleCashReceiverFields);
        document.getElementById('senderBank').addEventListener('change', toggleSenderBankFields);
        document.getElementById('receiverBank').addEventListener('change', toggleReceiverBankFields);
        
        // إضافة مستمع للبحث
        document.getElementById('searchInput').addEventListener('input', renderExpenses);
    });
</script>
@endsection
