{{-- قائمة المستثمرين --}}
@extends('layouts.container')
@section('title', 'قائمة المستثمرين')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content' )
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake text-warning mr-2"></i> قائمة المستثمرين</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.investors.create') }}" class="btn btn-warning"><i class="la la-plus"></i> إضافة مستثمر</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="investorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>الشركة</th>
                        <th>الجوال</th>
                        <th>إجمالي الاستثمار</th>
                        <th>المصروف له</th>
                        <th>المتبقي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($investors as $investor)
                    <tr>
                        <td>{{ $investor->unique_id }}</td>
                        <td>{{ $investor->name }}</td>
                        <td>{{ $investor->company ?? '-' }}</td>
                        <td>{{ $investor->phone ?? '-' }}</td>
                        <td><span class="text-info font-weight-bold">{{ number_format($investor->total_invested, 2) }}</span></td>
                        <td><span class="text-danger font-weight-bold">{{ number_format($investor->total_paid, 2) }}</span></td>
                        <td><span class="text-success font-weight-bold">{{ number_format($investor->remaining_investment, 2) }}</span></td>
                        <td>
                            <a href="{{ route('dashboard.investors.show', $investor->id) }}" class="btn btn-sm btn-icon btn-info" title="عرض"><i class="la la-eye"></i></a>
                            {{-- ... أزرار التعديل والحذف --}}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">لا يوجد مستثمرون مسجلون.</td></tr>
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
        $('#investorsTable').DataTable({
            "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"},
            "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "الكل"] ],
            "pageLength": 10
        });
    });
</script>
@endpush
