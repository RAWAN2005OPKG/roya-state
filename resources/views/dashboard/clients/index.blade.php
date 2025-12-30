{{-- قائمة العملاء --}}
@extends('layouts.container')
@section('title', 'قائمة العملاء')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content' )
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users text-primary mr-2"></i> قائمة العملاء</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.clients.create') }}" class="btn btn-primary"><i class="la la-plus"></i> إضافة عميل</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="clientsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>الجوال</th>
                        <th>المبلغ المستحق</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->unique_id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->id_number ?? '-' }}</td>
                        <td>{{ $client->phone ?? '-' }}</td>
                        <td><span class="text-info font-weight-bold">{{ number_format($client->total_due, 2) }}</span></td>
                        <td><span class="text-success font-weight-bold">{{ number_format($client->total_paid, 2) }}</span></td>
                        <td><span class="text-danger font-weight-bold">{{ number_format($client->remaining_balance, 2) }}</span></td>
                        <td>
                            <a href="{{ route('dashboard.clients.show', $client->id) }}" class="btn btn-sm btn-icon btn-info" title="عرض"><i class="la la-eye"></i></a>
                            {{-- ... أزرار التعديل والحذف --}}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">لا يوجد عملاء مسجلون.</td></tr>
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
        $('#clientsTable').DataTable({
            "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"},
            "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "الكل"] ],
            "pageLength": 10
        });
    });
</script>
@endpush
