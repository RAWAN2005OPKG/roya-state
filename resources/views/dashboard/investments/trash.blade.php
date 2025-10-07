@extends('layouts.container')
@section('title', 'سلة المحذوفات - الاستثمارات')
@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div style="background-color: #fff; padding: 30px; border-radius: 16px; max-width: 1200px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #e5e7eb;">
            <h2 style="font-size: 1.8rem; color: #4f46e5; margin: 0;">سلة محذوفات الاستثمارات</h2>
            <a href="{{ route('dashboard.investments.index') }}" style="background-color: #e0e7ff; color: #3730a3; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: 700;">العودة للاستثمارات</a>
        </div>
        @if(session('success'))
            <div style="padding: 15px; background-color: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px;">{{ session('success') }}</div>
        @endif
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr>
                    <th style="padding: 12px 15px;">المستثمر</th>
                    <th style="padding: 12px 15px;">المشروع</th>
                    <th style="padding: 12px 15px;">تاريخ الحذف</th>
                    <th style="padding: 12px 15px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($investments as $investment)
                    <tr>
                        <td style="padding: 12px 15px;">{{ $investment->investor->name ?? 'غير محدد' }}</td>
                        <td style="padding: 12px 15px;">{{ $investment->project }}</td>
                        <td style="padding: 12px 15px;">{{ $investment->deleted_at->format('Y-m-d') }}</td>
                        <td style="display: flex; gap: 15px; padding: 12px 15px;">
                            <form action="{{ route('dashboard.investments.trash.restore', $investment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" style="background: none; border: none; color: #16a34a; cursor: pointer; font-weight: 700;">استعادة</button>
                            </form>
                            <form action="{{ route('dashboard.investments.trash.forceDelete', $investment->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; font-weight: 700;">حذف نهائي</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="padding: 40px; text-align: center;">سلة المحذوفات فارغة.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
