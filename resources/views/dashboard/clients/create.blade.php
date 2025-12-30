{{-- مسار الملف: resources/views/dashboard/clients/create.blade.php --}}

@extends('layouts.container')
@section('title', 'إضافة عميل جديد')

@push('styles')
<style>
    .unit-sale-item {
        border-right: 4px solid #1BC5BD;
        padding-right: 15px;
    }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-user-plus text-primary mr-2"></i>
            إضافة عميل جديد وعمليات البيع
        </h3>
    </div>
    <form action="{{ route('dashboard.clients.store') }}" method="POST">
        @csrf
        <div class="card-body">

            {{-- رسائل التنبيه والتحقق --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>يرجى تصحيح الأخطاء التالية:</p>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- 1. تفاصيل العميل الأساسية --}}
            <h4 class="mb-5 text-primary">1. بيانات العميل</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name">اسم العميل <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="phone">رقم الجوال</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="id_number">رقم الهوية</label>
                    <input type="text" name="id_number" id="id_number" class="form-control" value="{{ old('id_number') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="address">العنوان</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                </div>
            </div>
            <div class="form-group">
                <label for="notes">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            <hr class="my-10">

            {{-- 2. الوحدات المشتراة --}}
            <h4 class="mb-5 text-primary">2. الوحدات المشتراة <small class="text-muted">(يجب اختيار وحدة واحدة على الأقل)</small></h4>
            <div id="units-sale-container">
                {{-- سيتم إضافة حقول الوحدات هنا بواسطة JavaScript --}}
            </div>
            <button type="button" id="add-unit-sale-btn" class="btn btn-success btn-sm mt-3">
                <i class="fas fa-plus"></i> إضافة عملية بيع وحدة
            </button>

        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ العميل وعمليات البيع</button>
            <a href="{{ route('dashboard.clients.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document ).ready(function() {
        let saleIndex = 0;
        const availableUnits = @json($availableUnits);

        function addUnitSaleField() {
            let unitOptions = '<option value="">اختر وحدة متاحة...</option>';
            availableUnits.forEach(unit => {
                unitOptions += `<option value="${unit.id}">[${unit.project.name}] - ${unit.unit_number} (${unit.unit_type})</option>`;
            });

            const saleHtml = `
                <div class="unit-sale-item border p-4 mb-4 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning">تفاصيل عملية البيع #${saleIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-sale-btn">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>الوحدة المشتراة <span class="text-danger">*</span></label>
                            <select name="units[${saleIndex}][unit_id]" class="form-control unit-select" required>
                                ${unitOptions}
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>تاريخ البيع <span class="text-danger">*</span></label>
                            <input type="date" name="units[${saleIndex}][sale_date]" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>مبلغ البيع الفعلي <span class="text-danger">*</span></label>
                            <input type="number" name="units[${saleIndex}][sale_price]" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>العملة <span class="text-danger">*</span></label>
                            <select name="units[${saleIndex}][currency]" class="form-control" required>
                                <option value="USD">دولار أمريكي (USD)</option>
                                <option value="SAR">ريال سعودي (SAR)</option>
                                <option value="EUR">يورو (EUR)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>تفاصيل العقد</label>
                            <textarea name="units[${saleIndex}][contract_details]" class="form-control" rows="1"></textarea>
                        </div>
                    </div>
                </div>
            `;
            $('#units-sale-container').append(saleHtml);
            saleIndex++;
        }

        // عند النقر على زر إضافة عملية بيع
        $('#add-unit-sale-btn').on('click', addUnitSaleField);

        // عند النقر على زر حذف عملية بيع
        $(document).on('click', '.remove-sale-btn', function() {
            $(this).closest('.unit-sale-item').remove();
        });

        // إضافة حقل بيع افتراضي عند تحميل الصفحة
        addUnitSaleField();
    });
</script>
@endpush
