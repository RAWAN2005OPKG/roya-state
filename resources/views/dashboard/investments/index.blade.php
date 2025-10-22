@extends('layouts.container')

@section('title', 'قائمة الاستثمارات')

@section('styles')
<style>
/* ====== النمط العام للصفحة ====== */
body {
    font-family: 'Cairo', sans-serif;
    background-color: #f9fafb;
    color: #1f2937;
    margin: 0;
    padding: 0;
}

.main-content {
    background-color: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 25px;
    margin-top: 30px;
}

/* ====== العنوان ====== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.page-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #4f46e5;
    letter-spacing: 0.3px;
}

/* ====== الأزرار ====== */
.btn {
    border-radius: 10px;
    padding: 10px 18px;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.25s ease;
}

.btn-primary {
    background-color: #4f46e5;
    border: none;
    color: #fff;
    box-shadow: 0 3px 6px rgba(79, 70, 229, 0.2);
}
.btn-primary:hover {
    background-color: #4338ca;
    transform: translateY(-2px);
}

.btn-outline-primary {
    color: #4f46e5;
    border: 2px solid #4f46e5;
}
.btn-outline-primary:hover {
    background-color: #4f46e5;
    color: #fff;
}

.btn-outline-danger {
    color: #dc3545;
    border: 2px solid #dc3545;
}
.btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff;
}

/* ====== الجدول ====== */
.table {
    width: 100%;
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
}

.table th {
    background: linear-gradient(135deg, #4f46e5, #6d28d9);
    color: #fff;
    text-align: center;
    padding: 14px;
    font-weight: 600;
    font-size: 0.95rem;
}

.table td {
    text-align: center;
    padding: 12px;
    font-size: 0.9rem;
    color: #1f2937;
    vertical-align: middle;
}

.table tr:nth-child(even) {
    background-color: #f3f4f6;
}
.table tr:hover {
    background-color: #eef2ff;
    transition: background-color 0.2s ease;
}

/* ====== الشارات (الحالة مثلاً) ====== */
.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}
.badge-success {
    background-color: #d1fae5;
    color: #065f46;
}
.badge-warning {
    background-color: #fef3c7;
    color: #92400e;
}
.badge-danger {
    background-color: #fee2e2;
    color: #b91c1c;
}

/* ====== تحسين على الشاشات الصغيرة ====== */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    .btn {
        font-size: 0.85rem;
        padding: 8px 12px;
    }
    .table th, .table td {
        font-size: 0.8rem;
        padding: 8px;
    }
}
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
                <td>{{ $investment->investor->name ?? 'غير محدد' }}</td> <!-- إضافة نص افتراضي إذا كان فارغًا -->
                <td>{{ $investment->project->name ?? 'غير محدد' }}</td> <!-- إضافة نص افتراضي وتأكيد العلاقة -->
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
        buttons: [
            'excelHtml5',
            'print'
        ],
        language: {
            search: "بحث:",
            paginate: {
                previous: "السابق",
                next: "التالي"
            },
            info: "إظهار _START_ إلى _END_ من أصل _TOTAL_ استثمار",
            infoEmpty: "لا توجد بيانات متاحة",
            zeroRecords: "لا توجد نتائج مطابقة"
        },
        responsive: true, // إضافة responsive للجدول
        pageLength: 10 // عدد الصفوف الافتراضي
    });
});
</script>
@endsection
