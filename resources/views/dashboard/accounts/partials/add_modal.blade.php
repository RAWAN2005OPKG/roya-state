{{-- نافذة منبثقة لإضافة حساب جديد --}}
<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('dashboard.accounts.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">إضافة حساب جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>الاسم</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label>الكود</label><input type="text" name="code" class="form-control" required></div>
                    <div class="form-group"><label>النوع</label><select name="type" class="form-control" required><option value="asset">أصل</option><option value="liability">التزام</option><option value="equity">حقوق ملكية</option><option value="revenue">إيراد</option><option value="expense">مصروف</option></select></div>
                    <div class="form-group"><label>الحساب الأب</label><select name="parent_id" class="form-control"><option value="">-- حساب رئيسي --</option>@foreach($allAccounts as $acc)<option value="{{ $acc->id }}">{{ $acc->name }}</option>@endforeach</select></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
