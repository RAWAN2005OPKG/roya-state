@extends('layouts.container')
@section('title', 'إدارة الاستثمارات')

{{-- 1. إضافة قسم الأنماط CSS للطباعة --}}
@section('styles')
<style>
    /* أنماط خاصة بالطباعة (مهم جداً) */
    @media print {
        body * {
            visibility: hidden; /* إخفاء كل شيء في الصفحة أولاً */
        }
        .printable-area, .printable-area * {
            visibility: visible; /* إظهار منطقة الطباعة ومحتوياتها فقط */
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important; /* إخفاء العناصر التي لا نريدها في الطباعة */
        }
    }
</style>
@endsection

@section('content')
<main class="main-content" style="padding-top: 40px;">
    {{-- تم إضافة ID هنا لمنطقة الطباعة --}}
    <div id="investments-table-area" style="background-color: #fff; padding: 30px; border-radius: 16px; max-width: 1400px; margin: auto; overflow-x: auto;">

        {{-- رأس الصفحة مع الأزرار --}}
        <div class="no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
            <h2 style="font-size: 1.8rem; color: #4f46e5; margin: 0;">إدارة الاستثمارات</h2>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('dashboard.investments.create') }}" style="background-color: #4f46e5; color: #fff; padding: 8px 15px; border-radius: 8px; text-decoration: none;">إضافة استثمار</a>
                <a href="{{ route('dashboard.investments.trash.index') }}" style="background-color: #f3f4f6; color: #4b5563; padding: 8px 15px; border-radius: 8px; text-decoration: none;">سلة المحذوفات</a>
                <a href="{{ route('dashboard.investments.export.excel') }}" style="background-color: #107c41; color: #fff; padding: 8px 15px; border-radius: 8px; text-decoration: none;">تصدير Excel</a>

                {{-- 2. إضافة زر الطباعة --}}
                <button onclick="printTable()" style="background-color: #3b82f6; color: #fff; padding: 8px 15px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-print"></i> طباعة
                </button>
            </div>
        </div>

        {{-- 3. إضافة نموذج البحث --}}
        <div class="no-print" style="margin-bottom: 20px;">
            <form action="{{ route('dashboard.investments.index') }}" method="GET">
                <input type="text" name="search" placeholder="ابحث باسم المستثمر أو المشروع..." value="{{ $search }}" style="width: 100%; max-width: 400px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px;">
                <button type="submit" style="background-color: #4f46e5; color: #fff; padding: 10px 15px; border-radius: 8px; border: none; cursor: pointer;">بحث</button>
            </form>
        </div>

        @if(session('success'))
            <div class="no-print" style="padding: 15px; background-color: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        {{-- منطقة الطباعة تبدأ من هنا --}}
        <div class="printable-area">
            <table style="width: 100%; border-collapse: collapse; text-align: right; min-width: 1200px;">
                <thead>
                    <tr style="background-color: #f9fafb;">
                        <th style="padding: 12px 15px;">المستثمر</th>
                        <th style="padding: 12px 15px;">المشروع</th>
                        <th style="padding: 12px 15px;">المبلغ</th>
                        <th style="padding: 12px 15px;">الحصة</th>
                        <th style="padding: 12px 15px;">الحالة</th>
                        <th style="padding: 12px 15px;">طريقة الدفع</th>
                        <th style="padding: 12px 15px;">تاريخ الاستثمار</th>
                        <th class="no-print" style="padding: 12px 15px;">تحكم</th> {{-- إخفاء عمود التحكم عند الطباعة --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($investments as $investment)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px 15px;">{{ $investment->investor->name ?? 'غير محدد' }}</td>
                            <td style="padding: 12px 15px;">{{ $investment->project }}</td>
                            <td style="padding: 12px 15px;">{{ number_format($investment->amount, 2) }} {{ $investment->currency }}</td>
                            <td style="padding: 12px 15px;">{{ $investment->share_percentage ?? 0 }}%</td>
                            <td style="padding: 12px 15px;">
                                <span style="padding: 4px 8px; border-radius: 12px; font-size: 0.8rem;
                                    @if($investment->status == 'active') background-color: #d1fae5; color: #065f46;
                                    @elseif($investment->status == 'completed') background-color: #dbeafe; color: #1e40af;
                                    @else background-color: #fee2e2; color: #991b1b; @endif">
                                    {{ $investment->status == 'active' ? 'نشط' : ($investment->status == 'completed' ? 'مكتمل' : 'ملغي') }}
                                </span>
                            </td>
                            <td style="padding: 12px 15px;">{{ $investment->payment_method ?? '-' }}</td>
<td style="padding: 12px 15px;">
    {{ $investment->date ? $investment->date->format('Y-m-d') : '-' }}
</td>
                            <td class="no-print" style="display: flex; gap: 10px; padding: 12px 15px;"> {{-- إخفاء خلية التحكم عند الطباعة --}}
                                <a href="{{ route('dashboard.investments.edit', $investment->id) }}" style="color: #2563eb; text-decoration: none; font-weight: 600;">تعديل</a>
                                <form action="{{ route('dashboard.investments.destroy', $investment->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0; font-weight: 600;">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="padding: 20px; text-align: center;">لا توجد نتائج تطابق بحثك.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- نهاية منطقة الطباعة --}}

        {{-- روابط التنقل بين الصفحات --}}
        <div class="no-print" style="margin-top: 25px;">
            {{-- إضافة appends للحفاظ على نتيجة البحث عند التنقل بين الصفحات --}}
            {{ $investments->appends(request()->query())->links() }}
        </div>

    </div>
</main>
@endsection

{{-- 4. إضافة قسم السكريبت لدالة الطباعة --}}
@section('script')
<script>
    function printTable() {
        const printTitle = "<h2>تقرير الاستثمارات</h2>";
        const tableArea = document.querySelector('.printable-area').innerHTML;

        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>طباعة التقرير</title>');
        printWindow.document.write('<style>body { font-family: Cairo, sans-serif; direction: rtl; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #ddd; padding: 8px; text-align: right; } th { background-color: #f2f2f2; } .no-print { display: none; } </style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printTitle);
        printWindow.document.write(tableArea);
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>
@endsection
