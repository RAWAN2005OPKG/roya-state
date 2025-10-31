@extends('layouts.container')
@section('title', 'تعديل بيانات المقاول')

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 800px; margin: auto;">
        <div class="card-header"><h3 class="card-title">تعديل بيانات: {{ $subcontractor->name }}</h3></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div>
            @endif

            <form action="{{ route('dashboard.subcontractors.update', $subcontractor->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>اسم المقاول/الشركة *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $subcontractor->name) }}" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>نوع الخدمة *</label>
                        <input type="text" name="service_type" class="form-control" value="{{ old('service_type', $subcontractor->service_type) }}" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $subcontractor->phone) }}">
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>اسم مسؤول التواصل</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $subcontractor->contact_person) }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4">تحديث البيانات</button>
            </form>
        </div>
    </div>
</main>
@endsection
