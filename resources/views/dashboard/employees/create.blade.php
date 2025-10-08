@extends('layouts.container')
@section('title', 'إضافة موظف جديد')
@section('content')
<main class="main-content">
    <div class="card card-custom" style="max-width: 800px; margin: auto;">
        <div class="card-header"><h3 class="card-title">نموذج إضافة موظف جديد</h3></div>
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('dashboard.employees.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group mb-3"><label>الاسم *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                    <div class="col-md-6 form-group mb-3"><label>المنصب *</label><input type="text" name="position" class="form-control" value="{{ old('position') }}" required></div>
                    <div class="col-md-6 form-group mb-3"><label>البريد الإلكتروني</label><input type="email" name="email" class="form-control" value="{{ old('email') }}"></div>
                    <div class="col-md-6 form-group mb-3"><label>الهاتف</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
                    <div class="col-md-6 form-group mb-3"><label>الراتب *</label><input type="number" name="salary" class="form-control" value="{{ old('salary') }}" step="0.01" required></div>
                    <div class="col-md-6 form-group mb-3"><label>العملة *</label><select name="currency" class="form-control" required><option value="ILS" @selected(old('currency') == 'ILS')>شيكل</option><option value="USD" @selected(old('currency') == 'USD')>دولار</option><option value="JOD" @selected(old('currency') == 'JOD')>دينار</option></select></div>
                    <div class="col-md-6 form-group mb-3"><label>IBAN</label><input type="text" name="iban" class="form-control" value="{{ old('iban') }}"></div>
                    <div class="col-md-6 form-group mb-3"><label>المحفظة</label><input type="text" name="wallet_name" class="form-control" value="{{ old('wallet_name') }}"></div>
                    <div class="col-md-6 form-group mb-3"><label>البنك</label><input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}"></div>
                    <div class="col-md-6 form-group mb-3"><label>فرع البنك</label><input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch') }}"></div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">حفظ الموظف</button>
            </form>
        </div>
    </div>
</main>
@endsection
