@extends('layouts.container')

@section('title', 'قائمة الاستثمارات')

@section('styles')
<style>
    .investments-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 16px;
        max-width: 1400px;
        margin: 40px auto;
    }

    .investments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .investments-title {
        font-size: 1.8rem;
        color: #4f46e5;
        margin: 0;
    }

    .invest-btn {
        padding: 8px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        cursor: pointer;
    }

    .invest-btn-primary { background-color: #4f46e5; color: #fff; }
    .invest-btn-primary:hover { background-color: #4338ca; }

    .invest-btn-outline-primary { color: #4f46e5; border: 2px solid #4f46e5; background: transparent; }
    .invest-btn-outline-primary:hover { background: #4f46e5; color: #fff; }

    .invest-btn-outline-danger { color: #dc3545; border: 2px solid #dc3545; background: transparent; }
    .invest-btn-outline-danger:hover { background: #dc3545; color: #fff; }

    .investments-table { width: 100%; border-collapse: collapse; text-align: center; }
    .investments-table th, .investments-table td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; }
    .investments-table th { background-color: #f9fafb; color: #111827; font-weight: 600; }

    .badge-success { background-color: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 20px; font-weight: 500; }
    .badge-primary { background-color: #dbeafe; color: #1e40af; padding: 6px 12px; border-radius: 20px; font-weight: 500; }
    .badge-secondary { background-color: #e5e7eb; color: #374151; padding: 6px 12px; border-radius: 20px; font-weight: 500; }

    /* ===== DataTables ===== */
    .dt-buttons {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dt-button {
        background-color: #4f46e5 !important;
        color: #fff !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 15px !important;
        font-weight: 600 !important;
        cursor: pointer !important;
    }

    .dt-button:hover {
        background-color: #4338ca !important;
    }

    .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px 10px;
        outline: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        padding: 6px 12px !important;
        margin: 2px !important;
        color: #4f46e5 !important;
        border: 1px solid #e5e7eb !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #4f46e5 !important;
        color: white !important;
        border: 1px solid #4f46e5 !important;
    }

    .dataTables_length select {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 4px 8px;
    }

    @media (max-width: 768px) {
        .investments-header { flex-direction: column; gap: 10px; text-align: center; }
        .invest-btn { font-size: 0.85rem; padding: 8px 12px; }
        .investments-table th, .investments-table td { font-size: 0.8rem; padding: 8px; }
    }
</style>
@endsection

@section('content')
<div class="investments-container">
    <div class="investments-header">
        <h2 class="investments-title">قائمة الاستثمارات</h2>
        <a href="{{ route('dashboard.investments.create') }}" class="invest-btn invest-btn-primary">إضافة استثمار جديد</a>
    </div>

    @if($investments->count() > 0)
    <table id="investmentsTable" class="investments-table display nowrap">
        <thead>
            <tr>
                <th>اسم المستثمر</th>
                <th>اسم المشروع</th>
                <th>المبلغ</th>
                <th>طريقة الدفع</th>
                <th>المستلم نقدًا</th>
                <th>بنك المرسل</th>
                <th>بنك المستلم</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($investments as $investment)
            <tr>
                <td>{{ $investment->investor->name ?? 'غير محدد' }}</td>
                <td>{{ $investment->project->name ?? 'غير محدد' }}</td>
                <td>{{ number_format($investment->amount, 2) }}</td>
                <td>{{ $investment->payment_method ?? '-' }}</td>
                <td>{{ $investment->cash_receiver ?? '-' }}</td>
                <td>{{ $investment->sender_bank ?? '-' }}</td>
                <td>{{ $investment->receiver_bank ?? '-' }}</td>
                <td>
                    @if($investment->status == 'active')
                        <span class="badge-success">نشط</span>
                    @elseif($investment->status == 'completed')
                        <span class="badge-primary">مكتمل</span>
                    @else
                        <span class="badge-secondary">ملغي</span>
                    @endif
                </td>
                <td>{{ $investment->date ? $investment->date->format('Y-m-d') : '-' }}</td>
                <td>
                    <a href="{{ route('dashboard.investments.edit', $investment->id) }}" class="invest-btn invest-btn-outline-primary btn-sm">تعديل</a>
                    <form action="{{ route('dashboard.investments.destroy', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="invest-btn invest-btn-outline-danger btn-sm">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="text-muted text-center">لا توجد استثمارات بعد.</p>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    $('#investmentsTable').DataTable({
        dom: '<"top"lfB>rt<"bottom"ip>',
        buttons: [
            { extend: 'excelHtml5', text: '💾 تصدير إلى Excel', className: 'dt-button' },
            { extend: 'print', text: '🖨️ طباعة', className: 'dt-button' }
        ],
        language: {
            search: "🔍 بحث:",
            lengthMenu: "عرض _MENU_ صفوف",
            info: "عرض _START_ إلى _END_ من أصل _TOTAL_ استثمار",
            infoEmpty: "لا توجد بيانات متاحة",
            zeroRecords: "لا توجد نتائج مطابقة",
            paginate: { previous: "السابق", next: "التالي" }
        },
        responsive: true,
        pageLength: 10
    });
});
</script>
@endsection
