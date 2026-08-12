<?php

namespace App\Http\Controllers;

use App\Models\IncomingEmail;
use App\Models\IncomingMailAccount;
use App\Models\User;
use App\Services\IncomingMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IncomingMailController extends Controller
{
    public function accounts(): View
    {
        $accounts = IncomingMailAccount::latest()->get();
        return view('admin.mail.incoming-accounts', [
            'accounts' => $accounts,
            'users' => User::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function storeAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'protocol' => ['required', 'in:imap,pop3'],
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['required', 'in:ssl,tls,none'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:2000'],
            'folder' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
            'mark_seen' => ['nullable', 'boolean'],
            'delete_after_fetch' => ['nullable', 'boolean'],
            'auto_create_request' => ['nullable', 'boolean'],
            'assigned_consultant_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['mark_seen'] = $request->boolean('mark_seen');
        $data['delete_after_fetch'] = $request->boolean('delete_after_fetch');
        $data['auto_create_request'] = $request->boolean('auto_create_request');
        $data['folder'] = $data['folder'] ?: 'INBOX';

        IncomingMailAccount::create($data);
        return back()->with('success', 'IMAP account saved.');
    }

    public function updateAccount(Request $request, IncomingMailAccount $account): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'protocol' => ['required', 'in:imap,pop3'],
            'host' => ['required', 'string', 'max:255'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
            'encryption' => ['required', 'in:ssl,tls,none'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:2000'],
            'folder' => ['nullable', 'string', 'max:120'],
            'is_active' => ['nullable', 'boolean'],
            'mark_seen' => ['nullable', 'boolean'],
            'delete_after_fetch' => ['nullable', 'boolean'],
            'auto_create_request' => ['nullable', 'boolean'],
            'assigned_consultant_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $data['mark_seen'] = $request->boolean('mark_seen');
        $data['delete_after_fetch'] = $request->boolean('delete_after_fetch');
        $data['auto_create_request'] = $request->boolean('auto_create_request');
        $data['folder'] = $data['folder'] ?: 'INBOX';
        if (! $data['password']) {
            unset($data['password']);
        }
        $account->update($data);
        return back()->with('success', 'Account updated.');
    }

    public function destroyAccount(IncomingMailAccount $account): RedirectResponse
    {
        $account->delete();
        return back()->with('success', 'Account removed.');
    }

    public function fetchNow(Request $request, IncomingMailService $service): RedirectResponse
    {
        $result = $service->fetchAll();
        return back()->with('success', sprintf(
            'Fetched %d message(s) from %d account(s).%s',
            $result['fetched'],
            $result['accounts'],
            $result['errors'] ? ' Some accounts failed; check the error log.' : ''
        ));
    }

    public function inbox(Request $request): View
    {
        $status = $request->get('status', 'new');
        $emails = IncomingEmail::with('account')
            ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
            ->latest('received_at')
            ->paginate(20)
            ->withQueryString();
        return view('admin.mail.inbox', compact('emails', 'status'));
    }

    public function show(IncomingEmail $email): View
    {
        $email->load('account', 'lead', 'request');
        return view('admin.mail.inbox-show', compact('email'));
    }

    public function convert(Request $request, IncomingEmail $email, IncomingMailService $service): RedirectResponse
    {
        $data = $request->validate([
            'as' => ['required', 'in:request,lead'],
            'consultant_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($data['as'] === 'request') {
            $requestId = $service->convertToRequest($email, $data['consultant_id'] ?? null);
            return redirect()->route('admin.requests.show', $requestId)->with('success', 'Incoming email converted to request.');
        }

        $leadId = $service->convertToLead($email, $data['consultant_id'] ?? null);
        return redirect()->route('admin.leads.v2.show', $leadId)->with('success', 'Incoming email converted to lead.');
    }

    public function ignore(IncomingEmail $email, IncomingMailService $service): RedirectResponse
    {
        $service->ignore($email);
        return back()->with('success', 'Email marked as ignored.');
    }
}
