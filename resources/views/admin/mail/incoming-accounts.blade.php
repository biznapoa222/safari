@extends('layouts.admin')
@section('title', 'IMAP Accounts · Shishi Footsteps ERP')
@section('content')
<x-admin.top-bar title="IMAP Accounts" description="Connect mailboxes to import enquiries into Requests" :search="false" :addButton="false">
    <x-slot:actions>
        <form method="POST" action="{{ route('admin.mail.incoming.fetch') }}" style="display:inline">
            @csrf
            <button type="submit" class="button button-primary"><i data-lucide="refresh-cw"></i>Fetch now</button>
        </form>
        <a href="{{ route('admin.mail.inbox') }}" class="button button-secondary"><i data-lucide="inbox"></i>Open inbox</a>
    </x-slot:actions>
</x-admin.top-bar>
@include('admin.partials.flash')

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Connected accounts</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Host</th>
                        <th>Username</th>
                        <th>Folder</th>
                        <th>Last fetched</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td><strong>{{ $account->label }}</strong><br><small style="color:var(--text-muted)">{{ strtoupper($account->protocol) }} · {{ $account->encryption }} · port {{ $account->port }}</small></td>
                        <td style="font-size:9px">{{ $account->host }}</td>
                        <td style="font-size:9px">{{ $account->username }}</td>
                        <td style="font-size:9px">{{ $account->folder }}</td>
                        <td style="font-size:9px">{{ $account->last_fetched_at ? $account->last_fetched_at->diffForHumans() : '—' }}<br><small style="color:var(--text-muted)">uid {{ $account->last_uid }}</small></td>
                        <td>
                            @if($account->is_active)
                                <span class="ops-pill" style="background:#f0fdf4;color:#16a34a">Active</span>
                            @else
                                <span class="ops-pill" style="background:#f3f4f6;color:#6b7280">Paused</span>
                            @endif
                            @if($account->error)
                                <small style="color:#dc2626;display:block;margin-top:4px;max-width:240px;white-space:normal">{{ $account->error }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="ops-actions">
                                <form method="POST" action="{{ route('admin.mail.incoming.destroy', $account) }}" onsubmit="return confirm('Delete this IMAP account?')">
                                    @csrf @method('DELETE')
                                    <button title="Delete"><i data-lucide="trash-2"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--text-muted)">No IMAP accounts yet. Add one on the right.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Add IMAP account</h2></div>
        <div style="padding:16px">
            <form method="POST" action="{{ route('admin.mail.incoming.store') }}">
                @csrf
                <div style="display:grid;gap:8px">
                    <input type="text" name="label" required placeholder="Label (e.g. info@shishifootsteps.com)" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <select name="protocol" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                            <option value="imap">IMAP</option>
                            <option value="pop3">POP3</option>
                        </select>
                        <select name="encryption" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                            <option value="none">None</option>
                        </select>
                    </div>
                    <input type="text" name="host" required placeholder="imap.example.com" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                        <input type="number" name="port" value="993" required style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <input type="text" name="folder" value="INBOX" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <input type="text" name="username" required placeholder="username" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <input type="password" name="password" required placeholder="Password" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <select name="assigned_consultant_id" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                        <option value="">Default assignee (no one)</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:9px">
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="is_active" value="1" checked>Active</label>
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="mark_seen" value="1" checked>Mark as seen</label>
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="delete_after_fetch" value="1">Delete after fetch</label>
                        <label style="display:flex;align-items:center;gap:6px"><input type="checkbox" name="auto_create_request" value="1">Auto-create request</label>
                    </div>
                    <button type="submit" class="button button-primary" style="height:38px">Save account</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
