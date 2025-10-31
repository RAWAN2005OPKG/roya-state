@extends('layouts.container')
@section('title', 'إضافة عميل جديد')

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 800px; margin: auto;">
        <div class="card-header">
            <h3 class="card-title">نموذج إضافة عميل جديد</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <form action="{{ route('dashboard.customers.store') }}" method="POST">
                @csrf
                <div class="form-section">
                    <h4 class="form-section-title">بيانات العميل الأساسية</h4>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>اسم العميل *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>رقم الجوال</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>العنوان</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">حفظ العميل</button>
            </form>
        </div>
    </div>
</main>
@endsection
