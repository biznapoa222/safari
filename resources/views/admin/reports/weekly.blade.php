@extends('layouts.admin')
@section('title', 'Weekly Management Report')
@section('content')
<x-admin.top-bar
    title="Weekly Management Report"
    description="Reports"
    :addButton="false"
    :search="false"
>
    <p style="margin:0.25rem 0 0;color:var(--text-muted);font-size:0.85rem;">{{ $data['week'] }}</p>
    <x-slot:actions>
        <a href="{{ route('admin.reports.weekly.export', 'pdf') }}" class="button button-secondary button-sm"><i data-lucide="file-text"></i>Export PDF</a>
        <a href="{{ route('admin.reports.weekly.export', 'csv') }}" class="button button-secondary button-sm"><i data-lucide="file-spreadsheet"></i>Export CSV</a>
    </x-slot:actions>
</x-admin.top-bar>
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">New Leads</small>
        <h2>{{ $data['new_leads'] }}</h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Converted Leads</small>
        <h2>{{ $data['converted_leads'] }}</h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">New Bookings</small>
        <h2>{{ $data['new_bookings'] }}</h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Revenue</small>
        <h2>${{ number_format($data['revenue']) }}</h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Collected</small>
        <h2 class="text-green">${{ number_format($data['collected']) }}</h2>
    </div>
    <div class="ops-panel" style="text-align:center;padding:1.5rem;">
        <small class="text-muted">Outstanding</small>
        <h2 style="color:#ef4444;">${{ number_format($data['outstanding']) }}</h2>
    </div>
</div>
<section class="ops-panel">
    <div class="ops-panel-title"><h2>Consultant KPIs (This Week)</h2></div>
    <div class="table-wrap">
        <table class="ops-table">
            <thead><tr><th>Consultant</th><th>Leads</th><th>Converted</th><th>Revenue</th></tr></thead>
            <tbody>
                @foreach($data['consultant_kpis'] as $c)
                <tr><td><strong>{{ $c['name'] }}</strong></td><td>{{ $c['leads'] }}</td><td>{{ $c['converted'] }}</td><td>${{ number_format($c['revenue'],2) }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<style>.text-green { color: #22c55e; }</style>
@endsection
