@extends('layouts.container')
@section('title', 'تفاصيل الموظف: ' . $employee->name)

@section('styles')
    {{-- أنماط لتحسين العرض --}}
    <style>
        .details-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        .details-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .details-header h2 {
            font-size: 1.75rem;
            color: #1f2937;
        }
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .detail-item {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }
        .detail-item strong {
            display: block;
            color: #6b7280;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        .detail-item span {
            color: #1f2937;
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div style="max-width: 1200px; margin: auto;">

        <!-- بطاقة تفاصيل الموظف -->
        <div class="details-card">
            <div class="details-header">
                <h2>ملف الموظف: {{ $employee->name }}</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('dashboard.employees.index') }}" class="btn btn-secondary">العودة للقائمة</a>
                    <a href="{{ route('dashboard.employees.edit', $employee->id) }}" class="btn btn-primary">تعديل الموظف</a>
                </div>
            </div>
            <div class="details-grid">
                <div class="detail-item"><strong>الاسم الكامل:</strong> <span>{{ $employee->name }}</span></div>
                <div class="detail-item"><strong>المنصب الوظيفي:</strong> <span>{{ $employee->position }}</span></div>
                <div class="detail-item"><strong>البريد الإلكتروني:</strong> <span>{{ $employee->email ?? 'غير مسجل' }}</span></div>
                <div class="detail-item"><strong>رقم الهاتف:</strong> <span>{{ $employee->phone ?? 'غير مسجل' }}</span></div>
                <div class="detail-item"><strong>الراتب الأساسي:</strong> <span>{{ number_format($employee->salary, 2) }} {{ $employee->currency }}</span></div>
                <div class="detail-item"><strong>الحساب البنكي (IBAN):</strong> <span>{{ $employee->iban ?? 'غير مسجل' }}</span></div>
                <div class="detail-item"><strong>تاريخ التعيين:</strong> <span>{{ $employee->created_at->format('d / m / Y') }}</span></div>
            </div>
        </div>

        <!-- بطاقة سجل الرواتب -->
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">سجل الرواتب</h3>
                <div class="card-toolbar">
                    <a href="{{ route('dashboard.employees.pay.form', $employee->id) }}" class="btn btn-success">
                        <i class="fas fa-money-bill-wave"></i> تسجيل دفعة راتب جديدة
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr style="background-color: #f8fafc;">
                                <th>الشهر المرجعي</th>
                                <th>المبلغ المدفوع</th>
                                <th>تاريخ الدفع</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employee->salaryPayments()->orderBy('salary_month', 'desc')->get() as $payment)
                                <tr>
                                    {{-- استخدام Carbon لتحويل '2024-10' إلى 'أكتوبر 2024' --}}
                                    <td>{{ \Carbon\Carbon::parse($payment->salary_month . '-01')->translatedFormat('F Y') }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} {{ $employee->currency }}</td>
                                    <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                                    <td>{{ $payment->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="padding: 20px;">
                                        لا توجد سجلات دفع رواتب لهذا الموظف حتى الآن.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
