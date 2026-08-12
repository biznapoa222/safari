@extends('layouts.guest')

@section('title', 'Authenticator Code')
@section('content')
<div class="two-factor-challenge-page">
    <div class="two-factor-challenge-card">
        <a href="{{ route('login') }}" class="two-factor-brand">Shishi Footsteps</a>
        <div class="two-factor-challenge-icon"><i data-lucide="shield-check"></i></div>
        <h1>Authenticator code</h1>
        <p>Enter the 6-digit code from Google Authenticator.</p>
        @if($errors->any())<div class="two-factor-error">{{ $errors->first('code') }}</div>@endif
        <form method="POST" action="{{ route('two-factor.challenge.verify') }}">
            @csrf
            <label>6-digit code<input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" autofocus required></label>
            <button class="two-factor-primary-button">Verify</button>
        </form>
        <a class="two-factor-back-link" href="{{ route('login') }}">Use another account</a>
    </div>
</div>
@endsection
