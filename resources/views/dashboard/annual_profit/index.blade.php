@extends('layouts.container')
@section('title', 'تحليل الربح السنوي')

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-chart-pie mr-2"></i> تحليل الربح السنوي
        </h3>
    </div>
    <div class="card-body">
        <p class="text-muted">يعرض هذا التقرير ملخصاً للإيرادات، المصروفات، وصافي الربح لكل سنة مالية مسجلة في النظام.</p>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr class="text-uppercase">
                        <th>السنة المالية</th>
                        <th>إجمالي الإيرادات</th>
                        <th>إجمالي المصروفات</th>
                        <th>صافي الربح / الخسارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($annualData as $data)
                    <tr>
                        <td class="font-weight-bolder font-size-h6">{{ $data['year'] }}</td>
                        <td class="text-success font-weight-bold">{{ number_format($data['revenue'], 2) }}</td>
                        <td class="text-danger font-weight-bold">{{ number_format($data['expenses'], 2) }}</td>
                        <td class="font-weight-bolder font-size-h6 {{ $data['net_profit'] >= 0 ? 'text-primary' : 'text-danger' }}">
                            {{ number_format($data['net_profit'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-5 text-muted">
                            لا توجد بيانات مالية مسجلة لعرضها.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
