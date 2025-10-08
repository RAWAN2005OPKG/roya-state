{{-- =================================================== --}}
{{--         1. حقول النموذج (HTML)                     --}}
{{-- =================================================== --}}

{{-- القسم الأول: المعلومات الأساسية --}}
<div class="form-section">
    <div class="section-header"><h3><i class="fas fa-info-circle"></i> المعلومات الأساسية</h3></div>
    <div class="form-grid">
        <div class="form-group"><label for="name" class="required">اسم المشروع</label><input type="text" id="name" name="name" value="{{ old('name', $project->name ?? '') }}" required></div>
        <div class="form-group"><label for="start_date">تاريخ البدء</label><input type="date" id="start_date" name="start_date" value="{{ old('start_date', isset($project) ? ($project->start_date ? $project->start_date->format('Y-m-d') : '') : '') }}"></div>
        <div class="form-group"><label for="owner_name" class="required">اسم المالك</label><input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $project->owner_name ?? '') }}" required></div>
        <div class="form-group"><label for="owner_phone" class="required">هاتف المالك</label><input type="text" id="owner_phone" name="owner_phone" value="{{ old('owner_phone', $project->owner_phone ?? '') }}" required></div>
        <div class="form-group"><label for="owner_id" class="required">رقم هوية المالك</label><input type="text" id="owner_id" name="owner_id" value="{{ old('owner_id', $project->owner_id ?? '') }}" required></div>
        <div class="form-group"><label for="project_title" class="required">عنوان المشروع</label><input type="text" id="project_title" name="project_title" value="{{ old('project_title', $project->project_title ?? '') }}" required></div>
        <div class="form-group"><label for="project_status" class="required">حالة المشروع</label>
            <select id="project_status" name="project_status" required>
                <option value="on_plan" @selected(old('project_status', $project->project_status ?? '') == 'on_plan')>على مخطط</option>
                <option value="under_construction" @selected(old('project_status', $project->project_status ?? '') == 'under_construction')>قيد الإنشاء</option>
                <option value="ready_finished" @selected(old('project_status', $project->project_status ?? '') == 'ready_finished')>جاهز تشطيب</option>
            </select>
        </div>
    </div>
</div>

{{-- القسم الثاني: تفاصيل الدفع --}}
<div class="form-section">
    <div class="section-header"><h3><i class="fas fa-credit-card"></i> تفاصيل الدفع</h3></div>
    <div class="form-grid">
        <div class="form-group"><label for="paymentMethod">طريقة الدفع</label>
            <select id="paymentMethod" name="payment_method">
                <option value="">-- اختر --</option>
                <option value="نقداً" @selected(old('payment_method', $project->payment_method ?? '') == 'نقداً')>نقداً</option>
                <option value="تحويل بنكي" @selected(old('payment_method', $project->payment_method ?? '') == 'تحويل بنكي')>تحويل بنكي</option>
                <option value="شيك" @selected(old('payment_method', $project->payment_method ?? '') == 'شيك')>شيك</option>
            </select>
        </div>
    </div>
    <!-- قسم الدفع النقدي -->
    <div id="cashDetailsSection" class="dynamic-section {{ old('payment_method', $project->payment_method ?? '') == 'نقداً' ? '' : 'hidden' }}">
        <h4>تفاصيل الدفع النقدي</h4>
        <div class="form-grid">
            <div class="form-group"><label>من استلم المبلغ</label><input type="text" name="cash_receiver" value="{{ old('cash_receiver', $project->cash_receiver ?? '') }}"></div>
            <div class="form-group"><label>وظيفة المستلم</label><input type="text" name="cash_receiver_job" value="{{ old('cash_receiver_job', $project->cash_receiver_job ?? '') }}"></div>
        </div>
    </div>
    <!-- قسم تفاصيل البنك -->
    <div id="bankDetailsSection" class="dynamic-section {{ old('payment_method', $project->payment_method ?? '') == 'تحويل بنكي' ? '' : 'hidden' }}">
        <h4>تفاصيل البنك</h4>
        <div class="form-grid">
            <div class="form-group"><label>البنك المرسل</label><input type="text" name="sender_bank" value="{{ old('sender_bank', $project->sender_bank ?? '') }}"></div>
            <div class="form-group"><label>فرع البنك المرسل</label><input type="text" name="sender_branch" value="{{ old('sender_branch', $project->sender_branch ?? '') }}"></div>
            <div class="form-group"><label>البنك المستقبل</label><input type="text" name="receiver_bank" value="{{ old('receiver_bank', $project->receiver_bank ?? '') }}"></div>
            <div class="form-group"><label>فرع البنك المستقبل</label><input type="text" name="receiver_branch" value="{{ old('receiver_branch', $project->receiver_branch ?? '') }}"></div>
            <div class="form-group"><label>رقم التحويلة</label><input type="text" name="transaction_id" value="{{ old('transaction_id', $project->transaction_id ?? '') }}"></div>
        </div>
    </div>
    <!-- قسم تفاصيل الشيك -->
    <div id="checkDetailsSection" class="dynamic-section {{ old('payment_method', $project->payment_method ?? '') == 'شيك' ? '' : 'hidden' }}">
        <h4>تفاصيل الشيك</h4>
        <div class="form-grid">
            <div class="form-group"><label>رقم الشيك</label><input type="text" name="check_number" value="{{ old('check_number', $project->check_number ?? '') }}"></div>
            <div class="form-group"><label>اسم صاحب الشيك</label><input type="text" name="check_owner" value="{{ old('check_owner', $project->check_owner ?? '') }}"></div>
            <div class="form-group"><label>اسم حامل الشيك</label><input type="text" name="check_holder" value="{{ old('check_holder', $project->check_holder ?? '') }}"></div>
            <div class="form-group"><label>تاريخ الاستحقاق</label><input type="date" name="check_due_date" value="{{ old('check_due_date', isset($project) ? ($project->check_due_date ? $project->check_due_date->format('Y-m-d') : '') : '') }}"></div>
            <div class="form-group"><label>تاريخ الاستلام</label><input type="date" name="check_receive_date" value="{{ old('check_receive_date', isset($project) ? ($project->check_receive_date ? $project->check_receive_date->format('Y-m-d') : '') : '') }}"></div>
        </div>
    </div>
