<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-labelledby="addAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('dashboard.bank-accounts.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModalLabel">إضافة حساب بنكي جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank_name">اسم البنك</label>
                                <input type="text" class="form-control" name="bank_name" value="{{ old('bank_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="account_name">اسم صاحب الحساب</label>
                                <input type="text" class="form-control" name="account_name" value="{{ old('account_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="account_number">رقم الحساب</label>
                                <input type="text" class="form-control" name="account_number" value="{{ old('account_number') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iban">رقم الآيبان (IBAN)</label>
                                <input type="text" class="form-control" name="iban" value="{{ old('iban') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="balance">الرصيد الافتتاحي</label>
                                <input type="number" class="form-control" name="balance" value="{{ old('balance', 0) }}" step="0.01" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ الحساب</button>
                </div>
            </form>
        </div>
    </div>
</div>
