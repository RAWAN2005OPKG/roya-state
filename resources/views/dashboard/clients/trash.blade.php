@extends('layouts.container')
@section('title', 'سلة محذوفات العملاء')
@section('content')
<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">سلة محذوفات العملاء</h3>
        <div class="card-toolbar">
            <a href="{{ route('dashboard.clients.index') }}" class="btn btn-primary btn-sm">العودة لقائمة العملاء</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الاسم</th>
                        <th>تاريخ الحذف</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trashedClients as $client)
                    <tr>
                        <td>{{ $client->unique_id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->deleted_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <form action="{{ route('dashboard.clients.restore', $client->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success">استعادة</button>
                            </form>
                            <form action="{{ route('dashboard.clients.forceDelete', $client->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');" style="display: inline;">
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
    </div>
</div>
@endsection
