<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'channel' => 'required|in:email,whatsapp,phone_call,internal_note',
            'content' => 'required|string',
            'direction' => 'required|in:incoming,outgoing',
        ]);

        $data['user_id'] = auth()->id();

        Conversation::create($data);

        return back()->with('success', 'Conversation entry added.');
    }
}
