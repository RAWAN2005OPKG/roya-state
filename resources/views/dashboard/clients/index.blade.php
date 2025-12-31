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
        <!-- === بداية قسم البحث === -->
        <form method="GET" action="{{ route('dashboard.clients.index') }}" class="mb-8 p-4 bg-light rounded">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">بحث بالرقم التعريفي (ID)</label>
                    <input type="text" name="search_id" class="form-control" placeholder="أدخل الرقم التعريفي" value="{{ request('search_id') }}">
                </div>
                <div class="col-md-4 form-group">
                    <label class="font-weight-bold">بحث برقم الهوية</label>
                    <input type="text" name="search_id_number" class="form-control" placeholder="أدخل رقم الهوية" value="{{ request('search_id_number') }}">
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-success"> <i class="la la-search"></i> بحث</button>
                    <a href="{{ route('dashboard.clients.index') }}" class="btn btn-secondary"> <i class="la la-close"></i> إلغاء</a>
                </div>
            </div>
        </form>
        <!-- === نهاية قسم البحث === -->

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="clientsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>الجوال</th>
                        <th>الوحدات المشتراة</th>
                        <th>المستحق (ILS)</th>
                        <th>المدفوع (ILS)</th>
                        <th>المتبقي (ILS)</th>
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
                        <td>
                            @forelse($client->units as $unit)
                                <div class="mb-2 p-2 border rounded" style="border-right: 3px solid #1BC5BD !important;">
                                    <p class="mb-0 font-weight-bold text-primary">{{ $unit->unit_number }} ({{ $unit->project->name ?? 'N/A' }})</p>
                                    <small class="text-muted">
                                        السعر: {{ number_format($unit->pivot->sale_price, 2) }} {{ $unit->pivot->currency }}
                                        <span class="text-dark">(يعادل {{ number_format($unit->pivot->sale_price_ils, 2) }} ILS)</span>
                                    </small>
                                </div>
                            @empty
                                <span class="text-muted">لم يشتري وحدات.</span>
                            @endforelse
                        </td>
                        <td><span class="text-info font-weight-bold">{{ number_format($client->total_due, 2) }}</span></td>
                        <td><span class="text-success font-weight-bold">{{ number_format($client->total_paid, 2) }}</span></td>
                        <td><span class="text-danger font-weight-bold">{{ number_format($client->remaining_balance, 2) }}</span></td>
                        <td>
                            <a href="{{ route('dashboard.clients.show', $client->id) }}" class="btn btn-sm btn-icon btn-info" title="عرض"><i class="la la-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">لا يوجد عملاء مطابقون لنتائج البحث.</td></tr>
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
        // عطّل بحث DataTables المدمج لأننا نستخدم بحث الخادم
        $('#clientsTable').DataTable({
            "searching": false,
            "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"},
            "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "الكل"] ],
            "pageLength": 10
        });
    });
</script>
@endpush
