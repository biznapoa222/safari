@extends('layouts.public')
@section('title', 'Payment')
@section('content')
<section style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem;">
    <div style="max-width:500px;width:100%;">
        <div style="text-align:center;margin-bottom:2rem;">
            <h1 style="font-family:'Outfit',sans-serif;font-size:2rem;">Complete Your Payment</h1>
            <p style="color:var(--text-muted);">Secure payment for your safari booking</p>
        </div>
        <div class="ops-panel">
            <div style="padding:1rem;text-align:center;">
                <small class="text-muted">Amount Due</small>
                <h2 style="font-size:2.5rem;">{{ $link->currency }} {{ number_format($link->amount, 2) }}</h2>
                <p style="margin:0.5rem 0;">{{ ucfirst($link->type) }} Payment</p>
            </div>
            <hr style="border-color:var(--border);margin:1rem 0;">
            <form method="POST" action="{{ route('admin.payments.links.pay', $link->token) }}" style="text-align:center;">
                @csrf
                <div style="margin-bottom:1.5rem;">
                    <p style="font-weight:600;margin-bottom:0.75rem;font-size:0.9rem;">Choose payment method</p>
                    @foreach ($gateways as $g)
                        <label style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;border:2px solid var(--border);border-radius:0.5rem;margin-bottom:0.5rem;cursor:pointer;transition:border-color 0.15s;{{ $loop->first ? 'border-color:var(--primary);' : '' }}">
                            <input type="radio" name="gateway" value="{{ $g }}" {{ $loop->first ? 'checked' : '' }} style="width:auto;">
                            <span style="font-weight:500;">
                                @switch($g)
                                    @case('stripe') Credit / Debit Card (Stripe) @break
                                    @case('flutterwave') Mobile Money / Card (Flutterwave) @break
                                    @case('manual') Offline / Manual Payment @break
                                    @default {{ ucfirst($g) }}
                                @endswitch
                            </span>
                        </label>
                    @endforeach
                </div>
                <div style="margin-bottom:1rem;">
                    <label class="checkbox-label" style="display:flex;align-items:center;gap:0.5rem;justify-content:center;font-size:0.9rem;">
                        <input type="checkbox" name="accept_cancellation" value="1" required>
                        I have read and understood the cancellation policy
                    </label>
                </div>
                <button class="button button-primary" style="width:100%;padding:1rem;font-size:1.1rem;">
                    Pay {{ $link->currency }} {{ number_format($link->amount, 2) }}
                </button>
                <p style="margin-top:1rem;font-size:0.8rem;color:var(--text-muted);">
                    <i data-lucide="lock" style="width:14px;height:14px;"></i>
                    Secured by industry-standard encryption
                </p>
            </form>
        </div>
    </div>
</section>
<style>.checkbox-label input { width: auto; }</style>
@endsection
