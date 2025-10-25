@extends('layouts.container')
@section('title', 'تعديل الاستثمار')

@section('styles')
{{-- (أنماط CSS تبقى كما هي بدون تغيير) --}}
<style>
    select option {
        color: #000 !important;
    }
    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 16px;
        max-width: 900px;
        margin: 40px auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group.full-width {
        grid-column: 1 / -1;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #000000;
        border-radius: 8px;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .btn-submit {
        background-color: #4f46e5;
        color: #fff;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        margin-top: 20px;
        transition: background-color 0.3s;
    }
    .btn-submit:hover {
        background-color: #4338ca;
    }
    .form-errors {
        background-color: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 1.2rem;
        color: #4f46e5;
        margin-top: 20px;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #e5e7eb;
        grid-column: 1 / -1;
    }
    .hidden {
        display: none !important;
    }
    /* أنماط إضافية من الكود الأصلي */
    .form-section {
        grid-column: 1 / -1;
        border: 1px solid #e5e7eb;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
    .payment-methods-group label {
        margin-right: 15px;
        font-weight: normal;
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="form-container">
        <h2 style="font-size: 1.8rem; color: #4f46e5; margin-bottom: 25px;">
            تعديل استثمار في مشروع: {{ $investment->project->name ?? 'غير محدد' }}
        </h2>

        @if ($errors->any())
        <div class="form-errors">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('dashboard.investments.update', $investment->id) }}" method="POST">
             @csrf
                @method('PUT')
                @include('dashboard.investments.form')
                <div class="form-actions mt-4">

            <button type="submit" class="btn-submit">تحديث الاستثمار</button>
        </form>
    </div>
</main>
@endsection

