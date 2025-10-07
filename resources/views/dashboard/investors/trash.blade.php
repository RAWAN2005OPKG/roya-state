@extends('layouts.container')
@section('title', 'سلة المحذوفات - المستثمرون')

@section('styles')
    {{-- يمكنك نسخ نفس الأنماط من صفحة المصروفات هنا لتوحيد الشكل --}}
    <style>
        .table-container { background-color: #fff; padding: 30px; border-radius: 16px; max-width: 1200px; margin: 40px auto; }
        .container-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb; }
        .container-title { font-size: 1.8rem; color: #4f46e5; margin: 0; }
        .btn-back { background-color: #e0e7ff; color: #3730a3; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 700; }
        .data-table { width: 100%; border-collapse: collapse; text-align: right; }
        .data-table th, .data-table td { padding: 14px 18px; border-bottom: 1px solid #e5e7eb; }
        .actions-cell { display: flex; gap: 15px; }
        .action-btn { background: none; border: none; cursor: pointer; font-weight: 700; font-family: inherit; font-size: 1rem; }
        .btn-restore { color: #16a34a; }
        .btn-delete-force { color: #dc2626; }
    </style>
@endsection

@section('content')
<main class="main-content">
    <div class="table-container">
        <!-- رأس الصفحة -->
        <div class="container-header">
            <h2 class="container-title"><i class="fas fa-trash-alt"></i> سلة محذوفات المستثمرين</h2>
            <a href="{{ route('dashboard.investors.index') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i> العودة للمستثمرين
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 15px; background-color: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        <!-- جدول البيانات -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>تاريخ الحذف</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($investors as $investor)
                    <tr>
                        <td>{{ $investor->name }}</td>
                        <td>{{ $investor->deleted_at->format('Y-m-d H:i') }}</td>
                        <td class="actions-cell">
                            <!-- نموذج الاستعادة -->
                            <form action="{{ route('dashboard.investors.trash.restore', $investor->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="action-btn btn-restore">استعادة</button>
                            </form>

                            <!-- نموذج الحذف النهائي -->
                            <form action="{{ route('dashboard.investors.trash.forceDelete', $investor->id) }}" method="POST" onsubmit="return confirm('تحذير! سيتم حذف هذا العنصر نهائياً. هل أنت متأكد؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete-force">حذف نهائي</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 40px; text-align: center; color: #6b7280;">سلة المحذوفات فارغة.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
