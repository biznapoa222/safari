@forelse ($notes as $note)
    <article class="note-item">
        <strong>{{ $note->user?->name ?? 'System' }}</strong>
        <small>{{ $note->created_at?->diffForHumans() }}</small>
        <p>{{ $note->note }}</p>
    </article>
@empty
    <p class="empty-inline">No notes yet.</p>
@endforelse
