@extends('layouts.container')
@section('title', 'إدارة خالد')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="card-title">قائمة السندات (خالد)</h4>
            <div>
                <a href="{{ route('dashboard.khaled.create') }}" class="btn btn-success">إنشاء سند جديد</a>
                <a href="{{ route('dashboard.khaled.trash') }}" class="btn btn-dark">سلة المحذوفات</a>
                <a href="{{ route('dashboard.khaled.export.excel') }}" class="btn btn-info">تنزيل Excel</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('dashboard.khaled.index') }}">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="ابحث بالوصف أو رقم السند..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="limit" class="form-control" onchange="this.form.submit()">
                        <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('limit') == 20 ? 'selected' : '' }}>20</option>
                        <option value="30" {{ request('limit') == 30 ? 'selected' : '' }}>30</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">بحث</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>البيان</th>
                        <th>المبلغ</th>
                        <th>القيمة (شيكل)</th>
                        <th>المشروع</th>
                        <th>العميل/المستثمر</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($khaleds as $khaled)
                    <tr>
                        <td>{{ $khaled->id }}</td>
                        <td>{{ $khaled->voucher_date->format('Y-m-d') }}</td>
                        <td><span class="badge {{ $khaled->type == 'receipt' ? 'badge-success' : 'badge-danger' }}">{{ $khaled->type == 'receipt' ? 'قبض' : 'صرف' }}</span></td>
                        <td>{{ Str::limit($khaled->description, 30) }}</td>
                        <td>{{ number_format($khaled->amount, 2) }} {{ $khaled->currency }}</td>
                        <td>{{ number_format($khaled->amount_ils, 2) }} ILS</td>
                        <td>{{ $khaled->project->name ?? '-' }}</td>
                        <td>{{ $khaled->client->name ?? $khaled->investor->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('dashboard.khaled.show', $khaled->id) }}" class="btn btn-sm btn-info">عرض</a>
                            <a href="{{ route('dashboard.khaled.edit', $khaled->id) }}" class="btn btn-sm btn-primary">تعديل</a>
                            <form action="{{ route('dashboard.khaled.destroy', $khaled->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">لا توجد بيانات لعرضها.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $khaleds->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
