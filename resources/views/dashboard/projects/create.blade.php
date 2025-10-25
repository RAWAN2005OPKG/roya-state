@extends('layouts.container')
@section('title', 'إضافة مشروع جديد')
@section('styles')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap');
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', 'Arial', sans-serif;
    }
    .background {
        background: linear-gradient(135deg, #efeff0 0%, #cdcacf 100%);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        direction: rtl;
        padding: 40px 20px;
        overflow-y: auto;
    }
    .form-container {
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 1100px;
        border: 1px solid #e9ecef;
        position: relative;
        overflow: hidden;
    }
    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
  .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f8f9fa;
        position: relative;
    }
 .header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .header-text h1 {
        font-size: 1.8rem;
        background: linear-gradient(135deg, #cecfd4, #9e9ca0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
        font-weight: 700;
    }

    .header-text p {
        font-size: 1rem;
        color: #000000;
        margin: 0;
    }

    .form-section {
        margin-bottom: 35px;
        background: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }

    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0;
        background: linear-gradient(135deg, #ffffff, #cac7ce);
        color: white;
        font-size: 1.2rem;
        padding: 20px 25px;
        position: relative;
        overflow: hidden;
    }

    .section-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .section-header:hover::before {
        left: 100%;
    }

    .section-header i {
        margin-left: 5px;
        font-size: 1.3rem;
    }

    .section-header h3 {
        margin: 0;
        font-weight: 600;
        color: white;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        padding: 25px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
    }

    .form-group label {
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-group:focus-within label {
        color: #c3c5cf;
        transform: translateY(-2px);
    }

    .form-group label.required::after {
        content: '*';
        color: #dc3545;
        margin-right: 5px;
        font-weight: bold;
    }

    input, select, textarea {
        width: 100%;
        padding: 12px 16px;
        background-color: #ffffff;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        color: #495057;
        font-size: 1rem;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #cacee2;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    input:hover, select:hover, textarea:hover {
        border-color: #bcb6c2;
    }

    .input-with-currency {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-with-currency input {
        padding-left: 60px;
    }

    .input-with-currency .currency {
        position: absolute;
        left: 16px;
        color: #6c757d;
        font-weight: 500;
        font-size: 0.9rem;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .form-group:focus-within .currency {
        background: #667eea;
        color: white;
    }

    .dynamic-section {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 25px;
        border-radius: 15px;
        margin-top: 20px;
        border: 2px dashed #dee2e6;
        position: relative;
        overflow: hidden;
        grid-column: 1 / -1;
    }

    .dynamic-section.hidden {
        display: none;
    }

    .dynamic-section.show {
        display: block;
        animation: slideDown 0.4s ease;
    }


    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dynamic-section h4 {
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 20px;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .file-upload-area {
        border: 3px dashed #dee2e6;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        position: relative;
        overflow: hidden;
    }



    .file-upload-area::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .file-upload-area:hover::before {
        left: 100%;
    }

    .upload-content i {
        font-size: 3rem;
        background: linear-gradient(135deg, #fcfcfc, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 16px;
        display: block;
    }

    .upload-content p {
        font-size: 1.1rem;
        color: #495057;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .file-types {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .media-preview {
        margin-top: 20px;
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .media-preview img,
    .media-preview video {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
        border-radius: 15px;
    }

    .remove-media {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .remove-media:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid #f8f9fa;
        flex-wrap: wrap;
    }

    .btn {
        padding: 12px 25px;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        overflow: hidden;
        min-width: 140px;
        justify-content: center;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-info {
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .hidden {
        display: none !important;
    }

    /* تحسينات الاستجابة */
    @media (max-width: 768px) {
        .background {
            padding: 10px;
        }

        .form-container {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }

        .header-text h1 {
            font-size: 1.5rem;
        }
    }

    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: left 12px center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-left: 40px;
        appearance: none;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }
        .btn.loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn.loading::after {
        content: '';
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
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

