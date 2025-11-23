{{-- هذا الملف مسؤول عن عرض كل حساب وأبنائه بشكل متكرر --}}
<li>
    <div class="account-item">
        <span>
            <strong>{{ $account->name }}</strong> ({{ $account->code }}) - <span class="text-muted">{{ $account->type }}</span>
        </span>
        <span class="action-buttons">
            <button type="button" class="btn-icon" data-toggle="modal" data-target="#editAccountModal"
                data-action="{{ route('dashboard.accounts.update', $account->id) }}"
                data-name="{{ $account->name }}"
                data-code="{{ $account->code }}"
                data-type="{{ $account->type }}"
                data-parent_id="{{ $account->parent_id }}"
                data-is_active="{{ $account->is_active }}">
                <i class="fas fa-edit"></i>
            </button>
        </span>
    </div>

    {{-- إذا كان للحساب أبناء، قم بعرضهم --}}
    @if ($account->children && $account->children->isNotEmpty())
        <ul>
            @foreach ($account->children as $child)
                @include('dashboard.accounts.partials.account_node', ['account' => $child])
            @endforeach
        </ul>
    @endif
</li>
