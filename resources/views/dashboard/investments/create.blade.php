@extends('layouts.container')
@section('title', 'إضافة استثمار جديد')

@section('styles')
<style>
    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f5f5f5;
    }

    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 16px;
        max-width: 900px;
        margin: 40px auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    h2, h3.section-title {
        color: #4f46e5;
        font-weight: 700;
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
        margin-bottom: 6px;
        font-weight: 600;
        color: #374151;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        box-sizing: border-box;
        transition: 0.3s;
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
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .form-section {
        grid-column: 1 / -1;
        border: 1px solid #e5e7eb;
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="form-container">
        <h2>إضافة استثمار جديد</h2>

        {{-- عرض الأخطاء --}}
        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.investments.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <h3 class="section-title">بيانات الاستثمار</h3>

                <div class="form-grid">
                    {{-- المستثمر --}}
                    <div class="form-group">
                        <label>المستثمر</label>
                        <select name="investor_id" required>
                            <option value="">اختر المستثمر</option>
                            @foreach($investors as $investor)
                                <option value="{{ $investor->id }}">{{ $investor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- المشروع --}}
                    <div class="form-group">
                        <label>المشروع</label>
                        <select name="project_id" required>
                            <option value="">اختر المشروع</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- التاريخ --}}
                    <div class="form-group">
                        <label>تاريخ الاستثمار</label>
                        <input type="date" name="investment_date" required>
                    </div>

                    {{-- نوع الاستثمار --}}
                    <div class="form-group">
                        <label>نوع الاستثمار</label>
                        <input type="text" name="investment_type" placeholder="نقدي، شراكة...">
                    </div>

                    {{-- العملة --}}
                    <div class="form-group">
                        <label>العملة</label>
                        <select name="currency">
                            <option value="usd">دولار</option>
                            <option value="ils">شيكل</option>
                            <option value="jod">دينار</option>
                        </select>
                    </div>

                    {{-- المبلغ --}}
                    <div class="form-group">
                        <label>المبلغ</label>
                        <input type="number" step="0.01" name="amount" required>
                    </div>

                    {{-- نسبة الحصة --}}
                    <div class="form-group">
                        <label>نسبة الحصة (%)</label>
                        <input type="number" step="0.01" name="share_percentage">
                    </div>

                    {{-- الحالة --}}
                    <div class="form-group">
                        <label>حالة الاستثمار</label>
                        <select name="status">
                            <option value="active">نشط</option>
                            <option value="draft">مسودة</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">إضافة الاستثمار</button>
        </form>
    </div>
</main>
@endsection
