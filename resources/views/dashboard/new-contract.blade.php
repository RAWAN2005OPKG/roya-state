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
            --border-color: #dee2e6;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Cairo', sans-serif; 
        }
        
        body { 
            background-color: var(--dark-bg-1); 
            color: var(--text-color); 
        }
        
        .main-content { 
            max-width: 1100px; 
            margin: 40px auto; 
            padding: 20px; 
        }
        
        .page-header { 
            text-align: center; 
            margin-bottom: 40px; 
        }
        
        .page-header h1 { 
            font-size: 2.5rem; 
            color: var(--text-color); 
            margin-bottom: 10px;
        }
        
        .page-header p { 
            color: var(--text-muted); 
            font-size: 1.1rem; 
        }
        
        .form-container { 
            background-color: var(--dark-bg-2); 
            padding: 40px; 
            border-radius: 16px; 
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .form-section { 
            margin-bottom: 40px; 
        }
        
        .section-title { 
            font-size: 1.5rem; 
            color: var(--primary-color); 
            margin-bottom: 25px; 
            padding-bottom: 10px; 
            border-bottom: 2px solid var(--primary-color); 
            display: inline-block; 
        }
        
        .form-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 25px; 
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column; 
        }
        
        .form-group label { 
            margin-bottom: 10px; 
            font-weight: 600; 
            color: var(--text-color); 
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; 
            padding: 12px; 
            background-color: var(--dark-bg-1);
            border: 1px solid var(--border-color); 
            border-radius: 8px;
            color: var(--text-color); 
            font-size: 1rem; 
            transition: all 0.3s;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; 
            border-color: var(--primary-color); 
            box-shadow: 0 0 0 3px rgba(0, 170, 255, 0.1);
        }
        
        .form-actions { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-top: 30px; 
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn-submit { 
            background: linear-gradient(45deg, var(--primary-color), #007bff); 
            color: #fff; 
            padding: 15px 35px; 
            border: none; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 1.1rem; 
            cursor: pointer; 
            transition: all 0.3s; 
            box-shadow: 0 4px 15px rgba(0, 170, 255, 0.2); 
        }
        
        .btn-submit:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(0, 170, 255, 0.3); 
        }
        
        .btn-secondary { 
            background-color: var(--border-color); 
            color: var(--text-color); 
            padding: 15px 35px; 
            border-radius: 8px; 
            text-decoration: none; 
            font-weight: 600; 
            transition: all 0.3s; 
            border: 1px solid var(--border-color);
        }
        
        .btn-secondary:hover { 
            background-color: #e9ecef; 
            text-decoration: none;
            color: var(--text-color);
        }

        /* الأقسام الديناميكية المحسنة */
        .dynamic-section {
            display: none;
            margin-top: 25px;
            padding: 25px;
            background-color: var(--dark-bg-1);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--primary-color);
        }
        
        .dynamic-section.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .section-subtitle {
            font-size: 12px;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .required {
            color: var(--danger-color);
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
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
            .main-content {
                margin: 20px auto;
                padding: 15px;
            }
            
            .form-container {
                padding: 25px;
            }
            
            .page-header h1 {
                font-size: 2rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-submit, .btn-secondary {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')

    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-file-signature"></i> إنشاء عقد استثماري للعقارات</h1>
            <p>أدخل كافة التفاصيل لتوثيق عقد استثماري جديد بشكل كامل ودقيق.</p>
        </div>

        <div class="form-container">
            <form id="contractForm">
                <!-- بيانات العقد -->
                <div class="form-section">
                    <h2 class="section-title">بيانات العقد</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contractId">رقم العقد <span class="required">*</span></label>
                            <input type="text" id="contractId" required>
                        </div>
                        <div class="form-group">
                            <label for="signingDate">تاريخ توقيع العقد <span class="required">*</span></label>
                            <input type="date" id="signingDate" required>
                        </div>
                        <div class="form-group">
                            <label for="status">حالة العقد</label>
                            <select id="status">
                                <option value="active">نشط</option>
                                <option value="draft">مسودة</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- بيانات المستثمر -->
                <div class="form-section">
                    <h2 class="section-title">بيانات المستثمر</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="clientName">الاسم الكامل <span class="required">*</span></label>
                            <input type="text" id="clientName" required>
                        </div>
                        <div class="form-group">
                            <label for="clientEmail">البريد الإلكتروني <span class="required">*</span></label>
                            <input type="email" id="clientEmail" required>
                        </div>
                        <div class="form-group">
                            <label for="clientPhone">رقم الجوال <span class="required">*</span></label>
                            <input type="tel" id="clientPhone" required>
                        </div>
                        <div class="form-group">
                            <label for="clientAltPhone">رقم جوال بديل (اختياري)</label>
                            <input type="tel" id="clientAltPhone">
                        </div>
                        <div class="form-group">
                            <label for="clientIdNumber">رقم الهوية <span class="required">*</span></label>
                            <input type="text" id="clientIdNumber" required>
                        </div>
                    </div>
                </div>

                <!-- بيانات العقار -->
                <div class="form-section">
                    <h2 class="section-title">بيانات العقار</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="propertyType">نوع العقار</label>
                            <input type="text" id="propertyType" placeholder="مثال: شقة سكنية">
                        </div>
                        <div class="form-group">
                            <label for="propertyLocation">موقع العقار</label>
                            <input type="text" id="propertyLocation" placeholder="المدينة، الحي">
                        </div>
                    </div>
                </div>

                <!-- التفاصيل المالية -->
                <div class="form-section">
                    <h2 class="section-title">التفاصيل المالية</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="investmentAmount">مبلغ الشراء  <span class="required">*</span></label>
                            <input type="number" id="investmentAmount" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="duration">مدة الدفع (بالأشهر) <span class="required">*</span></label>
                            <input type="number" id="duration" min="1" required>
                        </div>
                       
                        <div class="form-group">
                            <label for="paymentMethod">طريقة الدفع <span class="required">*</span></label>
                            <select id="paymentMethod" required>
                                <option value="">اختر طريقة الدفع</option>
                                <option value="cash">نقدي</option>
                                <option value="bank_transaction">معاملة بنكية</option>
                                <option value="check">شيك</option>
                            </select>
                        </div>
                    </div>
                                     
                  
                    <div class="form-group"><label for="apartment_price" class="required">سعر الشقة </label><input type="text" id="apartment_price" name="apartment_price" placeholder="مثال: 1500000" required></div>
                    <div class="form-group">
                            <label for="signingDate">تاريخ الدفعة الأولى  <span class="required">*</span></label>
                            <input type="date" id="signingDate" required>
                        </div>  
                    <div class="form-group">
                        <label for="down_payment_initial" class="required">الدفعة الاولى اللازمة </label>
                        <select id="down_payment_initial" name="down_payment_initial" required>
                            <option value="">اختر الحالة</option>
                            <option value="100000">100.000</option>
                            <option value="150000">150.000</option>
                            <option value="200000">200.000</option>
                            <option value="250000"> 250.000</option>
                            <option value="300000"> 300.000</option>
                            <option value="350000"> 350.000</option>
                            <option value="400000">400.000</option>
                            <option value="450000">450.000</option>
                            <option value="500000">500.000</option>
                            <option value="550000"> 550.000</option>
                            <option value="600000"> 600.000</option>
                            <option value="650000"> 650.000</option>
                            <option value="700000">700.000</option>
                            <option value="750000">750.000</option>
                            <option value="800000">800.000</option>
                            <option value="850000"> 850.000</option>
                            <option value="900000"> 900.000</option>
                            <option value="1000000"> 1.000.000</option>
                            <option value="1100000"> 1.100.000</option>
                            <option value="1200000"> 1.200.000</option>
                            <option value="1300000"> 1.300.000</option>
                            <option value="1400000"> 1.400.000</option>
                            <option value="1500000"> 1.500.000</option>
                            <option value="1600000"> 1.600.000</option>
                            <option value="1700000"> 1.700.000</option>
                            <option value="1800000"> 1.800.000</option>
                            <option value="1900000"> 1.900.000</option>
                            <option value="2000000"> 2.000.000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="down_payment_other">   الدفعات الاخرى</label>
                        <select id="down_payment_other" name="down_payment_other" >
                            <option value="">اختر الحالة</option>
                            <option value="100000">100.000</option>
                            <option value="150000">150.000</option>
                            <option value="200000">200.000</option>
                            <option value="250000"> 250.000</option>
                            <option value="300000"> 300.000</option>
                            <option value="350000"> 350.000</option>
                            <option value="400000">400.000</option>
                            <option value="450000">450.000</option>
                            <option value="500000">500.000</option>
                            <option value="550000"> 550.000</option>
                            <option value="600000"> 600.000</option>
                            <option value="650000"> 650.000</option>
                            <option value="700000">700.000</option>
                            <option value="750000">750.000</option>
                            <option value="800000">800.000</option>
                            <option value="850000"> 850.000</option>
                            <option value="900000"> 900.000</option>
                            <option value="1000000"> 1.000.000</option>
                            <option value="1100000"> 1.100.000</option>
                            <option value="1200000"> 1.200.000</option>
                            <option value="1300000"> 1.300.000</option>
                            <option value="1400000"> 1.400.000</option>
                            <option value="1500000"> 1.500.000</option>
                            <option value="1600000"> 1.600.000</option>
                            <option value="1700000"> 1.700.000</option>
                            <option value="1800000"> 1.800.000</option>
                            <option value="1900000"> 1.900.000</option>
                            <option value="2000000"> 2.000.000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="profitPercentage">نسبة الأرباح المتوقعة (%) <span class="required">*</span></label>
                        <input type="number" id="profitPercentage" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="remaining_amount">المبلغ المتبقي</label>
                        <input type="number" id="remaining_amount" name="remaining_amount" readonly>
                    </div>
                </div>

                <!-- أقسام تفاصيل الدفع الديناميكية -->
                <div id="cashDetailsSection" class="dynamic-section">
                    <h3 class="section-subtitle">تفاصيل الدفع النقدي</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="cashReceiver">المستلم <span class="required">*</span></label>
                            <select id="cashReceiver">
                                <option value="">-- اختر المستلم --</option>
                                <option value="موظف 1">موظف 1</option>
                                <option value="موظف 2">موظف 2</option>
                                <option value="other">أخرى (حدد)</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherReceiverGroup" style="display: none;">
                            <label for="otherReceiver">اسم المستلم <span class="required">*</span></label>
                            <input type="text" id="otherReceiver" placeholder="اكتب اسم المستلم">
                        </div>
                        <div class="form-group">
                            <label for="receiverJob">وظيفة المستلم <span class="required">*</span></label>
                            <input type="text" id="receiverJob" placeholder="مثال: مدير مبيعات">
                        </div>
                        <div class="form-group">
                            <label for="cashReceiptDate">تاريخ الاستلام <span class="required">*</span></label>
                            <input type="date" id="cashReceiptDate">
                        </div>
                    </div>
                </div>

                <div id="bankDetailsSection" class="dynamic-section">
                    <h3 class="section-subtitle">تفاصيل المعاملة البنكية</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="senderBank">البنك المرسل <span class="required">*</span></label>
                            <select id="senderBank">
                                <option value="">-- اختر البنك --</option>
                                <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                <option value="بنك الصفا">بنك الصفا</option>
                                <option value="بنك فلسطين">بنك فلسطين</option>
                                <option value="البنك العربي">البنك العربي</option>
                                <option value="other">أخرى (حدد)</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherSenderBankGroup" style="display: none;">
                            <label for="otherSenderBank">اسم البنك المرسل <span class="required">*</span></label>
                            <input type="text" id="otherSenderBank" placeholder="اكتب اسم البنك المرسل">
                        </div>
                        <div class="form-group">
                            <label for="senderBankBranch">فرع البنك المرسل <span class="required">*</span></label>
                            <input type="text" id="senderBankBranch" placeholder="اكتب اسم الفرع">
                        </div>
                        <div class="form-group">
                            <label for="receiverBank">البنك المستلم <span class="required">*</span></label>
                            <select id="receiverBank">
                                <option value="">-- اختر البنك --</option>
                                <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                <option value="بنك الصفا">بنك الصفا</option>
                                <option value="بنك فلسطين">بنك فلسطين</option>
                                <option value="البنك العربي">البنك العربي</option>
                                <option value="other">أخرى (حدد)</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherReceiverBankGroup" style="display: none;">
                            <label for="otherReceiverBank">اسم البنك المستلم <span class="required">*</span></label>
                            <input type="text" id="otherReceiverBank" placeholder="اكتب اسم البنك المستلم">
                        </div>
                        <div class="form-group">
                            <label for="receiverBankBranch">فرع البنك المستلم <span class="required">*</span></label>
                            <input type="text" id="receiverBankBranch" placeholder="اكتب اسم الفرع">
                        </div>
                        <div class="form-group">
                            <label for="transactionReference">الرقم المرجعي للمعاملة (اختياري)</label>
                            <input type="text" id="transactionReference" placeholder="مثال: TRN123456">
                        </div>
                        <div class="form-group">
                            <label for="transactionDate">تاريخ المعاملة <span class="required">*</span></label>
                            <input type="date" id="transactionDate">
                        </div>
                    </div>
                </div>

                <div id="checkDetailsSection" class="dynamic-section">
                    <h3 class="section-subtitle">تفاصيل الشيك</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="checkNumber">رقم الشيك <span class="required">*</span></label>
                            <input type="text" id="checkNumber" placeholder="اكتب رقم الشيك">
                        </div>
                        <div class="form-group">
                            <label for="checkOwner">اسم صاحب الشيك <span class="required">*</span></label>
                            <input type="text" id="checkOwner" placeholder="اسم صاحب الشيك">
                        </div>
                        <div class="form-group">
                            <label for="checkHolder">مالك الشيك <span class="required">*</span></label>
                            <input type="text" id="checkHolder" placeholder="اسم مالك الشيك">
                        </div>
                        <div class="form-group">
                            <label for="checkBank">البنك المسحوب عليه <span class="required">*</span></label>
                            <select id="checkBank">
                                <option value="">-- اختر البنك --</option>
                                <option value="بنك القاهرة عمان">بنك القاهرة عمان</option>
                                <option value="بنك الصفا">بنك الصفا</option>
                                <option value="بنك فلسطين">بنك فلسطين</option>
                                <option value="البنك العربي">البنك العربي</option>
                                <option value="other">أخرى (حدد)</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherCheckBankGroup" style="display: none;">
                            <label for="otherCheckBank">اسم البنك <span class="required">*</span></label>
                            <input type="text" id="otherCheckBank" placeholder="اكتب اسم البنك">
                        </div>
                        <div class="form-group">
                            <label for="checkBankBranch">فرع البنك <span class="required">*</span></label>
                            <input type="text" id="checkBankBranch" placeholder="اكتب اسم الفرع">
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

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ العقد
                    </button>
                    <a href="active-contracts.html" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> العودة لقائمة العقود
                    </a>
                </div>
            </form>
        </div>
    </main>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const DB_KEY = 'investment_contracts_v_final';
    const contractForm = document.getElementById('contractForm');
    const paymentMethodSelect = document.getElementById('paymentMethod');
    
    // العناصر الديناميكية
    const cashDetailsSection = document.getElementById('cashDetailsSection');
    const bankDetailsSection = document.getElementById('bankDetailsSection');
    const checkDetailsSection = document.getElementById('checkDetailsSection');
    
    // عناصر النقدي
    const cashReceiver = document.getElementById('cashReceiver');
    const otherReceiverGroup = document.getElementById('otherReceiverGroup');
    const otherReceiver = document.getElementById('otherReceiver');
    
    // عناصر البنك
    const senderBank = document.getElementById('senderBank');
    const otherSenderBankGroup = document.getElementById('otherSenderBankGroup');
    const otherSenderBank = document.getElementById('otherSenderBank');
    const receiverBank = document.getElementById('receiverBank');
    const otherReceiverBankGroup = document.getElementById('otherReceiverBankGroup');
    const otherReceiverBank = document.getElementById('otherReceiverBank');
    
    // عناصر الشيك
    const checkBank = document.getElementById('checkBank');
    const otherCheckBankGroup = document.getElementById('otherCheckBankGroup');
    const otherCheckBank = document.getElementById('otherCheckBank');

    // تعيين تاريخ اليوم كقيمة افتراضية
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('signingDate').value = today;

    // دالة لإظهار/إخفاء الأقسام بناءً على طريقة الدفع
    function togglePaymentSections() {
        const selectedMethod = paymentMethodSelect.value;
        
        // إخفاء جميع الأقسام أولاً
        cashDetailsSection.classList.remove('show');
        bankDetailsSection.classList.remove('show');
        checkDetailsSection.classList.remove('show');
        
        // استخدام setTimeout للسماح بإزالة الفئة قبل تغيير display
        setTimeout(() => {
            cashDetailsSection.style.display = 'none';
            bankDetailsSection.style.display = 'none';
            checkDetailsSection.style.display = 'none';
            
            // إظهار القسم المناسب
            if (selectedMethod === 'cash') {
                cashDetailsSection.style.display = 'block';
                setTimeout(() => cashDetailsSection.classList.add('show'), 10);
                setRequiredFields('cash');
            } else if (selectedMethod === 'bank_transaction') {
                bankDetailsSection.style.display = 'block';
                setTimeout(() => bankDetailsSection.classList.add('show'), 10);
                setRequiredFields('bank');
            } else if (selectedMethod === 'check') {
                checkDetailsSection.style.display = 'block';
                setTimeout(() => checkDetailsSection.classList.add('show'), 10);
                setRequiredFields('check');
            } else {
                clearRequiredFields();
            }
        }, 300);
    }

    // دالة لتعيين الحقول المطلوبة
    function setRequiredFields(method) {
        clearRequiredFields();
        
        if (method === 'cash') {
            document.getElementById('cashReceiver').required = true;
            document.getElementById('receiverJob').required = true;
            document.getElementById('cashReceiptDate').required = true;
        } else if (method === 'bank') {
            document.getElementById('senderBank').required = true;
            document.getElementById('senderBankBranch').required = true;
            document.getElementById('receiverBank').required = true;
            document.getElementById('receiverBankBranch').required = true;
            document.getElementById('transactionDate').required = true;
        } else if (method === 'check') {
            document.getElementById('checkNumber').required = true;
            document.getElementById('checkOwner').required = true;
            document.getElementById('checkHolder').required = true;
            document.getElementById('checkBank').required = true;
            document.getElementById('checkBankBranch').required = true;
            document.getElementById('checkDueDate').required = true;
            document.getElementById('checkReceiptDate').required = true;
        }
    }

    // دالة لإزالة جميع الحقول المطلوبة
    function clearRequiredFields() {
        const allInputs = document.querySelectorAll('.dynamic-section input, .dynamic-section select');
        allInputs.forEach(input => {
            input.required = false;
        });
    }

    // دالة لإظهار/إخفاء حقل "أخرى" للمستلم النقدي
    function toggleOtherReceiver() {
        const isOther = cashReceiver.value === 'other';
        otherReceiverGroup.style.display = isOther ? 'block' : 'none';
        otherReceiver.required = isOther;
        if (isOther) {
            otherReceiver.focus();
        }
    }

    // دالة لإظهار/إخفاء حقل "أخرى" للبنك المرسل
    function toggleOtherSenderBank() {
        const isOther = senderBank.value === 'other';
        otherSenderBankGroup.style.display = isOther ? 'block' : 'none';
        otherSenderBank.required = isOther;
        if (isOther) {
            otherSenderBank.focus();
        }
    }

    // دالة لإظهار/إخفاء حقل "أخرى" للبنك المستقبل
    function toggleOtherReceiverBank() {
        const isOther = receiverBank.value === 'other';
        otherReceiverBankGroup.style.display = isOther ? 'block' : 'none';
        otherReceiverBank.required = isOther;
        if (isOther) {
            otherReceiverBank.focus();
        }
    }

    // دالة لإظهار/إخفاء حقل "أخرى" لبنك الشيك
    function toggleOtherCheckBank() {
        const isOther = checkBank.value === 'other';
        otherCheckBankGroup.style.display = isOther ? 'block' : 'none';
        otherCheckBank.required = isOther;
        if (isOther) {
            otherCheckBank.focus();
        }
    }

    // ربط الأحداث
    paymentMethodSelect.addEventListener('change', togglePaymentSections);
    cashReceiver.addEventListener('change', toggleOtherReceiver);
    senderBank.addEventListener('change', toggleOtherSenderBank);
    receiverBank.addEventListener('change', toggleOtherReceiverBank);
    checkBank.addEventListener('change', toggleOtherCheckBank);

    // دالة لإظهار التنبيهات
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        
        const formContainer = document.querySelector('.form-container');
        formContainer.insertBefore(alertDiv, formContainer.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // التعامل مع إرسال النموذج
    contractForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // التحقق من صحة البيانات الأساسية
        const contractId = document.getElementById('contractId').value.trim();
        const clientName = document.getElementById('clientName').value.trim();
        const investmentAmount = parseFloat(document.getElementById('investmentAmount').value);
        const paymentMethod = paymentMethodSelect.value;
        const apartmentPrice = parseFloat(document.getElementById('apartment_price').value);
        const downPaymentInitial = parseFloat(document.getElementById('down_payment_initial').value);
        const profitPercentage = parseFloat(document.getElementById('profitPercentage').value);
        const downPaymentOther = parseFloat(document.getElementById('down_payment_other').value || 0); // إذا لم يتم اختيار قيمة، تكون 0

        if (!contractId || !clientName || isNaN(investmentAmount) || !paymentMethod || isNaN(apartmentPrice) || isNaN(downPaymentInitial) || isNaN(profitPercentage)) {
            showAlert('يرجى ملء جميع الحقول المطلوبة بشكل صحيح', 'error');
            return;
        }

        // إعداد تفاصيل الدفع
        let paymentDetails = {
            method: paymentMethod
        };

        if (paymentMethod === 'cash') {
            const receiver = cashReceiver.value === 'other' ? otherReceiver.value : cashReceiver.value;
            if (!receiver || !document.getElementById('receiverJob').value || !document.getElementById('cashReceiptDate').value) {
                showAlert('يرجى ملء جميع تفاصيل الدفع النقدي', 'error');
                return;
            }
            paymentDetails.cash = {
                receiver: receiver,
                job: document.getElementById('receiverJob').value,
                receiptDate: document.getElementById('cashReceiptDate').value
            };
        } else if (paymentMethod === 'bank_transaction') {
            const senderBankName = senderBank.value === 'other' ? otherSenderBank.value : senderBank.value;
            const receiverBankName = receiverBank.value === 'other' ? otherReceiverBank.value : receiverBank.value;
            
            if (!senderBankName || !receiverBankName || !document.getElementById('senderBankBranch').value || !document.getElementById('receiverBankBranch').value || !document.getElementById('transactionDate').value) {
                showAlert('يرجى ملء جميع تفاصيل المعاملة البنكية', 'error');
                return;
            }
            
            paymentDetails.bank = {
                senderBank: senderBankName,
                senderBranch: document.getElementById('senderBankBranch').value,
                receiverBank: receiverBankName,
                receiverBranch: document.getElementById('receiverBankBranch').value,
                reference: document.getElementById('transactionReference').value,
                transactionDate: document.getElementById('transactionDate').value
            };
        } else if (paymentMethod === 'check') {
            const checkBankName = checkBank.value === 'other' ? otherCheckBank.value : checkBank.value;
            
            if (!document.getElementById('checkNumber').value || !document.getElementById('checkOwner').value || !checkBankName || !document.getElementById('checkHolder').value || !document.getElementById('checkBankBranch').value || !document.getElementById('checkDueDate').value || !document.getElementById('checkReceiptDate').value) {
                showAlert('يرجى ملء جميع تفاصيل الشيك', 'error');
                return;
            }
            
            paymentDetails.check = {
                number: document.getElementById('checkNumber').value,
                owner: document.getElementById('checkOwner').value,
                holder: document.getElementById('checkHolder').value,
                bank: checkBankName,
                branch: document.getElementById('checkBankBranch').value,
                dueDate: document.getElementById('checkDueDate').value,
                receiptDate: document.getElementById('checkReceiptDate').value
            };
        }

        // إعداد بيانات العقد
        const contractData = {
            id: contractId,
            signingDate: document.getElementById('signingDate').value,
            status: document.getElementById('status').value,
            client: {
                name: clientName,
                email: document.getElementById('clientEmail').value,
                phone: document.getElementById('clientPhone').value,
                altPhone: document.getElementById('clientAltPhone').value,
                idNumber: document.getElementById('clientIdNumber').value,
            },
            property: {
                type: document.getElementById('propertyType').value,
                location: document.getElementById('propertyLocation').value,
            },
            financials: {
                investmentAmount: investmentAmount,
                duration: parseInt(document.getElementById('duration').value),
                profitPercentage: profitPercentage,
                apartmentPrice: apartmentPrice,
                downPaymentInitial: downPaymentInitial,
                downPaymentOther: downPaymentOther,
                payment: paymentDetails
            },
            payouts: [],
            createdAt: new Date().toISOString()
        };

        // حفظ البيانات
        const contracts = JSON.parse(localStorage.getItem(DB_KEY)) || [];

        if (contracts.some(contract => contract.id === contractData.id)) {
            showAlert('رقم العقد المدخل موجود مسبقاً. الرجاء إدخال رقم فريد.', 'error');
            return;
        }

        contracts.push(contractData);
        localStorage.setItem(DB_KEY, JSON.stringify(contracts));

        showAlert('تم حفظ العقد بنجاح!', 'success');
        
        // إعادة تعيين النموذج بعد 2 ثانية
        setTimeout(() => {
            contractForm.reset();
            document.getElementById('signingDate').value = today;
            togglePaymentSections();
        }, 2000);
    });

    // تهيئة الأقسام عند تحميل الصفحة
    togglePaymentSections();
});
</script>
@endsection

