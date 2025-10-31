@extends('layouts.container')
@section('title', 'تعديل بيانات العميل')

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 900px; margin: auto;">
        <div class="card-header"><h3 class="card-title">تعديل: {{ $customer->name }}</h3></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <form action="{{ route('dashboard.customers.update', $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-section">
                    <h4 class="form-section-title">بيانات العميل</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3"><label>اسم العميل *</label><input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required></div>
                        <div class="col-md-6 form-group mb-3"><label>رقم الجوال</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>البريد الإلكتروني</label><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}"></div>
                        <div class="col-md-6 form-group mb-3"><label>العنوان</label><input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}"></div>
                    </div>
                </div>

                {{-- قسم الاتفاقية المالية --}}
                <div class="form-section">
                    <h4 class="form-section-title">الاتفاقية المالية (اختياري)</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>قيمة الاتفاقية</label>
                            <input type="number" name="agreement_amount" class="form-control" value="{{ old('agreement_amount', $customer->agreement_amount) }}" step="0.01">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>العملة</label>
                            <select name="currency" class="form-control">
                                <option value="ILS" @selected(old('currency', $customer->currency) == 'ILS')>شيكل</option>
                                <option value="USD" @selected(old('currency', $customer->currency) == 'USD')>دولار</option>
                                <option value="JOD" @selected(old('currency', $customer->currency) == 'JOD')>دينار</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">تحديث البيانات</button>
            </form>
        </div>
    </div>
</main>
@endsection
