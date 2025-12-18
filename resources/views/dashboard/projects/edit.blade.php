@extends('layouts.container')
@section('title', 'تعديل المشروع: ' . $project->name)
@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Cairo', 'Arial', sans-serif;
}

body {
    background: #e6f0ff; /* خلفية هادئة زرقاء فاتحة */
    direction: rtl;
}

.background {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 40px 20px;
    overflow-y: auto;
}

.form-container {
    background: #ffffff;
    padding: 30px 40px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 1100px;
    border: 1px solid #c3d0f0;
}

.form-header h1 {
    font-size: 1.8rem;
    color: #1e3a8a; /* أزرق داكن للنصوص */
    font-weight: 700;
}

.form-header p {
    font-size: 1rem;
    color: #1e40af; /* أزرق متوسط */
}

.form-section {
    background: #f0f4ff; /* أزرق فاتح هادئ */
    margin-bottom: 25px;
    border-radius: 12px;
    border: 1px solid #c3d0f0;
    padding: 20px;
    transition: all 0.3s ease;
}

.section-header {
    font-size: 1.3rem;
    color: #1e3a8a; /* أزرق داكن */
    margin-bottom: 15px;
    border-bottom: 2px solid #1e3a8a;
    padding-bottom: 10px;
    font-weight: 600;
}

.form-group label {
    font-weight: 600;
    color: #1e3a8a; /* أزرق للنصوص */
    font-size: 0.95rem;
}

input, select, textarea {
    width: 100%;
    padding: 12px 16px;
    background-color: #ffffff;
    border: 2px solid #a5b4fc; /* أزرق فاتح */
    border-radius: 10px;
    color: #1e3a8a;
    font-size: 1rem;
    font-family: inherit;
    transition: all 0.3s ease;
}

input:focus, select:focus, textarea:focus {
    border-color: #3b82f6; /* أزرق واضح عند التركيز */
    box-shadow: 0 0 0 4px rgba(59,130,246,0.2);
}

input:hover, select:hover, textarea:hover {
    border-color: #2563eb;
}

.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(59,130,246,0.3);
}

.btn-secondary {
    background: #94a3b8; /* رمادي أزرق مريح */
    color: #ffffff;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}

.dynamic-section {
    display: none;
    background: #dbeafe; /* أزرق فاتح */
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.dynamic-section.show {
    display: block;
}
</style>
@endpush

@section('content')
<main class="main-content">
    <div class="card card-custom" style="max-width: 1100px; margin: auto;">
        <div class="card-header"><h3 class="card-title">تعديل المشروع: {{ $project->name }}</h3></div>
        <div class="card-body">
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('dashboard.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('dashboard.projects.form-fields')
                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary">تحديث المشروع</button>
                    <a href="{{ route('dashboard.projects.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
