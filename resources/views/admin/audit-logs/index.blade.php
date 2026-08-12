@extends('layouts.admin')
@section('title', 'Audit Logs')
@section('content')
<x-admin.top-bar
    title="Audit Log"
    description="System Audit"
    :addButton="false"
    :search="false"
/>
<form class="ops-filters" method="GET">
    <select name="action" onchange="this.form.submit()">
        <option value="">All Actions</option>
        @foreach($actions as $a)<option value="{{ $a }}" @selected(request('action') === $a)>{{ ucfirst($a) }}</option>@endforeach
    </select>
    <select name="module" onchange="this.form.submit()">
        <option value="">All Modules</option>
        @foreach($modules as $m)<option value="{{ $m }}" @selected(request('module') === $m)>{{ ucfirst($m) }}</option>@endforeach
    </select>
    <button class="button button-primary">Filter</button>
</form>
<div class="table-wrap">
    <table class="ops-table">
        <thead><tr><th>User</th><th>Action</th><th>Module</th><th>Description</th><th>IP</th><th>Date/Time</th></tr></thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->user?->name ?? 'System' }}</td>
                <td><span class="status status--{{ $log->action }}">{{ ucfirst($log->action) }}</span></td>
                <td>{{ $log->module }}</td>
                <td><small>{{ $log->description }}</small></td>
                <td><small>{{ $log->ip_address ?? '-' }}</small></td>
                <td><small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">No audit logs.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="ops-pagination">{{ $logs->links() }}</div>
@endsection
