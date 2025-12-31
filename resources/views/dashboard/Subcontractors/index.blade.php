@extends('layouts.container')
@section('title', 'قائمة المقاولين والموردين')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content' )
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-hard-hat text-dark mr-2"></i> قائمة المقاولين والموردين</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.subcontractors.create') }}" class="btn btn-dark"><i class="la la-plus"></i> إضافة مقاول/مورد</a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.subcontractors.index') }}" class="mb-8 p-4 bg-light rounded">
            <div class="row">
                <div class="col-md-4 form-group"><label class="font-weight-bold">بحث بالرقم التعريفي (ID)</label><input type="text" name="search_id" class="form-control" placeholder="أدخل الرقم التعريفي" value="{{ request('search_id') }}"></div>
                <div class="col-md-4 form-group"><label class="font-weight-bold">بحث برقم الهوية/الشركة</label><input type="text" name="search_id_number" class="form-control" placeholder="أدخل رقم الهوية" value="{{ request('search_id_number') }}"></div>
                <div class="col-md-4 align-self-end"><button type="submit" class="btn btn-success"><i class="la la-search"></i> بحث</button><a href="{{ route('dashboard.subcontractors.index') }}" class="btn btn-secondary"><i class="la la-close"></i> إلغاء</a></div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="subcontractorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>التخصص</th>
                        <th>رقم الهوية</th>
                        <th>الجوال</th>
                        <th>إجمالي العقود (ILS)</th>
                        <th>المدفوع له (ILS)</th>
                        <th>الرصيد (ILS)</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subcontractors as $subcontractor)
                    <tr>
                        <td>{{ $subcontractor->unique_id }}</td>
                        <td>{{ $subcontractor->name }}</td>
                        <td>{{ $subcontractor->specialization }}</td>
                        <td>{{ $subcontractor->id_number ?? '-' }}</td>
                        <td>{{ $subcontractor->phone ?? '-' }}</td>
                        <td><span class="text-info font-weight-bold">{{ number_format($subcontractor->total_contracts_value, 2) }}</span></td>
                        <td><span class="text-success font-weight-bold">{{ number_format($subcontractor->total_paid, 2) }}</span></td>
                        <td><span class="text-danger font-weight-bold">{{ number_format($subcontractor->remaining_balance, 2) }}</span></td>
                        <td>
                            {{-- <a href="{{ route('dashboard.subcontractors.show', $subcontractor->id) }}" class="btn btn-sm btn-icon btn-info" title="عرض"><i class="la la-eye"></i></a> --}}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">لا يوجد مقاولون مطابقون لنتائج البحث.</td></tr>
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
            "searching": false,
            "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"},
        });
    });
</script>
@endpush
