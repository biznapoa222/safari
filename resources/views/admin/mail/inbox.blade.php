@extends('layouts.admin')
@section('title', 'Inbox · Shishi Footsteps ERP')
@section('content')
<x-admin.top-bar title="Inbox" description="Convert incoming emails into Requests or Leads" :search="false" :addButton="false">
    <x-slot:actions>
        <form method="POST" action="{{ route('admin.mail.incoming.fetch') }}" style="display:inline">
            @csrf
            <button type="submit" class="button button-primary"><i data-lucide="refresh-cw"></i>Fetch now</button>
        </form>
        <a href="{{ route('admin.mail.incoming.accounts') }}" class="button button-secondary"><i data-lucide="settings"></i>IMAP accounts</a>
    </x-slot:actions>
</x-admin.top-bar>
@include('admin.partials.flash')

<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
    @php
        $tabs = ['new' => 'New', 'converted_to_request' => 'Converted to request', 'converted_to_lead' => 'Converted to lead', 'ignored' => 'Ignored', 'all' => 'All'];
    @endphp
    @foreach($tabs as $val => $label)
        <a href="{{ route('admin.mail.inbox', ['status' => $val]) }}" class="button @if($status === $val) button-primary @else button-secondary @endif" style="font-size:9px">{{ $label }}</a>
    @endforeach
</div>

<section class="ops-panel">
    <div class="table-wrap">
        <table class="ops-table">
            <thead>
                <tr>
                    <th>Received</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Account</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emails as $email)
                <tr>
                    <td style="font-size:8px">{{ $email->received_at ? $email->received_at->format('d M Y H:i') : '—' }}</td>
                    <td><strong>{{ $email->from_name ?: $email->from_email }}</strong><br><small style="color:var(--text-muted)">{{ $email->from_email }}</small></td>
                    <td><a href="{{ route('admin.mail.inbox.show', $email) }}" style="color:var(--primary);font-weight:600">{{ $email->subject ?: '(no subject)' }}</a></td>
                    <td style="font-size:9px">{{ $email->account?->label ?? '—' }}</td>
                    <td>
                        @php $colors = ['new' => '#2563eb', 'converted_to_request' => '#16a34a', 'converted_to_lead' => '#0d9488', 'ignored' => '#6b7280', 'failed' => '#dc2626']; @endphp
                        <span class="ops-pill" style="color:{{ $colors[$email->status] ?? '#3a3530' }}">{{ ucfirst(str_replace('_', ' ', $email->status)) }}</span>
                    </td>
                    <td>
                        <div class="ops-actions">
                            <a href="{{ route('admin.mail.inbox.show', $email) }}" title="Open"><i data-lucide="eye"></i></a>
                            @if($email->isConvertable())
                            <form method="POST" action="{{ route('admin.mail.inbox.convert', $email) }}" style="display:inline">
                                @csrf
                                <input type="hidden" name="as" value="request">
                                <button title="Convert to request" onclick="return confirm('Convert to request?')"><i data-lucide="file-plus"></i></button>
                            </form>
                            @endif
                            @if($email->isConvertable())
                            <form method="POST" action="{{ route('admin.mail.inbox.ignore', $email) }}" style="display:inline">
                                @csrf
                                <button title="Ignore" onclick="return confirm('Ignore this email?')"><i data-lucide="eye-off"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted)">No emails for this filter. Click "Fetch now" to pull new messages.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="ops-pagination">{{ $emails->links() }}</div>
</section>
@endsection
