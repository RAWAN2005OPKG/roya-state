@extends('layouts.container')
@section('title', 'إضافة مشروع جديد')
@section('styles')
    {{-- يمكنك إضافة نفس الـ CSS من تصميمك الأصلي هنا --}}
    <style>
        .form-section { background-color: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #e9ecef; }
        .section-header { font-size: 1.3rem; color: #4f46e5; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #4f46e5; }
        .dynamic-section { display: none; background: #f1f3f5; padding: 15px; border-radius: 8px; margin-top: 15px; }
    </style>
@endsection
@section('content')
<main class="main-content">
    <div class="card card-custom" style="max-width: 1100px; margin: auto;">
        <div class="card-header"><h3 class="card-title">نموذج إضافة مشروع جديد</h3></div>
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('dashboard.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('dashboard.projects.form-fields')
                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary">حفظ المشروع</button>
                    <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

