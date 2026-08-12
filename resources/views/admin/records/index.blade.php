@extends('layouts.admin')
@section('title', $title)
@section('content')

<x-admin.top-bar
    :title="$title"
    description="Operational register"
    addLabel="Add Record"
    addOnclick="openModal('add-modal')"
    :search="false"
/>

@include('admin.partials.flash')

@if($records->count())
<div class="table-wrap">
    <table class="ops-table">
        <thead>
            <tr>
                <th>Record</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>
                    <strong>{{ $record->title }}</strong>
                    @if($record->reference || $record->notes)
                    <small>{{ $record->reference ? $record->reference : '' }}{{ $record->reference && $record->notes ? ' · ' : '' }}{{ $record->notes ? \Illuminate\Support\Str::limit($record->notes, 60) : '' }}</small>
                    @endif
                </td>
                <td>{{ $record->effective_date ?: '—' }}</td>
                <td>{{ $record->amount ? number_format($record->amount, 2) : '—' }}</td>
                <td><span class="status status--{{ $record->status }}">{{ ucfirst($record->status) }}</span></td>
                <td>
                    <div class="ops-actions">
                        <button onclick="openEditModal({{ $record->id }})" title="Edit"><i data-lucide="square-pen"></i></button>
                        <form method="POST" action="{{ route('admin.records.destroy', [$slug, $record->id]) }}" onsubmit="return confirm('Delete this record?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button title="Delete"><i data-lucide="trash-2"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $records->links() }}</div>
@else
<x-admin.empty-state
    title="No records yet."
    description="Create the first {{ strtolower($title) }} record."
    action="openModal('add-modal')"
    addLabel="Add First Record"
/>
@endif

{{-- Add Modal --}}
<x-admin.modal id="add-modal" title="Add Record">
    <form method="POST" action="{{ route('admin.records.store', $slug) }}">
        @csrf
        <div class="modal-form-grid">
            <label class="span-2">Title<input name="title" required></label>
            <label>Reference<input name="reference"></label>
            <label>Status
                <select name="status">
                    <option>active</option><option>pending</option><option>confirmed</option><option>completed</option><option>cancelled</option>
                </select>
            </label>
            <label>Effective date<input type="date" name="effective_date"></label>
            <label>Amount<input type="number" step="0.01" name="amount"></label>
            <label class="span-2">Notes<textarea name="notes" rows="4"></textarea></label>
        </div>
        <div class="modal-form-footer">
            <button type="button" class="button button-secondary" onclick="closeModal('add-modal')">Cancel</button>
            <button class="button button-primary"><i data-lucide="save"></i>Create record</button>
        </div>
    </form>
</x-admin.modal>

{{-- Edit Modals --}}
@foreach($records as $record)
<x-admin.modal id="edit-modal-{{ $record->id }}" title="Edit: {{ $record->title }}" :open="false">
    <form method="POST" action="{{ route('admin.records.update', [$slug, $record->id]) }}">
        @csrf @method('PUT')
        <div class="modal-form-grid">
            <label class="span-2">Title<input name="title" value="{{ $record->title }}" required></label>
            <label>Reference<input name="reference" value="{{ $record->reference }}"></label>
            <label>Status
                <select name="status">
                    @foreach(['active','pending','confirmed','completed','cancelled'] as $status)
                    <option @selected($record->status === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
            <label>Effective date<input type="date" name="effective_date" value="{{ $record->effective_date }}"></label>
            <label>Amount<input type="number" step="0.01" name="amount" value="{{ $record->amount }}"></label>
            <label class="span-2">Notes<textarea name="notes" rows="4">{{ $record->notes }}</textarea></label>
        </div>
        <div class="modal-form-footer">
            <button type="button" class="button button-secondary" onclick="closeModal('edit-modal-{{ $record->id }}')">Cancel</button>
            <button class="button button-primary"><i data-lucide="save"></i>Save changes</button>
        </div>
    </form>
    <form method="POST" action="{{ route('admin.records.destroy', [$slug, $record->id]) }}" onsubmit="return confirm('Delete this record?')" style="margin-top:0.75rem;">
        @csrf @method('DELETE')
        <button class="button button-danger" style="width:100%;"><i data-lucide="trash-2"></i>Delete record</button>
    </form>
</x-admin.modal>
@endforeach

@push('scripts')
<script>
function openEditModal(id) {
    var el = document.getElementById('edit-modal-' + id);
    if (el) el.style.display = 'flex';
}
</script>
<script>
document.querySelector('.button-primary[href]')?.addEventListener('click', function(e) {
    if(this.getAttribute('onclick')) return;
    var href = this.getAttribute('href');
    if(href && href === '#') {
        e.preventDefault();
        openModal('add-modal');
    }
});
</script>
@endpush

@push('styles')
<style>
.ops-actions { display: flex; gap: 0.25rem; align-items: center; }
.ops-actions button, .ops-actions a { padding: 0.25rem; border: none; background: none; cursor: pointer; color: #6b6b6b; }
.ops-actions button:hover, .ops-actions a:hover { color: #234A36; }
.modal-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.modal-form-grid label { display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.85rem; font-weight: 500; }
.modal-form-grid input, .modal-form-grid select, .modal-form-grid textarea { padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: 0.375rem; font-size: 0.9rem; }
.modal-form-grid .span-2 { grid-column: span 2; }
.modal-form-footer { display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border); }
.button-danger { background: #ef4444; color: #fff; }
.button-danger:hover { background: #dc2626; }
</style>
@endpush
@endsection
