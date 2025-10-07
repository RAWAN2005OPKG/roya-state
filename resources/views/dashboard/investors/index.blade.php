@extends('layouts.container')
@section('title', 'إدارة المستثمرين')

@section('styles')
<style>
    .table-container { background-color: #fff; padding: 30px; border-radius: 16px; max-width: 1400px; margin: 40px auto; }
    .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
    .header-title { font-size: 1.8rem; color: #4f46e5; margin: 0; }
    .actions-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn { padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 5px; border: none; }
    .btn-primary { background-color: #4f46e5; color: #fff; }
    .btn-secondary { background-color: #f3f4f6; color: #4b5563; }
    .btn-success { background-color: #107c41; color: #fff; }
    .search-form { margin-bottom: 20px; }
    .search-input { width: 100%; max-width: 400px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; }
    .data-table { width: 100%; border-collapse: collapse; text-align: right; }
    .data-table th, .data-table td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
    .data-table th { background-color: #f9fafb; }
    .sortable-link { color: inherit; text-decoration: none; display: flex; align-items: center; gap: 5px; }
    .sortable-link:hover { color: #4f46e5; }
    .pagination-links { margin-top: 25px; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="table-container">
        <!-- 1. رأس الصفحة وأزرار التحكم -->
        <div class="header-controls">
            <h2 class="header-title">إدارة المستثمرين</h2>
            <div class="actions-group">
                <a href="{{ route('dashboard.investors.create') }}" class="btn btn-primary">إضافة مستثمر</a>
                <a href="{{ route('dashboard.investors.trash.index') }}" class="btn btn-secondary">سلة المحذوفات</a>
                <a href="{{ route('dashboard.investors.export.excel') }}" class="btn btn-success">تصدير Excel</a>
            </div>
        </div>

        <!-- 2. نموذج البحث -->
        <div class="search-form">
            <form action="{{ route('dashboard.investors.index') }}" method="GET">
                <input type="text" name="search" class="search-input" placeholder="ابحث بالاسم, رقم الهوية, الجوال..." value="{{ $search }}">
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>
        </div>

        @if(session('success'))
            <div style="padding: 15px; background-color: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif

        <!-- 3. جدول البيانات مع ميزة الترتيب -->
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            {{-- رابط الترتيب حسب الاسم --}}
                            <a href="{{ route('dashboard.investors.index', ['sort_by' => 'name', 'sort_order' => ($sort_by == 'name' && $sort_order == 'asc') ? 'desc' : 'asc', 'search' => $search]) }}" class="sortable-link">
                                الاسم
                                @if($sort_by == 'name') <i class="fas fa-sort-{{ $sort_order == 'asc' ? 'up' : 'down' }}"></i> @endif
                            </a>
                        </th>
                        <th>
                            {{-- رابط الترتيب حسب رقم الهوية --}}
                            <a href="{{ route('dashboard.investors.index', ['sort_by' => 'id_number', 'sort_order' => ($sort_by == 'id_number' && $sort_order == 'asc') ? 'desc' : 'asc', 'search' => $search]) }}" class="sortable-link">
                                رقم الهوية
                                @if($sort_by == 'id_number') <i class="fas fa-sort-{{ $sort_order == 'asc' ? 'up' : 'down' }}"></i> @endif
                            </a>
                        </th>
                        <th>الجوال</th>
                        <th>البريد الإلكتروني</th>
                        <th>
                            {{-- رابط الترتيب حسب تاريخ الإنشاء --}}
                            <a href="{{ route('dashboard.investors.index', ['sort_by' => 'created_at', 'sort_order' => ($sort_by == 'created_at' && $sort_order == 'asc') ? 'desc' : 'asc', 'search' => $search]) }}" class="sortable-link">
                                تاريخ الإضافة
                                @if($sort_by == 'created_at') <i class="fas fa-sort-{{ $sort_order == 'asc' ? 'up' : 'down' }}"></i> @endif
                            </a>
                        </th>
                        <th>تحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($investors as $investor)
                        <tr>
                            <td>{{ $investor->name }}</td>
                            <td>{{ $investor->id_number ?? '-' }}</td>
                            <td>{{ $investor->phone ?? '-' }}</td>
                            <td>{{ $investor->email ?? '-' }}</td>
                            <td>{{ $investor->created_at->format('Y-m-d') }}</td>
                            <td style="display: flex; gap: 10px;">
                                <a href="{{ route('dashboard.investors.edit', $investor->id) }}" style="color: #2563eb; text-decoration: none;">تعديل</a>
                                <form action="{{ route('dashboard.investors.destroy', $investor->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0;">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="padding: 20px; text-align: center;">لا توجد نتائج تطابق بحثك.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-links">
            {{ $investors->appends(request()->query())->links() }}
        </div>
    </div>
</main>
@endsection
