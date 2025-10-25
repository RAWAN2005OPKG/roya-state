<div class="form-section">
    <div class="section-header"><i class="fas fa-coins"></i> <h3>بيانات الاستثمار</h3></div>

    <div class="form-grid">
        <div class="form-group">
            <label>المستثمر</label>
            <select name="investor_id" required>
                <option value="">اختر المستثمر</option>
                @foreach($investors as $investor)
                    <option value="{{ $investor->id }}"
                        @selected(old('investor_id', $investment->investor_id ?? '') == $investor->id)>
                        {{ $investor->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>المشروع</label>
            <select name="project_id" required>
                <option value="">اختر المشروع</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}"
                        @selected(old('project_id', $investment->project_id ?? '') == $project->id)>
                        {{ $project->project_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>تاريخ الاستثمار</label>
            <input type="date" name="investment_date" value="{{ old('investment_date', $investment->investment_date ?? '') }}">
        </div>

        <div class="form-group">
            <label>نوع الاستثمار</label>
            <input type="text" name="investment_type" placeholder="نقدي، شراكة..." value="{{ old('investment_type', $investment->investment_type ?? '') }}">
        </div>

        <div class="form-group">
            <label>العملة</label>
            <select name="currency">
                <option value="usd" @selected(old('currency', $investment->currency ?? '') == 'usd')>دولار</option>
                <option value="ils" @selected(old('currency', $investment->currency ?? '') == 'ils')>شيكل</option>
                <option value="jod" @selected(old('currency', $investment->currency ?? '') == 'jod')>دينار</option>
            </select>
        </div>
          <div class="form-group">
            <label>المبلغ</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $investment->amount ?? '') }}">
        </div>

        <div class="form-group">
            <label>نسبة الحصة (%)</label>
            <input type="number" step="0.01" name="share_percentage" value="{{ old('share_percentage', $investment->share_percentage ?? '') }}">
        </div>

        <div class="form-group">
            <label>حالة الاستثمار</label>
            <select name="status">
                <option value="active" @selected(old('status', $investment->status ?? '') == 'active')>نشط</option>
                <option value="complete" @selected(old('status', $investment->status ?? '') == 'complete')>مكتمل</option>
                <option value="draft" @selected(old('status', $investment->status ?? '') == 'draft')>مسودة</option>
            </select>
        </div>
    </div>
</div>
