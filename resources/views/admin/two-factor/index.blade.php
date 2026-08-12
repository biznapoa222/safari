@extends('layouts.admin')

@section('title', 'Manage 2FA')
@section('content')
@include('admin.partials.flash')

<section class="two-factor-page">
    <header class="two-factor-header">
        <div>
            <span>Administration / Security</span>
            <h1>Manage 2FA</h1>
            <p>Protect {{ $user->email }} with Google Authenticator.</p>
        </div>
        <span class="two-factor-status {{ $enabled ? 'is-enabled' : ($pendingSecret ? 'is-pending' : '') }}">{{ $enabled ? 'Enabled' : ($pendingSecret ? 'Setup' : 'Off') }}</span>
    </header>

    <section class="two-factor-card">
        @if($enabled)
            <div class="two-factor-card-heading">
                <div><strong>Google Authenticator</strong><span>2-step verification is active on this account.</span></div>
                <span class="two-factor-status is-enabled">Enabled</span>
            </div>
            <div class="two-factor-alert">Your account will request a 6-digit authenticator code after password sign-in.</div>
            <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="two-factor-disable-form">
                @csrf
                <label>Current password<input type="password" name="password" autocomplete="current-password" required></label>
                <button class="two-factor-danger-button">Turn Off</button>
            </form>
        @elseif($pendingSecret)
            <div class="two-factor-card-heading">
                <div><strong>Google Authenticator</strong><span>Scan or open the setup link, then confirm the 6-digit code.</span></div>
                <span class="two-factor-status is-pending">Setup</span>
            </div>
            <div class="two-factor-setup">
                <img src="{{ $qrCode }}" alt="Google Authenticator QR code" class="two-factor-qr">
                <div class="two-factor-secret">
                    <span>Setup key</span>
                    <strong>{{ $pendingSecret }}</strong>
                    <a href="{{ $otpAuthUri }}">Open in Authenticator</a>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.two-factor.confirm') }}" class="two-factor-confirm-form">
                @csrf
                <label>6-digit code<input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required></label>
                <button class="two-factor-primary-button">Confirm</button>
            </form>
        @else
            <div class="two-factor-card-heading">
                <div><strong>Google Authenticator</strong><span>Add a 6-digit verification code before sign-in completes.</span></div>
                <span class="two-factor-status">Off</span>
            </div>
            <form method="POST" action="{{ route('admin.two-factor.start') }}">
                @csrf
                <button class="two-factor-primary-button">Turn On</button>
            </form>
        @endif
    </section>
</section>
@endsection
