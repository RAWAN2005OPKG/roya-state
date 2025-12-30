{{-- قائمة القيود اليومية (الدفعات) --}}
@extends('layouts.container')
@section('title', 'سجل القيود اليومية (الدفعات)')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content' )
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-receipt text-success mr-2"></i> سجل القيود اليومية</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.payments.create') }}" class="btn btn-success"><i class="la la-plus"></i> تسجيل قيد جديد</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="paymentsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>الكيان</th>
                        <th>النوع</th>
                        <th>المبلغ (أصلي)</th>
                        <th>المبلغ (شيكل)</th>
                        <th>طريقة الدفع</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td>
                            @if($payment->payable)
                                <span class="badge badge-light-info">{{ class_basename($payment->payable_type) }}</span>
                                {{ $payment->payable->name ?? 'محذوف' }} ({{ $payment->payable->unique_id ?? '-' }})
                            @else
                                <span class="badge badge-light-danger">غير معروف</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $payment->type == 'in' ? 'success' : 'danger' }}">
                                {{ $payment->type == 'in' ? 'قبض' : 'صرف' }}
                            </span>
                        </td>
                        <td>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</td>
                        <td><span class="font-weight-bold">{{ number_format($payment->amount_ils, 2) }} ILS</span></td>
                        <td><span class="badge badge-light-primary">{{ $payment->method }}</span></td>
                        <td>
                            <a href="{{ route('dashboard.payments.show', $payment->id) }}" class="btn btn-sm btn-icon btn-info" title="عرض"><i class="la la-eye"></i></a>
                            {{-- ... أزرار التعديل والحذف --}}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">لا يوجد قيود يومية مسجلة.</td></tr>
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
        $('#paymentsTable').DataTable({
            "language": {"url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json"},
            "order": [[ 0, "desc" ]], // ترتيب حسب ID تنازلياً
            "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "الكل"] ],
            "pageLength": 10
        });
    });
</script>
@endpush
