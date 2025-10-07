@extends('layouts.container')
@section('title', 'إضافة مستثمر جديد')

@section('styles')
    {{-- يمكنك نسخ نفس الأنماط من صفحة المصروفات هنا لتوحيد الشكل --}}
    <style>
        .form-container { background-color: #fff; padding: 30px; border-radius: 16px; max-width: 800px; margin: 40px auto; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; box-sizing: border-box; }
        .btn-submit { background-color: #4f46e5; color: #fff; padding: 12px 20px; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; }
        .form-errors { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="form-container">
        <h2 style="font-size: 1.8rem; color: #4f46e5; margin-bottom: 25px;">إضافة مستثمر جديد</h2>

        {{-- لعرض أخطاء التحقق (Validation) --}}
        @if ($errors->any())
            <div class="form-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.investors.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">اسم المستثمر *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="id_number">رقم الهوية</label>
                <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}">
            </div>
            <div class="form-group">
                <label for="phone">رقم الجوال</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="address">العنوان</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}">
            </div>
            <div class="form-group">
                <label for="notes">ملاحظات</label>
                <textarea id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn-submit">حفظ المستثمر</button>
        </form>
    </div>
</main>
@endsection
