@extends('layouts.admin')
@section('title', 'Evaluations')
@section('content')
<div class="ops-page-heading">
    <div>
        <p class="eyebrow">Confirmed proposals</p>
        <h1>Evaluations</h1>
        <p>Verify supplier invoices against the confirmed itinerary before finance pays them.</p>
    </div>
    <div class="heading-actions">
        <a class="button button-secondary" href="{{ route('admin.evaluations.invoices') }}"><i data-lucide="files"></i>Reservation invoices</a>
    </div>
</div>
@include('admin.partials.flash')

<section class="evaluation-guide">
    @foreach([
        ['files', '1. Verify invoices', 'Confirm every supplier document has been uploaded.'],
        ['list-checks', '2. Match itinerary', 'Check rates, dates, meal plans and room details.'],
        ['link-2', '3. Assign invoices', 'Attach each invoice to its correct confirmed service.'],
        ['landmark', '4. Finance handoff', 'Approve deadlines and send payable invoices to accounts.'],
    ] as [$icon, $title, $copy])
        <article><i data-lucide="{{ $icon }}"></i><span><strong>{{ $title }}</strong><small>{{ $copy }}</small></span></article>
    @endforeach
</section>

<section class="ops-panel">
    <form class="ops-filters" method="GET">
        <label class="ops-search"><i data-lucide="search"></i><input name="search" value="{{ request('search') }}" placeholder="Proposal, client or reference"></label>
        <select name="status">
            <option value="">All evaluation states</option>
            @foreach(['pending', 'in_progress', 'approved'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        <button class="button button-primary">Filter</button>
    </form>
    <div class="table-wrap">
        <table class="ops-table evaluation-queue-table">
            <thead><tr><th>Proposal / client</th><th>Travel</th><th>Supplier invoices</th><th>Verification progress</th><th>Evaluation</th><th></th></tr></thead>
            <tbody>
            @forelse($evaluations as $item)
                @php $progress = $item->entry_count > 0 ? round(($item->matched_count / $item->entry_count) * 100) : 0; @endphp
                <tr>
                    <td><strong>{{ $item->reference }} - {{ $item->title }}</strong><small>{{ $item->client_name }}</small></td>
                    <td>{{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}<small>{{ $item->duration_days }} days - {{ $item->guest_count }} guests</small></td>
                    <td><strong>{{ $item->invoice_count }}</strong><small>documents received</small></td>
                    <td>
                        <div class="evaluation-progress"><span style="width: {{ $progress }}%"></span></div>
                        <small>{{ $item->matched_count }} of {{ $item->entry_count }} services matched</small>
                    </td>
                    <td><span class="ops-pill {{ $item->evaluation_status === 'approved' ? 'ops-pill--green' : 'ops-pill--blue' }}">{{ ucwords(str_replace('_', ' ', $item->evaluation_status)) }}</span></td>
                    <td><a class="button button-secondary button-compact" href="{{ route('admin.evaluations.show', $item->id) }}">Open evaluation<i data-lucide="arrow-right"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty-cell">No confirmed proposals are waiting for evaluation.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="ops-pagination">{{ $evaluations->links() }}</div>
</section>
@endsection
