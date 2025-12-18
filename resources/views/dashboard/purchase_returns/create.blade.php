@extends('layouts.container')
@section('title', 'إضافة مرجوع مشتريات')
@section('content')
<div class="card card-custom">
    <div class="card-header"><h3 class="card-title">إضافة مرجوع مشتريات</h3></div>
    <form action="{{ route('dashboard.purchase-returns.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                {{-- حقل اختيار المورد --}}
                <div class="col-md-6 form-group">
                    <label>اسم المورد</label>
                    <select name="supplier_id" class="form-control select2" required>
                        <option value="">اختر مورد</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group"><label>تاريخ الإرجاع</label><input type="date" name="return_date" class="form-control" required></div>
            </div>

            <h4 class="mt-5 mb-3">المنتجات المرتجعة</h4>
            <div id="return-items-container">
                {{-- الصنف الأول --}}
                <div class="row item-row">
                    <div class="col-md-4 form-group">
                        <label>اختر منتج</label>
                        <select name="items[0][product_id]" class="form-control product-select select2" required>
                            <option value="">اختر منتج</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group"><label>الكمية</label><input type="number" name="items[0][quantity]" class="form-control item-quantity" value="1" min="1"></div>
                    <div class="col-md-3 form-group"><label>سعر الوحدة</label><input type="number" name="items[0][unit_price]" class="form-control item-price" step="0.01"></div>
                    <div class="col-md-3 form-group"><label>الإجمالي</label><input type="text" class="form-control item-total" readonly></div>
                </div>
            </div>
            <button type="button" id="addReturnItemBtn" class="btn btn-light-primary btn-sm"><i class="fas fa-plus"></i> إضافة منتج</button>

            <h4 class="mt-5 mb-3">الدفع</h4>
            <div class="row">
                <div class="col-md-6 form-group"><label>الإجمالي الكلي</label><input type="text" id="grandTotal" name="total_amount" class="form-control" readonly></div>
                <div class="col-md-6 form-group"><label>طريقة الاسترداد</label><select name="payment_method" class="form-control"><option value="cash">نقد</option><option value="bank">بنك</option></select></div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success mr-2">حفظ</button>
            <a href="{{ route('dashboard.purchase-returns.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>

@section('scripts')
<script>
    // تهيئة Select2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "اختر...",
            allowClear: true
        });
    });

    let itemIndex = 1;

    // دالة لحساب الإجمالي
    function calculateTotal(row) {
        const quantity = parseFloat(row.find('.item-quantity').val()) || 0;
        const price = parseFloat(row.find('.item-price').val()) || 0;
        const total = (quantity * price).toFixed(2);
        row.find('.item-total').val(total);
        calculateGrandTotal();
    }

    // دالة لحساب الإجمالي الكلي
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.item-total').each(function() {
            grandTotal += parseFloat($(this).val()) || 0;
        });
        $('#grandTotal').val(grandTotal.toFixed(2));
    }

    // إضافة بند جديد
    $('#addReturnItemBtn').on('click', function() {
        const newRow = `
            <div class="row item-row mt-3">
                <div class="col-md-4 form-group">
                    <select name="items[${itemIndex}][product_id]" class="form-control product-select select2" required>
                        <option value="">اختر منتج</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-name="{{ $product->name }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 form-group"><input type="number" name="items[${itemIndex}][quantity]" class="form-control item-quantity" value="1" min="1"></div>
                <div class="col-md-3 form-group"><input type="number" name="items[${itemIndex}][unit_price]" class="form-control item-price" step="0.01"></div>
                <div class="col-md-2 form-group"><input type="text" class="form-control item-total" readonly></div>
                <div class="col-md-1 form-group">
                    <button type="button" class="btn btn-sm btn-danger remove-item"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;
        $('#return-items-container').append(newRow);
        // إعادة تهيئة Select2 للعنصر الجديد
        $('#return-items-container').find('.row:last-child .select2').select2({
            placeholder: "اختر...",
            allowClear: true
        });
        itemIndex++;
    });

    // إزالة بند
    $('#return-items-container').on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        calculateGrandTotal();
    });

    // حساب الإجمالي عند تغيير الكمية أو السعر
    $('#return-items-container').on('input', '.item-quantity, .item-price', function() {
        calculateTotal($(this).closest('.item-row'));
    });

    // حساب الإجمالي عند تحميل الصفحة لأول مرة
    $('.item-row').each(function() {
        calculateTotal($(this));
    });
</script>
@endsection
