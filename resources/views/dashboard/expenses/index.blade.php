@extends('layouts.container')
@section('title', 'سجل المصروفات')

@section('styles')
<style>
    /* أنماط عامة لتحسين الشكل */
    .table-container { background-color: #fff; padding: 30px; border-radius: 16px; max-width: 1400px; margin: 40px auto; }
    .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
    .header-title { font-size: 1.8rem; color: #4f46e5; margin: 0; }
    .actions-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn { padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; border: none; cursor: pointer; }
    .btn-primary { background-color: #4f46e5; color: #fff; }
    .btn-secondary { background-color: #f3f4f6; color: #4b5563; }
    .btn-success { background-color: #107c41; color: #fff; }
    .btn-info { background-color: #3b82f6; color: #fff; } /* زر الطباعة */
    .search-form { margin-bottom: 20px; }
    .search-input { width: 100%; max-width: 400px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; }
    .data-table { width: 100%; border-collapse: collapse; text-align: right; }
    .data-table th, .data-table td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
    .data-table th { background-color: #f9fafb; }
    .pagination-links { margin-top: 25px; }

    @media print {
        body * {
            visibility: hidden; 
        }
        .printable-area, .printable-area * {
            visibility: visible; 
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none; /* إخفاء الأعمدة والأزرار التي لا نريدها في الطباعة */
        }
    }
</style>
@endsection

@section('content')
<main class="main-content">
    {{-- يمكنك إبقاء نموذج الإضافة هنا أو نقله لصفحة create.blade.php --}}

    <div class="table-container" id="expenses-table-area"> {{-- إضافة ID لمنطقة الطباعة --}}
        <!-- 1. رأس الصفحة وأزرار التحكم -->
        <div class="header-controls no-print"> {{-- إضافة كلاس لعدم الطباعة --}}
            <h2 class="header-title">المصروفات المسجلة</h2>
            <div class="actions-group">
                {{-- <a href="..." class="btn btn-primary">إضافة مصروف</a> --}}
                <a href="{{ route('dashboard.expenses.trash.index') }}" class="btn btn-secondary">سلة المحذوفات</a>
                <a href="{{ route('dashboard.expenses.export.excel') }}" class="btn btn-success">تصدير Excel</a>
                {{-- زر الطباعة الجديد --}}
                <button onclick="printTable()" class="btn btn-info">
                    <i class="fas fa-print"></i> طباعة
                </button>
            </div>
        </div>

        <!-- 2. نموذج البحث -->
        <div class="search-form no-print"> {{-- إضافة كلاس لعدم الطباعة --}}
            <form action="{{ route('dashboard.expenses.index') }}" method="GET">
                <input type="text" name="search" class="search-input" placeholder="ابحث باسم المستفيد, ملاحظات..." value="{{ $search }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        <!-- 3. جدول البيانات -->
        <div class="printable-area" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>المستفيد</th>
                        <th>المبلغ</th>
                        <th>طريقة الدفع</th>
                        <th class="no-print">تحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ $expense->date->format('Y-m-d') }}</td>
                            <td>{{ $expense->payee }}</td>
                            <td>{{ number_format($expense->amount, 2) }} {{ $expense->currency }}</td>
                            <td>{{ $expense->payment_method }}</td>
                            <td class="no-print">
                                <div style="display: flex; gap: 10px;">
                                    <a href="{{ route('dashboard.expenses.edit', $expense->id) }}" style="color: #2563eb;">تعديل</a>
                                    <form action="{{ route('dashboard.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none; border:none; color:#dc2626; cursor:pointer; padding:0;">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding: 20px; text-align: center;">لا توجد نتائج تطابق بحثك.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-links no-print"> 
            {{ $expenses->appends(request()->query())->links() }}
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    function printTable() {
        const printTitle = "<h2>تقرير المصروفات</h2>";
        const tableArea = document.getElementById('expenses-table-area').querySelector('.printable-area').innerHTML;

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
