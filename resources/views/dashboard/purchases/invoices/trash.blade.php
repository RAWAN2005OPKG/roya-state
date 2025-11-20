@extends('layouts.container')
@section('title', 'سلة محذوفات فواتير المشتريات')

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-trash-alt"></i> سلة محذوفات فواتير المشتريات</h1>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>المورد</th>
                        <th>تاريخ الحذف</th>
                        <th class="no-print">تحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            <td>{{ $invoice->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $invoice->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="action-buttons">
                                {{-- زر الاستعادة --}}
                                <form action="{{ route('dashboard.purchases.invoices.restore', $invoice->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="استعادة"><i class="fas fa-undo"></i></button>
                                </form>

                                {{-- زر الحذف النهائي --}}
                                <form action="{{ route('dashboard.purchases.invoices.forceDelete', $invoice->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف نهائي"><i class="fas fa-times"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center" style="padding: 2rem;">لا توجد فواتير محذوفة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $invoices->links() }}</div>
    </div>
</main>
@endsection
