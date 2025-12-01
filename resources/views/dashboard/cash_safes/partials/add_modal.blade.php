{{--
    هذا الملف يحتوي على النافذة المنبثقة (Modal) لتعديل بيانات الخزينة.
    يتم استدعاؤه لكل خزينة في جدول العرض.
--}}
<div class="modal fade" id="editSafeModal-{{ $safe->id }}" tabindex="-1" role="dialog" aria-labelledby="editSafeModalLabel-{{ $safe->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSafeModalLabel-{{ $safe->id }}">تعديل الخزينة: {{ $safe->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dashboard.cash-safes.update', $safe->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    {{-- حقل اسم الخزينة --}}
                    <div class="form-group">
                        <label for="name-{{ $safe->id }}">اسم الخزينة</label>
                        <input type="text" id="name-{{ $safe->id }}" name="name" class="form-control" value="{{ old('name', $safe->name) }}" required>
                    </div>

                    {{-- حقل الرصيد الافتتاحي (للقراءة فقط عند التعديل) --}}
                    <div class="form-group">
                        <label for="initial_balance-{{ $safe->id }}">الرصيد الافتتاحي</label>
                        <input type="number" id="initial_balance-{{ $safe->id }}" name="initial_balance" class="form-control" value="{{ old('initial_balance', $safe->initial_balance) }}" readonly>
                        <small class="form-text text-muted">لا يمكن تعديل الرصيد الافتتاحي. يمكنك إجراء قيود يومية لتسوية الرصيد.</small>
                    </div>

                    {{-- حقل حالة الخزينة --}}
                    <div class="form-group">
                        <label for="is_active-{{ $safe->id }}">الحالة</label>
                        <select id="is_active-{{ $safe->id }}" name="is_active" class="form-control" required>
                            <option value="1" {{ $safe->is_active ? 'selected' : '' }}>نشطة</option>
                            <option value="0" {{ !$safe->is_active ? 'selected' : '' }}>غير نشطة</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>
</div>
