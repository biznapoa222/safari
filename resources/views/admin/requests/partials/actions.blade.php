<div class="ops-actions">
    <a href="{{ route('admin.requests.show', $record) }}" class="row-action" title="View"><i data-lucide="eye"></i></a>
    <a href="{{ route('admin.requests.edit', $record) }}" class="row-action" title="Edit"><i data-lucide="square-pen"></i></a>
    @if ($canConvert ?? false)
        <form method="POST" action="{{ route('admin.requests.convert-to-quote', $record) }}">
            @csrf
            <button class="row-action" title="Convert to quotation"><i data-lucide="file-plus"></i></button>
        </form>
    @endif
</div>
