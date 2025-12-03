@extends('layouts.container')
@section('title', 'إضافة منتج جديد')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            إضافة منتج جديد
        </h3>
    </div>
    <!--begin::Form-->
    <form class="form" action="{{ route('dashboard.products.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-right">اسم المنتج <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="text" name="name" class="form-control" placeholder="أدخل اسم المنتج" required/>
                    @error('name') <span class="form-text text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-right">SKU (رقم التخزين)</label>
                <div class="col-lg-7">
                    <input type="text" name="sku" class="form-control" placeholder="أدخل SKU (اختياري)"/>
                    @error('sku') <span class="form-text text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-right">سعر الشراء <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="number" name="purchase_price" class="form-control" placeholder="0.00" step="0.01" required/>
                    @error('purchase_price') <span class="form-text text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-right">سعر البيع <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="number" name="sale_price" class="form-control" placeholder="0.00" step="0.01" required/>
                    @error('sale_price') <span class="form-text text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-right">الكمية الابتدائية <span class="text-danger">*</span></label>
                <div class="col-lg-7">
                    <input type="number" name="stock" class="form-control" value="0" required/>
                    @error('stock') <span class="form-text text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-7">
                    <button type="submit" class="btn btn-primary font-weight-bold mr-2">حفظ المنتج</button>
                    <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
@endsection
