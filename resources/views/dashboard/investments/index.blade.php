@extends('layouts.container')

@section('title', 'قائمة الاستثمارات')

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
<div class="container">
    <h2 class="text-center">قائمة الاستثمارات</h2>

    <a href="{{ route('dashboard.investments.create') }}" class="btn btn-primary mb-3">إضافة استثمار جديد</a>

    @if($investments->count() > 0)
    <table id="investmentsTable" class="table table-bordered text-center align-middle shadow-sm">
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
                <td>{{ $investment->payment_method }}</td>
                <td>{{ $investment->cash_receiver ?? '-' }}</td>
                <td>{{ $investment->sender_bank ?? '-' }}</td>
                <td>{{ $investment->receiver_bank ?? '-' }}</td>
                <td>
                    @if($investment->status == 'active')
                        <span class="badge bg-success">نشط</span>
                    @elseif($investment->status == 'completed')
                        <span class="badge bg-primary">مكتمل</span>
                    @else
                        <span class="badge bg-secondary">ملغي</span>
                    @endif
                </td>
                <td>{{ $investment->date ? $investment->date->format('Y-m-d') : '-' }}</td>
                <td>
                    <a href="{{ route('dashboard.investments.edit', $investment->id) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                    <form action="{{ route('dashboard.investments.destroy', $investment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
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
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'print'],
        language: {
            search: "🔍 بحث:",
            paginate: { previous: "السابق", next: "التالي" },
            info: "عرض _START_ إلى _END_ من أصل _TOTAL_ استثمار",
            infoEmpty: "لا توجد بيانات متاحة",
            zeroRecords: "لا توجد نتائج مطابقة",
            lengthMenu: "عرض _MENU_ صفوف"
        },
        responsive: true,
        pageLength: 10
    });
});
</script>
@endsection
