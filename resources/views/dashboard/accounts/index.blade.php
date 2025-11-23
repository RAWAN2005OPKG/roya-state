@extends('layouts.container')
@section('title', 'دليل الحسابات')

@section('styles')
<style>
    .tree ul { padding-right: 20px; border-right: 1px solid #ddd; }
    .tree li { list-style-type: none; margin: 10px 0; position: relative; }
    .tree li::before { content: ""; position: absolute; top: 0; right: -20px; border-top: 1px solid #ddd; width: 20px; height: 20px; }
    .tree li::after { content: ""; position: absolute; top: 0; right: -20px; border-left: 1px solid #ddd; height: 100%; }
    .tree li:last-child::after { display: none; }
    .account-item { display: flex; justify-content: space-between; align-items: center; padding: 5px; border-radius: 5px; }
    .account-item:hover { background-color: #f0f0f0; }
</style>
@endsection

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-sitemap"></i> دليل الحسابات</h1>
        <div class="header-actions">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAccountModal"><i class="fas fa-plus"></i> حساب جديد</button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-container">
                <h3 class="container-title">شجرة الحسابات</h3>
                <div class="tree">
                    <ul>
                        @foreach ($accounts as $account)
                            @include('dashboard.accounts.partials.account_node', ['account' => $account])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- نافذة إضافة حساب --}}
@include('dashboard.accounts.partials.add_modal', ['allAccounts' => $allAccounts])

{{-- نافذة تعديل حساب --}}
@include('dashboard.accounts.partials.edit_modal', ['allAccounts' => $allAccounts])
@endsection

@push('scripts')
<script>
    $('#editAccountModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        modal.find('form').attr('action', button.data('action'));
        modal.find('#edit_name').val(button.data('name'));
        modal.find('#edit_code').val(button.data('code'));
        modal.find('#edit_type').val(button.data('type'));
        modal.find('#edit_parent_id').val(button.data('parent_id'));
        modal.find('#edit_is_active').val(button.data('is_active') ? '1' : '0');
    });
</script>
@endpush
