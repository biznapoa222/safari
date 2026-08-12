@extends('layouts.admin')
@section('title', 'Conversations | '.$lead->name)
@section('content')
<div class="page-heading">
    <div>
        <p class="eyebrow"><a href="{{ route('admin.leads.index') }}">Leads</a> / <a href="{{ route('admin.leads.show', $lead->id) }}">{{ $lead->name }}</a></p>
        <h1>Conversations</h1>
        <p>{{ $lead->name }} · {{ $lead->email }}</p>
    </div>
    <div class="heading-actions">
        <a href="{{ route('admin.leads.show', $lead->id) }}" class="button button-ghost"><i data-lucide="arrow-left"></i>Back to lead</a>
    </div>
</div>

<section class="ops-panel">
    <div class="ops-panel-title"><h2>Conversation History</h2></div>
    <div style="padding:15px;display:grid;gap:12px;">
        @forelse($lead->conversations as $conversation)
            <div style="padding:14px;background:var(--bg-subtle);border:1px solid var(--line);border-radius:8px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <strong style="font-size:11px;">{{ $conversation->user?->name ?? 'System' }}</strong>
                    <small style="color:#7d8b84;font-size:9px;">{{ $conversation->created_at->format('M d, Y H:i') }}</small>
                </div>
                <p style="font-size:12px;line-height:1.8;margin:0;">{{ $conversation->message }}</p>
            </div>
        @empty
            <div style="text-align:center;padding:40px;color:#7d8b84;">
                <i data-lucide="message-circle" style="width:32px;margin-bottom:12px;"></i>
                <p>No conversations recorded yet.</p>
            </div>
        @endforelse
    </div>
</section>

<section class="ops-panel" style="margin-top:18px;">
    <div class="ops-panel-title"><h2>Add Note</h2></div>
    <form method="POST" action="{{ route('admin.leads.conversations.store', $lead->id) }}" style="padding:15px;">
        @csrf
        <label style="display:block;margin-bottom:10px;">
            <span style="display:block;margin-bottom:6px;font-size:7px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#6a7b73;">Message</span>
            <textarea name="message" rows="4" style="width:100%;padding:10px;border:1px solid var(--line);border-radius:6px;font-size:12px;" required></textarea>
        </label>
        <button class="button button-primary">Save note</button>
    </form>
</section>
@endsection
