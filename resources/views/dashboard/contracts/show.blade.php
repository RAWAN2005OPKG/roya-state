@extends('layouts.app')

@section('title', 'تفاصيل العقد رقم: ' . $contract->id)

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1>تفاصيل العقد رقم: {{ $contract->id }}</h1>
            <p class="lead">العميل: {{ $contract->client_name ?? 'غير محدد' }}</p>
        </div>
    </div>

    {{-- رسائل الأخطاء والنجاح --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ملخص المبالغ المالية --}}
    <div class="row mb-5">
        @php
            // حساب المبلغ المتبقي بناءً على البيانات المحدثة في قاعدة البيانات
            $remaining = $contract->investment_amount - $contract->total_paid;
        @endphp

        {{-- 1. إجمالي قيمة العقد (الجزء الذي أرسله المستخدم) --}}
        <div class="col-md-4 mb-3">
            <div class="p-3 border rounded bg-light">
                <h5 class="text-secondary">إجمالي قيمة العقد</h5>
                <h3 class="text-dark">{{ format_number($contract->investment_amount) }} {{ $contract->currency }}</h3>
            </div>
        </div>

        {{-- 2. إجمالي المبلغ المدفوع --}}
        <div class="col-md-4 mb-3">
            <div class="p-3 border rounded bg-success-light">
                <h5 class="text-success">إجمالي المبلغ المدفوع</h5>
                <h3 class="text-success">{{ format_number($contract->total_paid) }} {{ $contract->currency }}</h3>
            </div>
        </div>

        {{-- 3. المبلغ المتبقي --}}
        <div class="col-md-4 mb-3">
            <div class="p-3 border rounded @if($remaining > 0) bg-warning-light @else bg-info-light @endif">
                <h5 class="text-warning">المبلغ المتبقي</h5>
                <h3 class="text-warning">{{ format_number($remaining) }} {{ $contract->currency }}</h3>
            </div>
        </div>
    </div>

    {{-- جدول الدفعات --}}
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="m-0 font-weight-bold text-primary">الدفعات المسجلة</h4>
            <a href="{{ route('dashboard.contracts.payments.create', $contract->id) }}" class="btn btn-primary btn-sm">
                إضافة دفعة جديدة
            </a>
        </div>
        <div class="card-body">
            @if ($contract->payments->isEmpty())
                <p class="text-center">لم يتم تسجيل أي دفعات لهذا العقد بعد.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="paymentsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>تاريخ الدفعة</th>
                                <th>المبلغ</th>
                                <th>طريقة الدفع</th>
                                <th>الخزنة</th>
                                <th>الوصف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contract->payments as $payment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ format_number($payment->amount) }} {{ $payment->currency }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ $payment->fund->name ?? 'N/A' }}</td>
                                    <td>{{ $payment->description }}</td>
                                    <td>
                                        <form action="{{ route('dashboard.contracts.payments.destroy', [$contract->id, $payment->id]) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الدفعة؟ سيتم تعديل المبلغ المدفوع في العقد.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
