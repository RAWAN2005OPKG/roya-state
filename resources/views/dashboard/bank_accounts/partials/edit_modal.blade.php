<div class="modal fade" id="editAccountModal-{{ $account->id }}" tabindex="-1" role="dialog" aria-labelledby="editAccountModalLabel-{{ $account->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('dashboard.bank-accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editAccountModalLabel-{{ $account->id }}">تعديل الحساب: {{ $account->account_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>اسم البنك</label>
                                <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>اسم صاحب الحساب</label>
                                <input type="text" class="form-control" name="account_name" value="{{ old('account_name', $account->account_name) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>رقم الحساب</label>
                                <input type="text" class="form-control" name="account_number" value="{{ old('account_number', $account->account_number) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>رقم الآيبان (IBAN)</label>
                                <input type="text" class="form-control" name="iban" value="{{ old('iban', $account->iban) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الرصيد الحالي</label>
                                <input type="number" class="form-control" name="balance" value="{{ old('balance', $account->balance) }}" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الحالة</label>
                                <select class="form-control" name="is_active" required>
                                    <option value="1" @selected($account->is_active)>نشط</option>
                                    <option value="0" @selected(!$account->is_active)>غير نشط</option>
                                </select>
                            </div>
                        </div>
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
