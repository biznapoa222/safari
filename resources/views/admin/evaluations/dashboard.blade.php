@extends('layouts.admin')
@section('title', 'Evaluation Department Dashboard')
@section('content')
<div class="ops-page-heading">
    <div>
        <p class="eyebrow">Evaluation department</p>
        <h1>Evaluation Dashboard</h1>
        <p>Invoice verification, missing invoice detection and payment tracking.</p>
    </div>
    <div class="heading-actions">
        <a class="button button-secondary" href="{{ route('admin.evaluations.index') }}"><i data-lucide="clipboard-check"></i>Evaluation queue</a>
        <a class="button button-primary" href="{{ route('admin.evaluations.invoices') }}"><i data-lucide="upload"></i>Upload invoices</a>
    </div>
</div>

@include('admin.partials.flash')

<div class="kpi-dashboard">
    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-icon stat-icon--blue"><i data-lucide="clipboard-list"></i></div>
            <p>Today's Evaluations</p>
            <h2>{{ $kpiData['today'] }}</h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--orange"><i data-lucide="hourglass"></i></div>
            <p>Pending</p>
            <h2>{{ $kpiData['pending'] }}</h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--green"><i data-lucide="badge-check"></i></div>
            <p>Approved</p>
            <h2>{{ $kpiData['approved'] }}</h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--purple"><i data-lucide="receipt-text"></i></div>
            <p>Total Invoices</p>
            <h2>{{ $kpiData['total_invoices'] }}</h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--red"><i data-lucide="file-x"></i></div>
            <p>Missing Invoices</p>
            <h2 class="negative-money">{{ $kpiData['missing_invoices'] }}</h2>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--yellow"><i data-lucide="clock"></i></div>
            <p>Overdue Payments</p>
            <h2 class="negative-money">{{ $kpiData['overdue_payments'] }}</h2>
        </article>
    </section>

    @if($kpiData['largest_variance'] > 0)
    <div class="ops-panel" style="padding:1rem;border-left:4px solid var(--danger)">
        <strong>Largest variance detected:</strong> ${{ number_format($kpiData['largest_variance'], 2) }}
        @if($kpiData['largest_variance_entry'])
        — {{ $kpiData['largest_variance_entry']->title }} ({{ $kpiData['largest_variance_entry']->item_type }})
        @endif
    </div>
    @endif

    <div class="dashboard-grid">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3>Weekly Trend</h3><p>Proposals, confirmations & invoices</p></div>
            </div>
            <div class="chart-wrap">
                <div class="bar-chart" style="display:grid;grid-template-columns:repeat({{ $weeklyTrend->count() }},1fr)">
                    @foreach($weeklyTrend as $w)
                    <div class="bar-column">
                        <div class="bar bar--blue" style="height:min({{ $w['proposals'] > 0 ? ($w['proposals'] / $weeklyTrend->max('proposals')) * 100 : 0 }}%,100%)"><small>{{ $w['proposals'] }}</small></div>
                        <div class="bar bar--green" style="height:min({{ $w['confirmed'] > 0 ? ($w['confirmed'] / $weeklyTrend->max('confirmed')) * 100 : 0 }}%,100%)"><small>{{ $w['confirmed'] }}</small></div>
                        <div class="bar bar--orange" style="height:min({{ $w['invoices'] > 0 ? ($w['invoices'] / $weeklyTrend->max('invoices')) * 100 : 0 }}%,100%)"><small>{{ $w['invoices'] }}</small></div>
                        <small>{{ $w['label'] }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </article>
        <article class="panel">
            <div class="panel-heading"><div><h3>Invoice Status</h3><p>Distribution</p></div></div>
            <div class="task-list">
                @foreach($invoiceStatus as $is)
                <div class="task-item" style="justify-content:space-between">
                    <span class="ops-pill {{ $is['label'] === 'approved' || $is['label'] === 'paid' ? 'ops-pill--green' : ($is['label'] === 'requires_amendment' ? 'ops-pill--red' : 'ops-pill--blue') }}">{{ ucwords(str_replace('_', ' ', $is['label'])) }}</span>
                    <strong>{{ $is['value'] }}</strong>
                </div>
                @endforeach
            </div>
        </article>
    </div>

    <div class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading"><div><h3>Recent Activity</h3></div></div>
            <div class="task-list">
                @forelse($recentEntries as $entry)
                <div class="task-item">
                    <div><strong>{{ $entry->title }}</strong><small>{{ $entry->item_type }} — {{ $entry->evaluated_at?->diffForHumans() }}</small></div>
                    <span class="ops-pill {{ $entry->status === 'matched' ? 'ops-pill--green' : 'ops-pill--blue' }}">{{ $entry->status }}</span>
                </div>
                @empty
                <div class="empty-cell">No recent evaluation activity.</div>
                @endforelse
            </div>
        </article>
        <article class="panel">
            <div class="panel-heading"><div><h3>Upcoming Payments</h3></div></div>
            <div class="task-list">
                @forelse($kpiData['upcoming_payments'] as $payment)
                <div class="task-item">
                    <div><strong>{{ $payment->company_name }}</strong><small>{{ $payment->invoice_number }} — Due {{ $payment->payment_deadline?->format('d M Y') }}</small></div>
                    <strong>${{ number_format($payment->amount, 2) }}</strong>
                </div>
                @empty
                <div class="empty-cell">No upcoming payments.</div>
                @endforelse
            </div>
        </article>
    </div>

    <div class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading"><div><h3>Reservation Leaderboard</h3></div></div>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Officer</th><th>Evaluations</th><th>Approved</th></tr></thead>
                    <tbody>
                    @forelse($kpiData['reservation_leaderboard'] as $r)
                    <tr><td><strong>{{ $r['name'] }}</strong></td><td>{{ $r['evaluations'] }}</td><td class="{{ $r['approved'] > 0 ? 'positive-money' : '' }}">{{ $r['approved'] }}</td></tr>
                    @empty<tr><td colspan="3" class="empty-cell">No data</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
        </article>
        <article class="panel">
            <div class="panel-heading"><div><h3>Supplier Leaderboard</h3></div></div>
            <div class="table-wrap">
                <table class="ops-table">
                    <thead><tr><th>Supplier</th><th>Invoices</th><th>Total</th></tr></thead>
                    <tbody>
                    @forelse($kpiData['supplier_leaderboard'] as $s)
                    <tr><td><strong>{{ $s->company_name }}</strong></td><td>{{ $s->total }}</td><td><strong>${{ number_format($s->total_amount, 2) }}</strong></td></tr>
                    @empty<tr><td colspan="3" class="empty-cell">No data</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </div>
</div>
<style>
.kpi-dashboard { display:flex; flex-direction:column; gap:1.5rem; }
.stat-card .stat-icon--yellow { background:#fef3c7;color:#d97706; }
</style>
@endsection
