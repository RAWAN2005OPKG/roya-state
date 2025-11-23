@extends('layouts.container')
@section('title', 'قيود اليومية')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-book"></i> قيود اليومية</h1>
        <div class="header-actions">
            <a href="{{ route('dashboard.journal-entries.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> قيد يومية جديد</a>
        </div>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم القيد</th>
                        <th>التاريخ</th>
                        <th>البيان</th>
                        <th>الإجمالي</th>
                        <th>تحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($journalEntries as $entry)
                        <tr>
                            <td>#{{ $entry->id }}</td>
                            <td>{{ $entry->date }}</td>
                            <td>{{ Str::limit($entry->description, 50) }}</td>
                            <td>{{ number_format($entry->items->sum('debit'), 2) }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('dashboard.journal-entries.show', $entry->id) }}" class="btn-icon" title="عرض"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('dashboard.journal-entries.destroy', $entry->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد؟ حذف القيد عملية لا يمكن التراجع عنها.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon text-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">لا توجد قيود يومية لعرضها.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
