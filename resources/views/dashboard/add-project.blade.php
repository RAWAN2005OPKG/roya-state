@extends('layouts.container')
@section('title', 'إضافة مشروع جديد')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', 'Arial', sans-serif;
    }

    .background {
        background: linear-gradient(135deg, #efeff0 0%, #cdcacf 100%);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        direction: rtl;
        padding: 40px 20px;
        overflow-y: auto;
    }

    .form-container {
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 1100px;
        border: 1px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }



    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f8f9fa;
        position: relative;
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }



    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .header-text h1 {
        font-size: 1.8rem;
        background: linear-gradient(135deg, #cecfd4, #9e9ca0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        font-weight: 700;
    }

    .header-text p {
        font-size: 1rem;
        color: #000000;
        margin: 0;
    }

    .form-section {
        margin-bottom: 35px;
        background: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }

    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0;
        background: linear-gradient(135deg, #ffffff, #cac7ce);
        color: white;
        font-size: 1.2rem;
        padding: 20px 25px;
        position: relative;
        overflow: hidden;
    }

    .section-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .section-header:hover::before {
        left: 100%;
    }

    .section-header i {
        margin-left: 5px;
        font-size: 1.3rem;
    }

    .section-header h3 {
        margin: 0;
        font-weight: 600;
        color: white;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        padding: 25px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-group:focus-within label {
        color: #c3c5cf;
        transform: translateY(-2px);
    }

    .form-group label.required::after {
        content: '*';
        color: #dc3545;
        margin-right: 5px;
        font-weight: bold;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px 16px;
        background-color: #ffffff;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        color: #495057;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #cacee2;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    input:hover, select:hover, textarea:hover {
        border-color: #bcb6c2;
    }

    .input-with-currency {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-currency input {
        padding-left: 60px;
    }

    .input-with-currency .currency {
        position: absolute;
        left: 16px;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .form-group:focus-within .currency {
        background: #667eea;
        color: white;
    }

    /* تصحيح CSS للأقسام الديناميكية */
    .dynamic-section {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 25px;
        border-radius: 15px;
        margin-top: 20px;
        border: 2px dashed #dee2e6;
        position: relative;
        overflow: hidden;
        grid-column: 1 / -1;
    }

    .dynamic-section.hidden {
        display: none;
    }

    .dynamic-section.show {
        display: block;
        animation: slideDown 0.4s ease;
    }


    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dynamic-section h4 {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 20px;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .file-upload-area {
        border: 3px dashed #dee2e6;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        position: relative;
        overflow: hidden;
    }



    .file-upload-area::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .file-upload-area:hover::before {
        left: 100%;
    }

    .upload-content i {
        font-size: 3rem;
        background: linear-gradient(135deg, #fcfcfc, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 16px;
        display: block;
    }

    .upload-content p {
        font-size: 1.1rem;
        color: #495057;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .file-types {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .media-preview {
        margin-top: 20px;
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .media-preview img,
    .media-preview video {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 15px;
    }

    .remove-media {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .remove-media:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f8f9fa;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
        min-width: 140px;
        justify-content: center;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-info {
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .hidden {
        display: none !important;
    }

    /* تحسينات الاستجابة */
    @media (max-width: 768px) {
        .background {
            padding: 10px;
        }

        .form-container {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .header-text h1 {
            font-size: 1.5rem;
        }
    }

    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: left 12px center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-left: 40px;
        appearance: none;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }
        .btn.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn.loading::after {
        content: '';
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endsection

@section('content')
<div class="background">
    <div class="form-container">
        <div class="form-header">
            <div class="header-content">
                <div class="header-icon"><i class="fas fa-book"></i></div>
                <div class="header-text">
                    <h1>السجل الكامل للمشروع</h1>
                    <p>لتوثيق كافة تفاصيل المشروع</p>
                </div>
            </div>
        </div>

        <form id="addProjectForm" action="{{ route('dashboard.projects.index') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 1. المعلومات الأساسية -->
            <div class="form-section">
                <div class="section-header"><i class="fas fa-info-circle"></i><h3>المعلومات الأساسية</h3></div>
                <div class="form-grid">
                    <div class="form-group"><label>تاريخ الاستحقاق</label><input type="date" id="dueDate" name="due_date" required></div>
                    <div class="form-group"><label for="owner_name" class="required">اسم مالك المشروع</label><input type="text" id="owner_name" name="owner_name" placeholder="مثال: أحمد علي" required></div>
                    <div class="form-group"><label for="owner_phone" class="required">رقم الجوال</label><input type="text" id="owner_phone" name="owner_phone" placeholder="مثال: 0599123456" required></div>
                    <div class="form-group"><label for="owner_id" class="required">رقم الهوية</label><input type="text" id="owner_id" name="owner_id" placeholder="مثال: 412345678" required></div>
                    <div class="form-group"><label for="name" class="required">اسم المشروع</label><input type="text" id="name" name="project_name" placeholder="أدخل اسم المشروع" required></div>
                    <div class="form-group"><label for="project_title" class="required">عنوان المشروع</label><input type="text" id="project_title" name="project_title" placeholder="مثال: القدس" required></div>
                    <div class="form-group"><label for="currency" class="required">نوع العملة</label><select id="currency" name="currency" required><option value="">اختر العملة</option><option value="ils"> شيكل</option><option value="jod">دينار</option><option value="usd">دولار</option></select></div>
                    <div class="form-group"><label for="apartment_price" class="required">سعر الشقة</label><input type="text" id="apartment_price" name="apartment_price" placeholder="مثال: 1500000" required></div>

                    <div class="form-group">
                        <label for="down_payment" class="required">الدفعة الأولى اللازمة للشقة</label>
                        <select id="down_payment" name="down_payment" required>
                            <option value="">اختر مبلغ الدفعة الأولى</option>
                            <option value="100000">100,000  </option>
                            <option value="150000">150,000  </option>
                            <option value="200000">200,000  </option>
                            <option value="250000">250,000  </option>
                            <option value="300000">300,000  </option>
                            <option value="350000">350,000  </option>
                            <option value="400000">400,000  </option>
                            <option value="450000">450,000  </option>
                            <option value="500000">500,000  </option>
                            <option value="550000">550,000  </option>
                            <option value="600000">600,000  </option>
                            <option value="650000">650,000  </option>
                            <option value="700000">700,000  </option>
                            <option value="750000">750,000  </option>
                            <option value="800000">800,000  </option>
                            <option value="850000">850,000  </option>
                            <option value="900000">900,000  </option>
                            <option value="950000">950,000  </option>
                            <option value="1000000">1,000,000  </option>
                            <option value="1050000">1,050,000  </option>
                            <option value="1100000">1,100,000  </option>
                            <option value="1150000">1,150,000  </option>
                            <option value="1200000">1,200,000  </option>
                            <option value="1250000">1,250,000  </option>
                            <option value="1300000">1,300,000  </option>
                            <option value="1350000">1,350,000  </option>
                            <option value="1400000">1,400,000  </option>
                            <option value="1450000">1,450,000  </option>
                            <option value="1500000">1,500,000  </option>
                            <option value="1550000">1,550,000  </option>
                            <option value="1600000">1,600,000  </option>
                            <option value="1650000">1,650,000  </option>
                            <option value="1700000">1,700,000  </option>
                            <option value="1750000">1,750,000  </option>
                            <option value="1800000">1,800,000  </option>
                            <option value="1850000">1,850,000  </option>
                            <option value="1900000">1,900,000  </option>
                            <option value="1950000">1,950,000  </option>
                            <option value="2000000">2,000,000  </option>
                        </select>
                    </div>

                    <div class="form-group"><label for="project_status" class="required">حالة المشروع الحالية</label><select id="project_status" name="project_status" required><option value="">اختر الحالة</option><option value="on_plan">مشروع على مخطط</option><option value="licensing">قيد الترخيص</option><option value="excavation">قيد الحفر</option><option value="under_construction">قيد الإنشاء</option><option value="ready_structure">مشروع جاهز عظم</option><option value="ready_finished">مشروع جاهز تشطيب</option></select></div>
                    <div class="form-group"><label for="paymentMethod">طريقة الدفع</label><select id="paymentMethod" name="payment_method" required><option value="">-- اختر طريقة الدفع --</option><option value="نقداً">نقداً</option><option value="تحويل بنكي">تحويل بنكي</option><option value="شيك">شيك</option></select></div>

                    <!-- قسم الدفع النقدي -->
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
                                <input type="text" id="receiverJob" name="cash_receiver_job" placeholder="مثال: محاسب، مدير">
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
                                <input type="text" id="otherSenderBank" name="sender_bank_other" placeholder="اكتب اسم البنك">
                            </div>

                            <div class="form-group">
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
                                <input type="text" id="otherReceiverBank" name="receiver_bank_other" placeholder="اكتب اسم البنك">
                            </div>

                            <div class="form-group">
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
                </div>
            </div>

            <!-- 2. صورة أو فيديو المشروع -->
            <div class="form-section">
                <div class="section-header"><i class="fas fa-photo-video"></i><h3>صورة أو فيديو المشروع</h3></div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <div class="file-upload-area" id="mediaUploadArea"><div class="upload-content"><i class="fas fa-cloud-upload-alt"></i><p>اسحب الصورة/الفيديو هنا أو انقر للاختيار</p><span class="file-types">JPG, PNG, MP4 (حد أقصى 20MB)</span></div><input type="file" id="projectMedia" name="project_media" accept="image/*,video/*" class="hidden"></div>
                        <div class="media-preview" id="mediaPreview" style="display: none;"><img id="previewImg" src="" alt="معاينة الصورة" style="display: none;"><video id="previewVideo" src="" controls style="display: none;"></video><button type="button" class="remove-media" id="removeMediaBtn"><i class="fas fa-times"></i></button></div>
                    </div>
                </div>
            </div>

            <!-- 4. التكاليف التقديرية -->
            <div class="form-section">
                <div class="section-header"><i class="fas fa-calculator"></i><h3>التكاليف التقديرية للمشروع</h3></div>
                <div class="form-grid">
                    <div class="form-group"><label for="land_cost">تكلفة الأرض</label><div class="input-with-currency"><input type="number" class="cost-input" id="land_cost" name="land_cost" placeholder="0"><span class="currency"> </span></div></div>
                    <div class="form-group"><label for="excavation_cost">تكلفة الحفر</label><div class="input-with-currency"><input type="number" class="cost-input" id="excavation_cost" name="excavation_cost" placeholder="0"><span class="currency"> </span></div></div>
                    <div class="form-group"><label for="engineers_cost">تكلفة المهندسين</label><div class="input-with-currency"><input type="number" class="cost-input" id="engineers_cost" name="engineers_cost" placeholder="0"><span class="currency"> </span></div></div>
                    <div class="form-group"><label for="licensing_cost">تكاليف التراخيص</label><div class="input-with-currency"><input type="number" class="cost-input" id="licensing_cost" name="licensing_cost" placeholder="0"><span class="currency"> </span></div></div>
                    <div class="form-group"><label for="materials_cost">تكاليف المواد الخام</label><div class="input-with-currency"><input type="number" class="cost-input" id="materials_cost" name="materials_cost" placeholder="0"><span class="currency"> </span></div></div>
                    <div class="form-group"><label for="finishing_cost">تكاليف التشطيبات</label><div class="input-with-currency"><input type="number" class="cost-input" id="finishing_cost" name="finishing_cost" placeholder="0"><span class="currency"> </span></div></div>
                    <div class="form-group full-width"><label for="total_budget">إجمالي التكاليف التقديرية</label><div class="input-with-currency"><input type="text" id="total_budget" name="total_budget" readonly><span class="currency"> </span></div></div>
                </div>
            </div>

            <!-- أزرار الإجراءات -->
            <div class="form-actions">
                <button type="button" class="btn btn-success" onclick="exportData()"><i class="fas fa-download"></i> تصدير</button>
                <button type="button" class="btn btn-info" onclick="printForm()"><i class="fas fa-print"></i> طباعة</button>
                <button type="button" class="btn btn-secondary" onclick="resetForm()"><i class="fas fa-undo"></i> إعادة تعيين</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ سجل المشروع</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log('jQuery loaded and ready'); // للتأكد من تحميل jQuery

    // إظهار/إخفاء أقسام الدفع الرئيسية
    $('#paymentMethod').on('change', function() {
        const selectedMethod = $(this).val();
        console.log('Payment method selected:', selectedMethod); // للتتبع

        // إخفاء جميع الأقسام أولاً
        $('.dynamic-section').removeClass('show').addClass('hidden');

        // إظهار القسم المناسب
        if (selectedMethod === 'نقداً') {
            console.log('Showing cash section');
            $('#cashDetailsSection').removeClass('hidden').addClass('show');
        } else if (selectedMethod === 'تحويل بنكي') {
            console.log('Showing bank section');
            $('#bankDetailsSection').removeClass('hidden').addClass('show');
        } else if (selectedMethod === 'شيك') {
            console.log('Showing check section');
            $('#checkDetailsSection').removeClass('hidden').addClass('show');
        }
    });

    // إظهار حقل "أخرى" للمستلم النقدي
    $('#cashReceiver').on('change', function() {
        const isOther = $(this).val() === 'أخرى';
        $('#otherReceiverGroup').toggleClass('hidden', !isOther);
    });

    // إظهار حقل "أخرى" للبنك المرسل
    $('#senderBank').on('change', function() {
        const isOther = $(this).val() === 'other';
        $('#otherSenderBankGroup').toggleClass('hidden', !isOther);
    });

    // إظهار حقل "أخرى" للبنك المستقبل
    $('#receiverBank').on('change', function() {
        const isOther = $(this).val() === 'other';
        $('#otherReceiverBankGroup').toggleClass('hidden', !isOther);
    });

    // حساب التكاليف الإجمالية تلقائيًا
    $('.cost-input').on('input', function() {
        let total = 0;
        $('.cost-input').each(function() {
            const value = parseFloat($(this).val());
            if (!isNaN(value)) {
                total += value;
            }
        });
        $('#total_budget').val(total.toLocaleString());
    });

    // تفعيل منطقة رفع الملفات
    $('#mediaUploadArea').on('click', function() {
        $('#projectMedia').click();
    });

    // معاينة الصورة/الفيديو عند الاختيار
    $('#projectMedia').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'video/mp4'];
            if (!allowedTypes.includes(file.type)) {
                alert('نوع الملف غير مدعوم. يرجى اختيار صورة (JPG, PNG) أو فيديو (MP4)');
                return;
            }

            if (file.size > 20 * 1024 * 1024) {
                alert('حجم الملف كبير جداً. الحد الأقصى 20MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const result = e.target.result;

                if (file.type.startsWith('image/')) {
                    $('#previewImg').attr('src', result).show();
                    $('#previewVideo').hide();
                } else if (file.type.startsWith('video/')) {
                    $('#previewVideo').attr('src', result).show();
                    $('#previewImg').hide();
                }

                $('#mediaPreview').fadeIn(300);
                $('#mediaUploadArea').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    // إزالة الملف المحدد
    $('#removeMediaBtn').on('click', function() {
        $('#projectMedia').val('');
        $('#mediaPreview').fadeOut(300, function() {
            $('#previewImg, #previewVideo').hide().attr('src', '');
            $('#mediaUploadArea').fadeIn(300);
        });
    });

    // تحسين تجربة المستخدم للنماذج
    $('input, select, textarea').on('focus', function() {
        $(this).closest('.form-group').addClass('focused');
    });

    $('input, select, textarea').on('blur', function() {
        $(this).closest('.form-group').removeClass('focused');
    });


    // تنسيق أرقام الأسعار أثناء الكتابة
    $('#apartment_price').on('input', function() {
        let value = $(this).val().replace(/,/g, '');
        if (!isNaN(value) && value !== '') {
            $(this).val(parseInt(value).toLocaleString('ar-EG'));
        }
    });

    // دعم السحب والإفلات للملفات
    $('#mediaUploadArea').on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('drag-over');
    });

    $('#mediaUploadArea').on('dragleave dragend', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
    });

    $('#mediaUploadArea').on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');

        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            $('#projectMedia')[0].files = files;
            $('#projectMedia').trigger('change');
        }
    });
});

// دوال الإجراءات
function exportData() {
    const btn = event.target.closest('.btn');
    btn.classList.add('loading');

    setTimeout(() => {
        alert('تم تصدير البيانات بنجاح!');
        btn.classList.remove('loading');
    }, 1500);
}

function printForm() {
    window.print();
}

function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
        document.getElementById('addProjectForm').reset();
        $('#mediaPreview').hide();
        $('#mediaUploadArea').show();
        $('.dynamic-section').addClass('hidden').removeClass('show');
        $('#total_budget').val('');
    }
}
</script>
@endpush
