@extends('layouts.container')
@section('title', 'إضافة شيك جديد')
@push('styles')<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />@endpush

@section('content' )
<div class="card card-custom" style="max-width: 900px; margin: 40px auto;">
    <div class="card-header"><h3 class="card-title">إضافة شيك جديد</h3></div>
    <form action="{{ route('dashboard.cheques.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <input type="hidden" name="payable_type" id="payable_type_hidden">
            <div class="row">
                <div class="col-md-6 form-group"><label>نوع الشيك <span class="text-danger">*</span></label><select name="type" class="form-control"><option value="inbound">شيك قبض (داخل)</option><option value="outbound">شيك صرف (خارج)</option></select></div>
                <div class="col-md-6 form-group"><label>رقم الشيك <span class="text-danger">*</span></label><input type="text" name="cheque_number" class="form-control" value="{{ old('cheque_number') }}" required></div>
                <div class="col-md-6 form-group"><label>تاريخ الاستلام <span class="text-danger">*</span></label><input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required></div>
                <div class="col-md-6 form-group"><label>تاريخ الاستحقاق <span class="text-danger">*</span></label><input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}" required></div>
                <div class="col-md-6 form-group"><label>قيمة الشيك <span class="text-danger">*</span></label><input type="number" name="amount" class="form-control" value="{{ old('amount') }}" step="0.01" required></div>
                <div class="col-md-6 form-group"><label>اسم البنك <span class="text-danger">*</span></label><input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required></div>
                <div class="col-md-6 form-group"><label>نوع الكيان <span class="text-danger">*</span></label><select id="entity_type_selector" class="form-control"><option value="">اختر...</option><option value="Client">عميل</option><option value="Investor">مستثمر</option><option value="Subcontractor">مقاول/مورد</option></select></div>
                <div class="col-md-6 form-group"><label>ابحث عن الكيان <span class="text-danger">*</span></label><select name="payable_id" id="payable_id_select" class="form-control" required disabled></select></div>
                <div class="col-12 form-group"><label>ملاحظات</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
            </div>
        </div>
        <div class="card-footer text-left"><button type="submit" class="btn btn-primary">حفظ الشيك</button></div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document ).ready(function() {
    $('#entity_type_selector').on('change', function() {
        const type = $(this).val();
        $('#payable_type_hidden').val(type);
        $('#payable_id_select').prop('disabled', !type).val(null).trigger('change');
        if(type) {
            $('#payable_id_select').select2({
                placeholder: 'ابحث بالاسم أو ID...',
                ajax: {
                    url: "{{ route('dashboard.contracts.getContractables') }}", // سنستخدم نفس الدالة القديمة
                    data: params => ({ q: params.term, type: type }),
                    processResults: data => ({ results: data.items })
                }
            });
        }
    });
});
</script>
@endpush
