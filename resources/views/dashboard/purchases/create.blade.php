@extends('layouts.container')
@section('title', 'إضافة فاتورة شراء')
@section('content')
<div class="card card-custom">
    <div class="card-header"><h3 class="card-title">إضافة فاتورة شراء جديدة</h3></div>
    <form action="{{ route('dashboard.purchases.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group"><label>اسم المورد</label><input type="text" name="supplier_name" class="form-control" required></div>
                <div class="col-md-6 form-group"><label>رقم الفاتورة</label><input type="text" name="invoice_number" class="form-control" required></div>
                <div class="col-md-6 form-group"><label>تاريخ الفاتورة</label><input type="date" name="invoice_date" class="form-control" required></div>
                <div class="col-md-6 form-group"><label>تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control"></div>
            </div>

            <h4 class="mt-5 mb-3">الأصناف</h4>
            <div id="items-container">
                {{-- هنا يتم إضافة أصناف الفاتورة ديناميكياً --}}
                <div class="row item-row">
                    <div class="col-md-4 form-group"><label>المنتج</label><select name="items[0][product_id]" class="form-control product-select"></select></div>
                    <div class="col-md-2 form-group"><label>الكمية</label><input type="number" name="items[0][quantity]" class="form-control item-quantity" value="1" min="1"></div>
                    <div class="col-md-3 form-group"><label>سعر الوحدة</label><input type="number" name="items[0][unit_price]" class="form-control item-price" step="0.01"></div>
                    <div class="col-md-3 form-group"><label>الإجمالي</label><input type="text" class="form-control item-total" readonly></div>
                </div>
            </div>
            <button type="button" id="addItemBtn" class="btn btn-light-primary btn-sm"><i class="fas fa-plus"></i> إضافة بند</button>

            <h4 class="mt-5 mb-3">الدفع</h4>
            <div class="row">
                <div class="col-md-4 form-group"><label>الإجمالي الكلي</label><input type="text" id="grandTotal" name="total_amount" class="form-control" readonly></div>
                <div class="col-md-4 form-group"><label>المبلغ المدفوع</label><input type="number" name="paid_amount" class="form-control" step="0.01" value="0"></div>
                <div class="col-md-4 form-group"><label>طريقة الدفع</label><select name="payment_method" class="form-control"><option value="cash">نقد</option><option value="bank">بنك</option></select></div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success mr-2">حفظ</button>
            <a href="{{ route('dashboard.purchases.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection
