@extends('layouts.container')
@section('title', 'سجل المصروفات')

@section('content')
<main class="main-content">

    <div class="table-container" style="...">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="container-title"><i class="fas fa-list-ul"></i> المصروفات المسجلة</h2>
            <a href="{{ route('dashboard.expenses.trash.index') }}" style="background-color: #fee2e2; color: #991b1b; padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-trash"></i> سلة المحذوفات
            </a>
        </div>

        @if(session('success'))
            <div style="padding: 15px; background-color: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <table style="width: 100%; ...">
            {{-- ... رأس الجدول (thead) يبقى كما هو ... --}}
            <tbody>
                @forelse ($expenses as $expense)
                    <tr>
                        {{-- ... باقي الحقول (td) تبقى كما هي ... --}}
                        <td style="padding: 12px 15px; display: flex; gap: 10px;">
                            <a href="{{ route('dashboard.expenses.edit', $expense->id) }}" style="...">تعديل</a>
                            {{-- زر الحذف الآن ينقل إلى سلة المحذوفات --}}
                            <form action="{{ route('dashboard.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من نقل هذا المصروف إلى سلة المحذوفات؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="...">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- ... رسالة "لا توجد مصروفات" ... --}}
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
