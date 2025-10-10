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
