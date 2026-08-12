@extends('layouts.admin')
@section('title', $email->subject ?: 'Email · Shishi Footsteps ERP')
@section('content')
<x-admin.top-bar :title="$email->subject ?: '(no subject)'" :description="$email->from_email" :search="false" :addButton="false">
    <x-slot:actions>
        <a href="{{ route('admin.mail.inbox') }}" class="button button-secondary"><i data-lucide="arrow-left"></i>Back to inbox</a>
    </x-slot:actions>
</x-admin.top-bar>
@include('admin.partials.flash')

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Message</h2></div>
        <div style="padding:16px;font-size:11px;line-height:1.6">
            <p><strong>From:</strong> {{ $email->from_name }} &lt;{{ $email->from_email }}&gt;</p>
            <p><strong>To:</strong> {{ $email->to_email }}</p>
            <p><strong>Received:</strong> {{ $email->received_at?->toDateTimeString() }}</p>
            <p><strong>Account:</strong> {{ $email->account?->label }}</p>
            <hr>
            @if($email->body_html)
                <iframe sandbox srcdoc="{{ $email->body_html }}" style="width:100%;min-height:420px;border:1px solid #ede8df;border-radius:8px;background:#fff"></iframe>
                <details style="margin-top:8px"><summary>Show plain text</summary>
                    <pre style="white-space:pre-wrap;background:#faf6ec;padding:12px;border-radius:8px;margin-top:8px">{{ $email->body_text }}</pre>
                </details>
            @else
                <pre style="white-space:pre-wrap;background:#faf6ec;padding:12px;border-radius:8px">{{ $email->body_text }}</pre>
            @endif
        </div>
    </section>

    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Convert</h2></div>
        <div style="padding:16px">
            <p style="font-size:9px;margin-bottom:8px;color:var(--text-muted)">Choose where this email should land in the CRM.</p>
            @if($email->isConvertable())
                <form method="POST" action="{{ route('admin.mail.inbox.convert', $email) }}" style="display:flex;flex-direction:column;gap:8px">
                    @csrf
                    <label style="display:flex;gap:8px;align-items:center;font-size:10px"><input type="radio" name="as" value="request" checked> Create as <strong>Request</strong></label>
                    <label style="display:flex;gap:8px;align-items:center;font-size:10px"><input type="radio" name="as" value="lead"> Create as <strong>Lead</strong></label>
                    <button type="submit" class="button button-primary" style="height:38px">Convert</button>
                </form>
                <hr>
                <form method="POST" action="{{ route('admin.mail.inbox.ignore', $email) }}">
                    @csrf
                    <button type="submit" class="button button-secondary" style="width:100%" onclick="return confirm('Ignore this email?')">Ignore</button>
                </form>
            @else
                <p style="font-size:9px;color:var(--text-muted)">Already converted to <strong>{{ $email->request_id ? 'request' : 'lead' }}</strong>#{{ $email->request_id ?? $email->lead_id }}.</p>
                <a href="{{ $email->request_id ? route('admin.requests.show', $email->request_id) : route('admin.leads.v2.show', $email->lead_id) }}" class="button button-primary">Open {{ $email->request_id ? 'request' : 'lead' }}</a>
            @endif
        </div>
    </section>
</div>
@endsection
