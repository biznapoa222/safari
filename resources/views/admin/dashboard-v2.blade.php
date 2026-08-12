@extends('layouts.admin')
@section('title', 'Executive Dashboard')
@section('content')
<x-admin.top-bar
    title="Executive Dashboard"
    :description="now()->format('l, F j, Y')"
    :addButton="false"
    :search="false"
>
    <p style="margin:0.25rem 0 0;color:var(--text-muted);font-size:0.85rem;">Real-time KPIs and performance metrics.</p>
    <x-slot:actions>
        <a href="{{ route('admin.reports.weekly') }}" class="button button-secondary button-sm"><i data-lucide="calendar"></i>Weekly Report</a>
        <a href="{{ route('admin.reports.kpi') }}" class="button button-secondary button-sm"><i data-lucide="chart-no-axes-combined"></i>Full KPIs</a>
    </x-slot:actions>
</x-admin.top-bar>

{{-- Today's Stats --}}
<h3 style="margin-bottom:0.75rem;">Today</h3>
<section class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
    <article class="stat-card">
        <div class="stat-icon stat-icon--orange"><i data-lucide="mail-question"></i></div>
        <p>New Leads</p>
        <h2>{{ $stats['today_leads'] }}</h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--blue"><i data-lucide="calendar-check"></i></div>
        <p>Bookings</p>
        <h2>{{ $stats['today_bookings'] }}</h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--green"><i data-lucide="circle-dollar-sign"></i></div>
        <p>Revenue</p>
        <h2>${{ number_format($stats['today_revenue']) }}</h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--purple"><i data-lucide="trending-up"></i></div>
        <p>Conversion Rate</p>
        <h2>{{ $stats['conversion_rate'] }}%</h2>
    </article>
</section>

{{-- Monthly Stats --}}
<h3 style="margin-bottom:0.75rem;">This Month</h3>
<section class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
    <article class="stat-card">
        <div class="stat-icon stat-icon--orange"><i data-lucide="mail-question"></i></div>
        <p>Leads</p><h2>{{ $stats['month_leads'] }}</h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--blue"><i data-lucide="files"></i></div>
        <p>Bookings</p><h2>{{ $stats['month_bookings'] }}</h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--green"><i data-lucide="circle-dollar-sign"></i></div>
        <p>Revenue (Total)</p><h2>${{ number_format($stats['month_revenue']) }}</h2>
    </article>
    <article class="stat-card">
        <div class="stat-icon stat-icon--purple"><i data-lucide="wallet"></i></div>
        <p>Collected</p><h2>${{ number_format($stats['month_collected']) }}</h2>
    </article>
</section>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
    {{-- Top Consultants --}}
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Top Consultants</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Name</th><th>Leads Assigned</th><th>Converted</th><th>Conversion Rate</th></tr></thead>
                <tbody>
                    @foreach($topConsultants as $c)
                    <tr>
                        <td><strong>{{ $c['name'] }}</strong></td>
                        <td>{{ $c['leads_assigned'] }}</td>
                        <td>{{ $c['leads_converted'] }}</td>
                        <td>{{ $c['conversion_rate'] }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Top Activities --}}
    <section class="ops-panel">
        <div class="ops-panel-title"><h2>Top Activities</h2></div>
        <div class="table-wrap">
            <table class="ops-table">
                <thead><tr><th>Activity</th><th>Bookings</th></tr></thead>
                <tbody>
                    @foreach($topActivities as $a)
                    <tr><td>{{ $a->name }}</td><td>{{ $a->total }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
