@extends('layouts.admin')

@section('title', __('ui.dashboard'))

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">{{ now()->format('l, F j') }}</p>
            <h1>{{ __('ui.good_afternoon') }}, Amara</h1>
            <p>{{ __('ui.dashboard_intro') }}</p>
        </div>
        <div class="heading-actions">
            <button class="button button-secondary"><i data-lucide="calendar-days"></i>{{ __('ui.this_month') }}</button>
            <a href="{{ route('admin.quotations.create') }}" class="button button-primary"><i data-lucide="plus"></i>{{ __('ui.new_proposal') }}</a>
        </div>
    </div>

    <section class="stats-grid">
        <article class="stat-card">
            <div class="stat-icon stat-icon--green"><i data-lucide="circle-dollar-sign"></i></div>
            <div class="stat-change positive"><i data-lucide="trending-up"></i>12.8%</div>
            <p>{{ __('ui.confirmed_revenue') }}</p>
            <h2>${{ number_format($stats['revenue']) }}</h2>
            <small>{{ __('ui.vs_last_month') }}</small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--orange"><i data-lucide="mail-question"></i></div>
            <div class="stat-change positive"><i data-lucide="trending-up"></i>8.4%</div>
            <p>{{ __('ui.new_enquiries') }}</p>
            <h2>{{ $stats['enquiries'] }}</h2>
            <small>{{ __('ui.this_month') }}</small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--blue"><i data-lucide="files"></i></div>
            <div class="stat-change neutral">{{ __('ui.in_progress') }}</div>
            <p>{{ __('ui.active_proposals') }}</p>
            <h2>{{ $stats['proposals'] }}</h2>
            <small>{{ __('ui.awaiting_decision') }}</small>
        </article>
        <article class="stat-card">
            <div class="stat-icon stat-icon--purple"><i data-lucide="plane-takeoff"></i></div>
            <div class="stat-change positive"><i data-lucide="arrow-up-right"></i>{{ __('ui.upcoming') }}</div>
            <p>{{ __('ui.departures') }}</p>
            <h2>{{ $stats['departures'] }}</h2>
            <small>{{ __('ui.next_30_days') }}</small>
        </article>
    </section>

    <section class="dashboard-grid">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3>{{ __('ui.revenue_overview') }}</h3><p>{{ __('ui.sales_performance') }}</p></div>
                <div class="chart-legend"><span></span>2026</div>
            </div>
            <div class="chart-wrap">
                <div class="chart-y"><span>$150k</span><span>$100k</span><span>$50k</span><span>$0</span></div>
                <div class="bar-chart">
                    @foreach($monthlySales as $index => $sale)
                        <div class="bar-column">
                            <div class="bar" style="height: {{ ($sale / 150) * 100 }}%"><span>${{ $sale }}k</span></div>
                            <small>{{ now()->startOfYear()->addMonths($index)->format('M') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </article>

        <article class="panel task-panel">
            <div class="panel-heading">
                <div><h3>{{ __('ui.todays_tasks') }}</h3><p>{{ $tasks->count() }} {{ __('ui.items_due') }}</p></div>
                <a href="{{ route('admin.records.index', 'proposal-tasks') }}">{{ __('ui.view_all') }}</a>
            </div>
            <div class="task-list">
                @foreach($tasks as $task)
                    <div class="task-item">
                        <button class="task-check" aria-label="Complete task"></button>
                        <div><strong>{{ $task->title }}</strong><small>{{ $task->category }} · {{ \Carbon\Carbon::parse($task->due_at)->diffForHumans() }}</small></div>
                        <span class="priority priority--{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                    </div>
                @endforeach
            </div>
            <button class="add-task"><i data-lucide="plus"></i>{{ __('ui.add_task') }}</button>
        </article>
    </section>

    <section class="dashboard-grid dashboard-grid--bottom">
        <article class="panel panel--wide">
            <div class="panel-heading">
                <div><h3>{{ __('ui.recent_proposals') }}</h3><p>{{ __('ui.latest_client_quotes') }}</p></div>
                <a href="{{ route('admin.quotations.index') }}">{{ __('ui.view_all') }} <i data-lucide="arrow-right"></i></a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>{{ __('ui.proposal') }}</th><th>{{ __('ui.client') }}</th><th>{{ __('ui.destination') }}</th><th>{{ __('ui.value') }}</th><th>{{ __('ui.status') }}</th><th></th></tr></thead>
                    <tbody>
                    @foreach($proposals as $proposal)
                        <tr>
                            <td><strong>{{ $proposal->reference }}</strong><small>{{ $proposal->title }}</small></td>
                            <td>{{ $proposal->client_name }}</td>
                            <td>{{ $proposal->destination }}</td>
                            <td><strong>${{ number_format($proposal->quoted_amount) }}</strong></td>
                            <td><span class="status status--{{ $proposal->status }}">{{ ucfirst($proposal->status) }}</span></td>
                            <td><button class="row-action"><i data-lucide="more-horizontal"></i></button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </article>

        <article class="panel departure-panel">
            <div class="panel-heading">
                <div><h3>{{ __('ui.upcoming_departures') }}</h3><p>{{ __('ui.next_30_days') }}</p></div>
                <a href="{{ route('admin.records.index', 'operations-calendar') }}"><i data-lucide="calendar"></i></a>
            </div>
            <div class="departure-list">
                @foreach($departures as $departure)
                    <div class="departure-item">
                        <div class="date-tile"><strong>{{ \Carbon\Carbon::parse($departure->start_date)->format('d') }}</strong><small>{{ \Carbon\Carbon::parse($departure->start_date)->format('M') }}</small></div>
                        <div><strong>{{ $departure->title }}</strong><small>{{ $departure->lead_guest }} · {{ $departure->travelers }} guests</small></div>
                        <i data-lucide="chevron-right"></i>
                    </div>
                @endforeach
            </div>
        </article>
    </section>
@endsection
