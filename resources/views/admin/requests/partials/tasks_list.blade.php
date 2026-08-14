@forelse ($tasks as $task)
    <article class="task-item">
        <strong>{{ $task->title }}</strong>
        <small>{{ $task->priority }} · {{ $task->deadline?->format('Y-m-d') ?: 'No deadline' }}</small>
        @if ($task->description)
            <p>{{ $task->description }}</p>
        @endif
    </article>
@empty
    <p class="empty-inline">No tasks yet.</p>
@endforelse
