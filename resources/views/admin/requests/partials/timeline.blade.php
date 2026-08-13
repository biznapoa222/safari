@php
    $events = collect();
    foreach (($history ?? collect()) as $item) {
        $events->push([
            'title' => $item->action ?? 'History',
            'at' => $item->created_at?->diffForHumans(),
            'body' => $item->description ?? $item->notes ?? null,
        ]);
    }
    foreach (($statusLogs ?? collect()) as $item) {
        $events->push([
            'title' => 'Status: '.($item->to_status ?? $item->status ?? 'updated'),
            'at' => $item->created_at?->diffForHumans(),
            'body' => $item->notes ?? null,
        ]);
    }
@endphp

@forelse ($events as $item)
    <article class="timeline-item">
        <strong>{{ $item['title'] }}</strong>
        <small>{{ $item['at'] }}</small>
        @if (!empty($item['body']))
            <p>{{ $item['body'] }}</p>
        @endif
    </article>
@empty
    <p class="empty-inline">No timeline events yet.</p>
@endforelse
