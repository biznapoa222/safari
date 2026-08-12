@extends('layouts.public')
@section('title', 'Payment Link Expired')
@section('content')
<section style="min-height:60vh;display:flex;align-items:center;justify-content:center;padding:2rem;text-align:center;">
    <div>
        <i data-lucide="clock" style="width:64px;height:64px;color:var(--text-muted);margin-bottom:1rem;"></i>
        <h1>Payment Link Expired</h1>
        <p>This payment link is no longer valid. Please contact your safari specialist for a new payment link.</p>
        <a href="{{ route('home') }}" class="button button-primary" style="margin-top:1rem;">Return to Home</a>
    </div>
</section>
@endsection
