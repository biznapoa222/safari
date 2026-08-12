@extends('layouts.admin')
@section('title', 'Evaluation Audit Log')
@section('content')
<div class="ops-page-heading">
    <div><p class="eyebrow">Audit trail</p><h1>Evaluation Audit Log</h1></div>
    <div class="heading-actions"><a class="button button-secondary" href="{{ route('admin.evaluations.show', $quotation) }}"><i data-lucide="arrow-left"></i>Back</a></div>
</div>
@include('admin.partials.flash')

<section class="ops-panel">
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Time</th><th>User</th><th>Action</th><th>Description</th><th>Changes</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
            <tr>
                <td><small>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i') }}</small></td>
                <td>{{ $log->user_name ?: 'System' }}</td>
                <td><span class="ops-pill">{{ ucwords(str_replace('_', ' ', $log->action)) }}</span></td>
                <td>{{ $log->description }}</td>
                <td>
                    @if($log->old_values || $log->new_values)
                    <details style="font-size:0.8rem">
                        <summary>View changes</summary>
                        <pre style="background:var(--surface);padding:0.5rem;border-radius:4px;margin-top:0.25rem;max-height:100px;overflow:auto">@if($log->old_values)Old: {{ json_encode(json_decode($log->old_values), JSON_PRETTY_PRINT) }}@endif
@if($log->new_values)New: {{ json_encode(json_decode($log->new_values), JSON_PRETTY_PRINT) }}@endif</pre>
                    </details>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty-cell">No audit log entries for this evaluation.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="ops-pagination">{{ $logs->links() }}</div>
</section>
@endsection