</div>

{{-- القسم الثالث: التكاليف التقديرية --}}
<div class="form-section">
    <div class="section-header"><h3><i class="fas fa-calculator"></i> التكاليف التقديرية</h3></div>
    <div class="form-grid">
        <div class="form-group"><label>تكلفة الأرض</label><input type="number" name="land_cost" class="cost-input" value="{{ old('land_cost', $project->land_cost ?? 0) }}" step="0.01"></div>
        <div class="form-group"><label>تكلفة الحفر</label><input type="number" name="excavation_cost" class="cost-input" value="{{ old('excavation_cost', $project->excavation_cost ?? 0) }}" step="0.01"></div>
        <div class="form-group"><label>تكلفة المهندسين</label><input type="number" name="engineers_cost" class="cost-input" value="{{ old('engineers_cost', $project->engineers_cost ?? 0) }}" step="0.01"></div>
        <div class="form-group"><label>تكاليف التراخيص</label><input type="number" name="licensing_cost" class="cost-input" value="{{ old('licensing_cost', $project->licensing_cost ?? 0) }}" step="0.01"></div>
        <div class="form-group"><label>تكاليف المواد</label><input type="number" name="materials_cost" class="cost-input" value="{{ old('materials_cost', $project->materials_cost ?? 0) }}" step="0.01"></div>
        <div class="form-group"><label>تكاليف التشطيبات</label><input type="number" name="finishing_cost" class="cost-input" value="{{ old('finishing_cost', $project->finishing_cost ?? 0) }}" step="0.01"></div>
        <div class="form-group full-width"><label>إجمالي الميزانية</label><input type="text" id="total_budget" name="total_budget" value="{{ old('total_budget', $project->total_budget ?? 0) }}" readonly></div>
    </div>
</div>

{{-- القسم الرابع: صورة أو فيديو المشروع --}}
<div class="form-section">
    <div class="section-header"><h3><i class="fas fa-photo-video"></i> صورة/فيديو المشروع</h3></div>
    <div class="form-group">
        <label>تحميل ملف جديد</label>
        <input type="file" name="project_media" class="form-control">
        @if(isset($project) && $project->project_media)
            <div class="mt-2">
                <p>الملف الحالي:</p>
                @if(in_array(pathinfo($project->project_media, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $project->project_media) }}" alt="media" style="max-width: 200px; border-radius: 8px;">
                @else
                    <a href="{{ asset('storage/' . $project->project_media) }}" target="_blank">عرض الملف</a>
                @endif
            </div>
        @endif
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const cashDetails = document.getElementById('cashDetailsSection');
    const bankDetails = document.getElementById('bankDetailsSection');
    const checkDetails = document.getElementById('checkDetailsSection');
    const costInputs = document.querySelectorAll('.cost-input');
    const totalBudgetInput = document.getElementById('total_budget');

    function togglePaymentDetails() {
        if (!paymentMethodSelect) return; 
        const selectedMethod = paymentMethodSelect.value;
        if(cashDetails) cashDetails.style.display = (selectedMethod === 'نقداً') ? 'block' : 'none';
        if(bankDetails) bankDetails.style.display = (selectedMethod === 'تحويل بنكي') ? 'block' : 'none';
        if(checkDetails) checkDetails.style.display = (selectedMethod === 'شيك') ? 'block' : 'none';
    }

    function calculateTotal() {
        if (costInputs.length === 0) return; 
        let total = 0;
        costInputs.forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        if(totalBudgetInput) totalBudgetInput.value = total.toFixed(2);
    }

    if(paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', togglePaymentDetails);
    }
    costInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    togglePaymentDetails();
    calculateTotal();
});
@endpush
