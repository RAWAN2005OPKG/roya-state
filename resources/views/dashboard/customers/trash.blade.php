@extends('layouts.container')
@section('title', 'سلة محذوفات العملاء')

@section('content')
<main class="main-content" style="padding-top: 40px;">
    <div class="card card-custom" style="max-width: 1200px; margin: auto;">
        <div class="card-header">
            <h3 class="card-title">سلة محذوفات العملاء</h3>
            <div class="card-toolbar">
                <a href="{{ route('dashboard.customers.index') }}" class="btn btn-secondary">العودة لقائمة العملاء</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>المشروع</th>
                            <th>تاريخ الحذف</th>
                            <th>تحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->project }}</td>
                                <td>{{ $customer->deleted_at->format('Y-m-d H:i') }}</td>
                                <td nowrap="nowrap">
                                    <form action="{{ route('dashboard.customers.trash.restore', $customer->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success">استعادة</button>
                                    </form>
                                    <form action="{{ route('dashboard.customers.trash.forceDelete', $customer->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('تحذير! سيتم حذف هذا العنصر نهائياً. هل أنت متأكد؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">حذف نهائي</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">سلة المحذوفات فارغة.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</main>
@endsection

