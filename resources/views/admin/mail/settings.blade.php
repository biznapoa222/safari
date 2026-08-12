@extends('layouts.admin')
@section('title', 'Mail Settings · Shishi Footsteps ERP')
@section('content')
<x-admin.top-bar title="Mail Settings" description="Configure SMTP and outgoing email" :search="false" :addButton="false" />
@include('admin.partials.flash')

<div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;align-items:start">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>SMTP &amp; Sender</h2></div>
        <div style="padding:16px">
            <form method="POST" action="{{ route('admin.mail.settings.update') }}">
                @csrf @method('PUT')
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Mailer</label>
                        <select name="mailer" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF;color:var(--text)">
                            @foreach(['smtp','sendmail','log','array'] as $m)
                            <option value="{{ $m }}" @selected($setting->mailer === $m)>{{ strtoupper($m) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">SMTP Host</label>
                        <input type="text" name="host" value="{{ $setting->host }}" placeholder="smtp.example.com" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Port</label>
                        <input type="number" name="port" value="{{ $setting->port }}" placeholder="587" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Encryption</label>
                        <select name="encryption" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                            <option value="">None</option>
                            <option value="tls" @selected($setting->encryption === 'tls')>TLS</option>
                            <option value="ssl" @selected($setting->encryption === 'ssl')>SSL</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Username</label>
                        <input type="text" name="username" value="{{ $setting->username }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Password</label>
                        <input type="password" name="password" value="" placeholder="••••••••" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">From email</label>
                        <input type="email" name="from_address" value="{{ $setting->from_address }}" required style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">From name</label>
                        <input type="text" name="from_name" value="{{ $setting->from_name }}" required style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Reply-to email</label>
                        <input type="email" name="reply_to_address" value="{{ $setting->reply_to_address }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                    <div>
                        <label style="display:block;font-size:9px;font-weight:600;color:var(--text-muted);margin-bottom:6px">Reply-to name</label>
                        <input type="text" name="reply_to_name" value="{{ $setting->reply_to_name }}" style="width:100%;height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    </div>
                </div>
                <div style="margin-top:14px;display:flex;gap:10px;align-items:center">
                    <label style="display:flex;align-items:center;gap:8px;font-size:9px;color:var(--text);cursor:pointer">
                        <input type="checkbox" name="is_active" value="1" @checked($setting->is_active) style="width:14px;height:14px;accent-color:var(--primary)">
                        Enable SMTP (when unchecked, emails are written to laravel.log instead of sent)
                    </label>
                </div>
                <div style="margin-top:16px;display:flex;gap:10px">
                    <button type="submit" class="button button-primary">Save</button>
                </div>
            </form>
        </div>
    </section>

    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Send test email</h2></div>
        <div style="padding:16px">
            <form method="POST" action="{{ route('admin.mail.settings.test') }}">
                @csrf
                <div style="display:grid;gap:8px">
                    <input type="email" name="to" required placeholder="Recipient email" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <input type="text" name="subject" placeholder="Subject (optional)" style="height:38px;padding:0 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF">
                    <textarea name="body" rows="4" placeholder="Body (optional)" style="padding:9px 11px;border:1px solid #d9d0c1;border-radius:7px;font-size:9px;background:#F8F5EF"></textarea>
                    <button type="submit" class="button button-secondary">Send test</button>
                </div>
            </form>
        </div>
    </section>
</div>

<section class="ops-panel" style="margin-top:16px">
    <div class="ops-panel-title"><h2>Recent emails</h2><span>{{ $stats['total'] }} total · {{ $stats['sent'] }} sent · {{ $stats['failed'] }} failed</span></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead>
                <tr>
                    <th>When</th>
                    <th>Category</th>
                    <th>Subject</th>
                    <th>To</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent as $mail)
                <tr>
                    <td style="font-size:8px">{{ $mail->sent_at?->format('d M Y H:i') ?? $mail->created_at->format('d M Y H:i') }}</td>
                    <td><span class="ops-pill">{{ $mail->category }}</span></td>
                    <td>{{ $mail->subject }}</td>
                    <td style="font-size:9px">{{ $mail->to_email }}<br><small style="color:var(--text-muted)">{{ $mail->to_name }}</small></td>
                    <td>
                        @if($mail->status === 'sent')
                            <span class="ops-pill" style="background:#f0fdf4;color:#16a34a">Sent</span>
                        @elseif($mail->status === 'failed')
                            <span class="ops-pill" style="background:#fef2f2;color:#dc2626">Failed</span>
                        @else
                            <span class="ops-pill">{{ ucfirst($mail->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:32px;color:var(--text-muted)">No emails sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
