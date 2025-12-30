{{-- مسار الملف: resources/views/dashboard/investors/create.blade.php --}}

@extends('layouts.container')
@section('title', 'إضافة مستثمر جديد')

@push('styles')
<style>
    .investment-item {
        border-right: 4px solid #FFA800;
        padding-right: 15px;
    }
</style>
@endpush

@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-handshake text-warning mr-2"></i>
            إضافة مستثمر جديد
        </h3>
    </div>
    <form action="{{ route('dashboard.investors.store') }}" method="POST">
        @csrf
        <div class="card-body">

            {{-- رسائل التنبيه والتحقق --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>يرجى تصحيح الأخطاء التالية:</p>
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- 1. تفاصيل المستثمر الأساسية --}}
            <h4 class="mb-5 text-primary">1. بيانات المستثمر</h4>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name">اسم المستثمر <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="company">الشركة</label>
                    <input type="text" name="company" id="company" class="form-control" value="{{ old('company') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="phone">رقم الجوال</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="form-group">
                <label for="notes">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>

            <hr class="my-10">

            {{-- 2. المشاريع المستثمر فيها --}}
            <h4 class="mb-5 text-primary">2. المشاريع المستثمر فيها <small class="text-muted">(يمكن إضافة أكثر من مشروع)</small></h4>
            <div id="investments-container">
                {{-- سيتم إضافة حقول الاستثمار هنا بواسطة JavaScript --}}
            </div>
            <button type="button" id="add-investment-btn" class="btn btn-warning btn-sm mt-3">
                <i class="fas fa-plus"></i> إضافة استثمار في مشروع
            </button>

        </div>
        <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary mr-2">حفظ المستثمر</button>
            <a href="{{ route('dashboard.investors.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document ).ready(function() {
        let investmentIndex = 0;
        const projectsList = @json($projects);

        function addInvestmentField() {
            let projectOptions = '<option value="">اختر مشروع...</option>';
            projectsList.forEach(project => {
                projectOptions += `<option value="${project.id}">${project.name} (${project.location})</option>`;
            });

            const investmentHtml = `
                <div class="investment-item border p-4 mb-4 rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-warning">تفاصيل الاستثمار #${investmentIndex + 1}</h5>
                        <button type="button" class="btn btn-danger btn-sm remove-investment-btn">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>المشروع <span class="text-danger">*</span></label>
                            <select name="investments[${investmentIndex}][project_id]" class="form-control" required>
                                ${projectOptions}
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>نسبة الاستثمار (%) <span class="text-danger">*</span></label>
                            <input type="number" name="investments[${investmentIndex}][investment_percentage]" class="form-control" step="0.01" min="0.01" max="100" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>المبلغ المستثمر فعلياً <span class="text-danger">*</span></label>
                            <input type="number" name="investments[${investmentIndex}][invested_amount]" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>العملة <span class="text-danger">*</span></label>
                            <select name="investments[${investmentIndex}][currency]" class="form-control" required>
                                <option value="USD">دولار أمريكي (USD)</option>
                                <option value="SAR">ريال سعودي (SAR)</option>
                                <option value="EUR">يورو (EUR)</option>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>ملاحظات الاستثمار</label>
                            <textarea name="investments[${investmentIndex}][notes]" class="form-control" rows="1"></textarea>
                        </div>
                    </div>
                </div>
            `;
            $('#investments-container').append(investmentHtml);
            investmentIndex++;
        }

        // عند النقر على زر إضافة استثمار
        $('#add-investment-btn').on('click', addInvestmentField);

        // عند النقر على زر حذف استثمار
        $(document).on('click', '.remove-investment-btn', function() {
            $(this).closest('.investment-item').remove();
        });

        // إضافة حقل استثمار افتراضي عند تحميل الصفحة
        addInvestmentField();
    });
</script>
@endpush
