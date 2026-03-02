{{-- هذا الملف يتم استدعاؤه في صفحتي الإنشاء والتعديل --}}
<div class="row">
    <div class="col-md-6 form-group"><label>اسم التقرير <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $report->name ?? '') }}" required></div>
    <div class="col-md-6 form-group"><label>عنوان المشروع <span class="text-danger">*</span></label><input type="text" name="project_title" class="form-control" value="{{ old('project_title', $report->project_title ?? '') }}" required></div>
</div>
<div class="row">
    <div class="col-md-4 form-group"><label>اسم المالك <span class="text-danger">*</span></label><input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $report->owner_name ?? '') }}" required></div>
    <div class="col-md-4 form-group"><label>هاتف المالك</label><input type="text" name="owner_phone" class="form-control" value="{{ old('owner_phone', $report->owner_phone ?? '') }}"></div>
    <div class="col-md-4 form-group"><label>هوية المالك</label><input type="text" name="owner_id" class="form-control" value="{{ old('owner_id', $report->owner_id ?? '') }}"></div>
</div>
<hr>
<div class="row">
    <div class="col-md-4 form-group"><label>حالة المشروع <span class="text-danger">*</span></label><input type="text" name="project_status" class="form-control" value="{{ old('project_status', $report->project_status ?? 'قيد التنفيذ') }}" required></div>
    <div class="col-md-4 form-group"><label>تاريخ البدء</label><input type="date" name="start_date" class="form-control" value="{{ old('start_date', isset($report) ? ($report->start_date ? $report->start_date->format('Y-m-d') : '') : '') }}"></div>
</div>
<div class="row">
    <div class="col-md-6 form-group"><label>الميزانية</label><input type="number" name="total_budget" class="form-control" value="{{ old('total_budget', $report->total_budget ?? '') }}" step="0.01"></div>
    <div class="col-md-6 form-group"><label>العملة <span class="text-danger">*</span></label><select name="currency" class="form-control" required><option value="USD" @selected(old('currency', $report->currency ?? 'USD') == 'USD')>USD</option><option value="ILS" @selected(old('currency', $report->currency ?? '') == 'ILS')>ILS</option></select></div>
</div>
<div class="form-group"><label>الوصف</label><textarea name="description" class="form-control" rows="3">{{ old('description', $report->description ?? '') }}</textarea></div>
