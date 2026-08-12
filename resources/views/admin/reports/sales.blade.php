@extends('layouts.admin')
@section('title', 'Sales Report')
@section('content')
<x-admin.top-bar
    title="Sales Report"
    description="Reports"
    :addButton="false"
    :search="false"
/>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Monthly Sales</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Month</th><th>Bookings</th><th>Revenue</th><th>Collected</th></tr></thead>
                <tbody>
                    @forelse($salesData as $s)
                    <tr><td>{{ $s->month }}</td><td>{{ $s->total_bookings }}</td><td><strong>${{ number_format($s->revenue,2) }}</strong></td><td>${{ number_format($s->collected,2) }}</td></tr>
                    @empty
                    <tr><td colspan="4" class="text-muted text-center">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Revenue by Currency</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Currency</th><th>Revenue</th></tr></thead>
                <tbody>
                    @foreach($byCountry as $c)
                    <tr><td>{{ $c->currency }}</td><td><strong>{{ number_format($c->revenue,2) }}</strong></td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
<section class="ops-panel" style="margin-top:1.5rem;">
    <div class="ops-panel-title"><h2>Revenue by Consultant</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Consultant</th><th>Revenue</th></tr></thead>
            <tbody>
                @foreach($byConsultant as $c)
                <tr><td>{{ $c['name'] }}</td><td><strong>${{ number_format($c['revenue'],2) }}</strong></td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
