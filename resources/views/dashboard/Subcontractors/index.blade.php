{{-- قائمة المقاولين والموردين --}}
@extends('layouts.container')
@section('title', 'قائمة المقاولين والموردين')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content' )
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-hard-hat text-danger mr-2"></i> قائمة المقاولين والموردين</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.subcontractors.create') }}" class="btn btn-danger"><i class="la la-plus"></i> إضافة مقاول/مورد</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="subcontractorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>اسم المقاول/الشركة</th>
                        <th>شخص الاتصال</th>
                        <th>رقم السجل</th>
                        <th>المستحق له</th>
                        <th>المدفوع له</th>
                        <th>المتبقي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcontractors as $subcontractor)
                    <tr>
                        <td>{{ $subcontractor->unique_id }}</td>
                        <td>{{ $subcontractor->name }}</td>
                        <td>{{ $subcontractor->contact_person ?? '-' }}</td>
                        <td>{{ $subcontractor->id_number ?? '-' }}</td>
                        <td><span class="text-info font-weight-bold">{{ number_format($subcontractor->total_due, 2) }}</span></td>
                        <td><span class="text-danger font-weight-bold">{{ number_format($subcontractor->total_paid, 2) }}</span></td>
                        <td><span class="text-success font-weight-bold">{{ number_format($subcontractor->remaining_balance, 2) }}</span></td>
                        <td>
                            <a href="{{ route('dashboard.subcontractors.show', $subcontractor->id) }}" class="btn btn-sm btn-icon btn-info" title="عرض"><i class="la la-eye"></i></a>
                            {{-- ... أزرار التعديل والحذف --}}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">لا يوجد مقاولون/موردون مسجلون.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document ).ready(function() {
        $('#subcontractorsTable').DataTable({
            "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"},
            "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "الكل"] ],
            "pageLength": 10
        });
    });
</script>
@endpush
