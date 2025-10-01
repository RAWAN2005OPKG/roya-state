@extends('layouts.container')
@section('title', 'إضافة تنبيه')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <h3 class="card-label">إضافة تنبيه جديد</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.alerts.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">العنوان</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">الرسالة</label>
                    <textarea name="message" id="message" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">النوع</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="cheque_due">شيك مستحق</option>
                        <option value="contract_expiry">انتهاء عقد</option>
                        <option value="payment_due">دفعة مستحقة</option>
                        <option value="general">عام</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="priority" class="form-label">الأولوية</label>
                    <select name="priority" id="priority" class="form-control" required>
                        <option value="high">عالية</option>
                        <option value="medium">متوسطة</option>
                        <option value="low">منخفضة</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label">تاريخ الاستحقاق</label>
                    <input type="date" name="due_date" id="due_date" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="assigned_to" class="form-label">تعيين إلى (اختياري)</label>
                    <select name="assigned_to" id="assigned_to" class="form-control">
                        <option value="">غير معين</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">إنشاء التنبيه</button>
            </form>
        </div>
    </div>
@endsection
