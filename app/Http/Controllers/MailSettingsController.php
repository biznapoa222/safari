<?php

namespace App\Http\Controllers;

use App\Models\MailSetting;
use App\Models\SentEmail;
use App\Services\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MailSettingsController extends Controller
{
    public function show(): View
    {
        $setting = MailSetting::current();
        $recent = SentEmail::latest()->limit(20)->get();
        $stats = [
            'total' => SentEmail::count(),
            'sent' => SentEmail::where('status', 'sent')->count(),
            'failed' => SentEmail::where('status', 'failed')->count(),
        ];
        return view('admin.mail.settings', compact('setting', 'recent', 'stats'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mailer' => ['required', 'in:smtp,sendmail,log,array'],
            'host' => ['nullable', 'string', 'max:255'],
            'port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['nullable', 'in:tls,ssl,none'],
            'username' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:2000'],
            'from_address' => ['required', 'email', 'max:255'],
            'from_name' => ['required', 'string', 'max:255'],
            'reply_to_address' => ['nullable', 'email', 'max:255'],
            'reply_to_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $setting = MailSetting::current();
        $setting->fill($data)->save();

        return back()->with('success', 'SMTP settings saved.');
    }

    public function test(Request $request, MailService $mail): RedirectResponse
    {
        $data = $request->validate([
            'to' => ['required', 'email'],
            'subject' => ['nullable', 'string', 'max:200'],
            'body' => ['nullable', 'string', 'max:2000'],
        ]);

        $result = $mail->send(
            'test',
            $data['to'],
            null,
            $data['subject'] ?? 'Shishi Footsteps · SMTP test',
            $data['body'] ?? "This is a test email sent from the Shishi Footsteps ERP.\n\nIf you received this, your SMTP configuration is working.\n\n— Shishi Footsteps",
            null,
            null,
            $request->user()?->id
        );

        if ($result['success']) {
            return back()->with('success', 'Test email sent.');
        }
        return back()->withErrors(['mail' => $result['error'] ?? 'Failed to send test email']);
    }
}
