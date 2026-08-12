@extends('layouts.admin')
@section('title', 'Evaluation '.$quotation->reference)
@section('content')
<div class="proposal-toolbar evaluation-toolbar">
    <div><span>{{ $quotation->reference }}</span><strong>{{ $quotation->title }}</strong><small>{{ $quotation->client_name }} · {{ $quotation->guest_count }} guests · {{ $quotation->duration_days }} days</small></div>
    <div>
        <a href="{{ route('admin.evaluations.export', $quotation->id) }}" title="Export CSV"><i data-lucide="file-down"></i>Export</a>
        <a href="{{ route('admin.quotations.show', $quotation->id) }}"><i data-lucide="map"></i>Proposal</a>
        <a href="{{ route('admin.evaluations.index') }}"><i data-lucide="arrow-left"></i>Queue</a>
    </div>
</div>
@include('admin.partials.flash')

<div class="evaluation-shell">
    <nav class="evaluation-tabs">
        <span>Evaluation</span>
        <a href="#overview" class="is-active"><i data-lucide="chart-no-axes-combined"></i>Overview</a>
        <a href="#entries"><i data-lucide="list-checks"></i>Entries <b>{{ $summary['total'] }}</b></a>
        <a href="#invoices"><i data-lucide="receipt-text"></i>Invoices <b>{{ $summary['invoice_count'] }}</b></a>
        <a href="#missing"><i data-lucide="file-search"></i>Missing <b>{{ $summary['missing'] }}</b></a>
        <a href="#transport"><i data-lucide="car-front"></i>Transport</a>
        <a href="#accommodation"><i data-lucide="bed-double"></i>Accommodation</a>
        <a href="#activities"><i data-lucide="binoculars"></i>Activities</a>
        <a href="#supplements"><i data-lucide="package-plus"></i>Supplements</a>
        <a href="#deadlines"><i data-lucide="calendar-clock"></i>Deadlines</a>
        <a href="#audit"><i data-lucide="history"></i>Audit</a>
        <a href="#finance"><i data-lucide="landmark"></i>Finance</a>
    </nav>

    <div class="evaluation-workspace">
        {{-- OVERVIEW TAB --}}
        <section id="overview" class="evaluation-status-card">
            <div>
                <span class="evaluation-status-icon"><i data-lucide="{{ ($evaluation->status ?? 'pending') === 'approved' ? 'badge-check' : 'clipboard-clock' }}"></i></span>
                <span>
                    <small>{{ $quotation->client_name }} · {{ $quotation->guest_count }} guests</small>
                    <strong>{{ ($evaluation->status ?? 'pending') === 'approved' ? 'Approved for finance' : 'Verification in progress' }}</strong>
                    <small>{{ $quotation->start_date ? \Carbon\Carbon::parse($quotation->start_date)->format('d M Y') : '' }} · {{ $quotation->currency }}</small>
                </span>
            </div>
            <div class="evaluation-metrics" style="grid-template-columns:repeat(6,1fr)">
                <article><small>Total value</small><strong>{{ $quotation->currency }} {{ number_format($quotation->sell_total ?? $quotation->quoted_amount ?? 0, 2) }}</strong></article>
                <article><small>Supplier count</small><strong>{{ $summary['supplier_count'] }}</strong></article>
                <article><small>Missing invoices</small><strong class="negative-money">{{ $summary['missing'] }}</strong></article>
                <article><small>Pending invoices</small><strong>{{ $summary['pending_invoices'] }}</strong></article>
                <article><small>Matched</small><strong class="positive-money">{{ $summary['matched'] }}</strong></article>
                <article><small>Variance</small><strong class="{{ $summary['variance'] > 0 ? 'negative-money' : 'positive-money' }}">{{ $quotation->currency }} {{ number_format($summary['variance'], 2) }}</strong></article>
            </div>
            <div style="margin-top:0.75rem">
                <div class="evaluation-progress"><span style="width: {{ $summary['total'] > 0 ? ($summary['matched'] / $summary['total']) * 100 : 0 }}%"></span></div>
                <small>{{ $summary['matched'] }} of {{ $summary['total'] }} services matched</small>
            </div>
        </section>

        {{-- EVALUATION ENTRIES TAB --}}
        <section id="entries" class="ops-panel evaluation-entries-panel">
            <div class="ops-panel-title">
                <div><h2>Evaluation entries</h2><p>Assign invoices, verify rates and itinerary details.</p></div>
                <a class="button button-secondary button-compact" href="{{ route('admin.evaluations.missing', $quotation->id) }}"><i data-lucide="file-search"></i>Missing invoice report</a>
            </div>
            <div class="evaluation-entry-list">
            @forelse($entries as $entry)
                <form method="POST" action="{{ route('admin.evaluations.entries.update', $entry->id) }}" class="evaluation-entry {{ $entry->status === 'matched' ? 'is-matched' : ($entry->status === 'issue' ? 'has-issue' : '') }}">
                    @csrf @method('PUT')
                    <header>
                        <span class="evaluation-entry-day">Day {{ $entry->day_number }}</span>
                        <span><strong>{{ $entry->title }}</strong><small>{{ ucfirst($entry->item_type) }} — {{ $entry->supplier ?: 'No supplier' }} — {{ $entry->service_date ? \Carbon\Carbon::parse($entry->service_date)->format('d M Y') : '' }}</small></span>
                        <span class="ops-pill {{ $entry->status === 'matched' ? 'ops-pill--green' : ($entry->status === 'issue' ? 'ops-pill--red' : 'ops-pill--muted') }}">{{ ucwords(str_replace('_', ' ', $entry->status)) }}</span>
                    </header>
                    <div class="evaluation-entry-body">
                        <label class="span-2">Assign invoice<select name="supplier_invoice_id"><option value="">No invoice</option>@foreach($invoices as $inv)<option value="{{ $inv->id }}" @selected($entry->supplier_invoice_id == $inv->id)>{{ $inv->company_name }} — {{ $inv->invoice_number ?: 'pending' }} — {{ $inv->currency }} {{ number_format($inv->amount, 2) }}</option>@endforeach</select></label>
                        <label>System rate<input value="{{ $quotation->currency }} {{ number_format($entry->system_rate, 2) }}" readonly></label>
                        <label>Invoice rate<input type="number" step="0.01" name="invoice_rate" value="{{ $entry->invoice_rate }}" placeholder="0.00"></label>
                        <label>Meal plan<input name="meal_plan" value="{{ $entry->meal_plan }}" placeholder="Full board..."></label>
                        <label>Room configuration<input name="room_configuration" value="{{ $entry->room_configuration }}" placeholder="Double/twin..."></label>
                        <label>Room type<input name="room_type" value="{{ $entry->room_type }}" placeholder="Suite/tent..."></label>
                        <div class="evaluation-check-grid span-2">
                            <label><input type="checkbox" name="rate_matches" value="1" @checked($entry->rate_matches)> Rate matches</label>
                            <label><input type="checkbox" name="dates_match" value="1" @checked($entry->dates_match)> Dates match</label>
                            @php $isAcc = in_array($entry->item_type, ['room', 'accommodation'], true); @endphp
                            <label class="{{ !$isAcc ? 'is-not-applicable' : '' }}"><input type="checkbox" name="meal_plan_matches" value="1" @checked($entry->meal_plan_matches || !$isAcc) @disabled(!$isAcc)> Meal plan</label>
                            <label class="{{ !$isAcc ? 'is-not-applicable' : '' }}"><input type="checkbox" name="room_configuration_matches" value="1" @checked($entry->room_configuration_matches || !$isAcc) @disabled(!$isAcc)> Room config</label>
                            <label class="{{ !$isAcc ? 'is-not-applicable' : '' }}"><input type="checkbox" name="room_type_matches" value="1" @checked($entry->room_type_matches || !$isAcc) @disabled(!$isAcc)> Room type</label>
                        </div>
                        <label class="span-2">Issue notes<textarea name="issue_notes" rows="2" placeholder="Describe mismatch">{{ $entry->issue_notes }}</textarea></label>
                        <div class="evaluation-entry-footer span-2">
                            <span>Variance: <strong class="{{ $entry->discrepancy > 0 ? 'negative-money' : 'positive-money' }}">{{ $quotation->currency }} {{ number_format($entry->discrepancy, 2) }}</strong>
                            @if($entry->variance_percent != 0) ({{ $entry->variance_percent }}%) @endif</span>
                            @if($entry->is_overcharge)<span class="ops-pill ops-pill--red">Overcharge</span>@endif
                            @if($entry->is_undercharge)<span class="ops-pill ops-pill--blue">Undercharge</span>@endif
                            @if($entry->is_mismatch)<span class="ops-pill ops-pill--orange">Mismatch</span>@endif
                            <button class="button button-primary"><i data-lucide="save"></i>Save</button>
                        </div>
                    </div>
                </form>
            @empty<div class="empty-cell">No itinerary services to evaluate.</div>@endforelse
            </div>
        </section>

        {{-- INVOICES TAB --}}
        <section id="invoices" class="ops-panel evaluation-invoices-panel">
            <div class="ops-panel-title">
                <div><h2>Supplier invoices</h2><p>{{ $summary['invoice_count'] }} invoices for this proposal.</p></div>
                <a class="button button-secondary button-compact" href="{{ route('admin.evaluations.invoices') }}"><i data-lucide="upload"></i>Upload</a>
            </div>
            <details class="evaluation-create-invoice" {{ $invoices->isEmpty() ? 'open' : '' }}>
                <summary><i data-lucide="plus"></i>Create invoice</summary>
                <form method="POST" action="{{ route('admin.evaluations.invoices.store', $quotation->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem">
                        <label>Invoice date<input type="date" name="invoice_date" value="{{ old('invoice_date', today()->toDateString()) }}" required></label>
                        <label>Invoice number<input name="invoice_number" value="{{ old('invoice_number') }}" required></label>
                        <label>Company name<input name="company_name" value="{{ old('company_name') }}" required></label>
                        <label>Amount<input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required></label>
                        <label>Currency<input name="currency" maxlength="3" value="{{ old('currency', $quotation->currency) }}" required></label>
                        <label>Category<select name="invoice_category"><option value="">General</option><option value="accommodation">Accommodation</option><option value="activity">Activity</option><option value="transport">Transport</option><option value="guide">Guide</option><option value="park_fee">Park Fee</option><option value="jeep">Jeep</option><option value="supplement">Supplement</option></select></label>
                        <label>Type<select name="invoice_type"><option value="normal">Normal</option><option value="supplement">Supplement</option><option value="credit_note">Credit note</option></select></label>
                        <label>VAT %<input type="number" step="0.01" name="vat_rate" value="0"></label>
                        <label>Payment deadline<input type="date" name="payment_deadline"></label>
                        <label>File<input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.webp"></label>
                    </div>
                    <label style="margin-top:0.5rem" class="evaluation-check"><input type="checkbox" name="vat_reclaimable" value="1"> VAT reclaimable</label>
                    <textarea name="comments" rows="2" placeholder="Comments" style="margin-top:0.5rem;width:100%"></textarea>
                    <button class="button button-primary" style="margin-top:0.5rem"><i data-lucide="receipt-text"></i>Add invoice</button>
                </form>
            </details>
            <div class="table-wrap"><table class="ops-table"><thead><tr><th>Supplier / invoice</th><th>Date</th><th>Category</th><th>Amount</th><th>VAT</th><th>Deadline</th><th>Status</th><th></th></tr></thead><tbody>
                @forelse($invoices as $invoice)
                <tr>
                    <td><strong>{{ $invoice->company_name }}</strong><small>{{ $invoice->invoice_number ?: 'Pending' }} — {{ $invoice->uploader_name ?: 'System' }}</small></td>
                    <td>{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : '-' }}</td>
                    <td><span class="item-type">{{ $invoice->invoice_category ?: 'General' }}</span></td>
                    <td><strong>{{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</strong></td>
                    <td><small>{{ number_format($invoice->vat_rate, 1) }}%</small></td>
                    <td>{{ $invoice->payment_deadline ? \Carbon\Carbon::parse($invoice->payment_deadline)->format('d M') : '-' }}</td>
                    <td><span class="ops-pill {{ in_array($invoice->status, ['approved','payment_ready','paid']) ? 'ops-pill--green' : ($invoice->status === 'requires_amendment' ? 'ops-pill--red' : 'ops-pill--blue') }}">{{ ucwords(str_replace('_', ' ', $invoice->status)) }}</span></td>
                    <td><div class="ops-actions">
                        @if($invoice->file_path)<a href="{{ route('admin.evaluations.invoices.download', $invoice->id) }}" target="_blank" title="View"><i data-lucide="file-search"></i></a>@endif
                        <details class="edit-popover"><summary title="Edit"><i data-lucide="square-pen"></i></summary><div class="edit-popover-panel"><form method="POST" action="{{ route('admin.evaluations.invoices.update', $invoice->id) }}" enctype="multipart/form-data">@csrf @method('PUT')<input type="date" name="invoice_date" value="{{ $invoice->invoice_date ?: today() }}" required><input name="invoice_number" value="{{ $invoice->invoice_number }}" required><input name="company_name" value="{{ $invoice->company_name }}" required><input type="number" step="0.01" name="amount" value="{{ $invoice->amount }}" required><input name="currency" value="{{ $invoice->currency }}" maxlength="3" required><select name="invoice_category"><option value="">General</option><option value="accommodation" @selected($invoice->invoice_category==='accommodation')>Accommodation</option><option value="activity" @selected($invoice->invoice_category==='activity')>Activity</option><option value="transport" @selected($invoice->invoice_category==='transport')>Transport</option></select><input type="number" step="0.01" name="vat_rate" value="{{ $invoice->vat_rate }}"><label class="evaluation-check"><input type="checkbox" name="vat_reclaimable" value="1" @checked($invoice->vat_reclaimable)> Reclaimable</label><input type="date" name="payment_deadline" value="{{ $invoice->payment_deadline }}"><input type="file" name="document"><textarea name="comments" rows="2">{{ $invoice->comments }}</textarea><button class="button button-primary">Save</button></form></div></details>
                    </div></td>
                </tr>
                @empty<tr><td colspan="8" class="empty-cell">No invoices.</td></tr>@endforelse
            </tbody></table></div>
        </section>

        {{-- MISSING INVOICES TAB --}}
        <section id="missing" class="ops-panel">
            <div class="ops-panel-title">
                <div><h2>Missing invoices <span class="ops-pill ops-pill--red">{{ $summary['missing'] }}</span></h2><p>Itinerary items still awaiting supplier invoices.</p></div>
                <a class="button button-secondary button-compact" href="{{ route('admin.evaluations.missing', $quotation->id) }}"><i data-lucide="file-search"></i>Full report</a>
            </div>
            @php $missingGroups = collect($missing)->filter(fn($g) => $g['total'] > 0)->take(4); @endphp
            @if($missingGroups->isNotEmpty())
            <div class="evaluation-metrics" style="grid-template-columns:repeat(4,1fr)">
                @foreach($missingGroups as $key => $group)
                <article>
                    <small>{{ ucfirst($key) }}</small>
                    <strong class="negative-money">{{ $group['total'] }}</strong>
                </article>
                @endforeach
            </div>
            <div class="empty-cell" style="margin-top:0.5rem">Use the full report to see all missing items by category.</div>
            @else
            <div class="empty-cell"><i data-lucide="check-circle-2" style="color:var(--success)"></i> All invoices assigned.</div>
            @endif
        </section>

        {{-- TRANSPORT TAB --}}
        <section id="transport" class="ops-panel">
            <div class="ops-panel-title"><h2>Transport & Vehicle</h2><p>Transport and jeep-related itinerary items.</p></div>
            <div class="table-wrap"><table class="ops-table"><thead><tr><th>Day</th><th>Service</th><th>Supplier</th><th>System rate</th><th>Status</th></tr></thead><tbody>
                @php $transportEntries = $entries->filter(fn($e) => in_array($e->item_type, ['transport', 'vehicle', 'transfer'])); @endphp
                @forelse($transportEntries as $e)
                <tr><td>{{ $e->day_number }}</td><td>{{ $e->title }}</td><td>{{ $e->supplier ?: '-' }}</td><td>{{ $quotation->currency }} {{ number_format($e->system_rate, 2) }}</td><td><span class="ops-pill">{{ $e->status }}</span></td></tr>
                @empty<tr><td colspan="5" class="empty-cell">No transport items.</td></tr>@endforelse
            </tbody></table></div>
        </section>

        {{-- ACCOMMODATION TAB --}}
        <section id="accommodation" class="ops-panel">
            <div class="ops-panel-title"><h2>Accommodation</h2><p>Room and accommodation itinerary items.</p></div>
            <div class="table-wrap"><table class="ops-table"><thead><tr><th>Day</th><th>Property</th><th>Room</th><th>Meal plan</th><th>Check-in</th><th>Check-out</th><th>Rate</th><th>Status</th></tr></thead><tbody>
                @php $accEntries = $entries->filter(fn($e) => in_array($e->item_type, ['room', 'accommodation', 'hotel'])); @endphp
                @forelse($accEntries as $e)
                <tr><td>{{ $e->day_number }}</td><td><strong>{{ $e->title }}</strong></td><td>{{ $e->room_type ?: '-' }}</td><td>{{ $e->meal_plan ?: '-' }}</td><td>{{ $e->arrival_date ?: '-' }}</td><td>{{ $e->departure_date ?: '-' }}</td><td>{{ $quotation->currency }} {{ number_format($e->system_rate, 2) }}</td><td><span class="ops-pill">{{ $e->status }}</span></td></tr>
                @empty<tr><td colspan="8" class="empty-cell">No accommodation items.</td></tr>@endforelse
            </tbody></table></div>
        </section>

        {{-- ACTIVITIES TAB --}}
        <section id="activities" class="ops-panel">
            <div class="ops-panel-title"><h2>Activities</h2><p>Activity itinerary items.</p></div>
            <div class="table-wrap"><table class="ops-table"><thead><tr><th>Day</th><th>Activity</th><th>Supplier</th><th>Date</th><th>Rate</th><th>Status</th></tr></thead><tbody>
                @php $actEntries = $entries->filter(fn($e) => $e->item_type === 'activity'); @endphp
                @forelse($actEntries as $e)
                <tr><td>{{ $e->day_number }}</td><td><strong>{{ $e->title }}</strong></td><td>{{ $e->supplier ?: '-' }}</td><td>{{ $e->service_date ?: '-' }}</td><td>{{ $quotation->currency }} {{ number_format($e->system_rate, 2) }}</td><td><span class="ops-pill">{{ $e->status }}</span></td></tr>
                @empty<tr><td colspan="6" class="empty-cell">No activity items.</td></tr>@endforelse
            </tbody></table></div>
        </section>

        {{-- SUPPLEMENTS TAB --}}
        <section id="supplements" class="ops-panel">
            <div class="ops-panel-title"><h2>Supplements & Other</h2><p>Park fees, supplements, guides, and miscellaneous.</p></div>
            <div class="table-wrap"><table class="ops-table"><thead><tr><th>Day</th><th>Item</th><th>Type</th><th>Supplier</th><th>Rate</th><th>Status</th></tr></thead><tbody>
                @php $supEntries = $entries->filter(fn($e) => !in_array($e->item_type, ['room', 'accommodation', 'hotel', 'activity', 'transport', 'vehicle', 'transfer'])); @endphp
                @forelse($supEntries as $e)
                <tr><td>{{ $e->day_number }}</td><td>{{ $e->title }}</td><td><span class="item-type item-type--{{ $e->item_type }}">{{ $e->item_type }}</span></td><td>{{ $e->supplier ?: '-' }}</td><td>{{ $quotation->currency }} {{ number_format($e->system_rate, 2) }}</td><td><span class="ops-pill">{{ $e->status }}</span></td></tr>
                @empty<tr><td colspan="6" class="empty-cell">No supplement items.</td></tr>@endforelse
            </tbody></table></div>
        </section>

        {{-- PAYMENT DEADLINES TAB --}}
        <section id="deadlines" class="ops-panel">
            <div class="ops-panel-title"><h2>Payment deadlines</h2><p>Supplier payment schedule.</p></div>
            <div class="evaluation-metrics" style="grid-template-columns:repeat(3,1fr)">
                <article><small>Nearest deadline</small><strong>{{ $invoices->whereNotNull('payment_deadline')->sortBy('payment_deadline')->first()?->payment_deadline ? \Carbon\Carbon::parse($invoices->sortBy('payment_deadline')->first()->payment_deadline)->format('d M Y') : 'N/A' }}</strong></article>
                <article><small>Overdue</small><strong class="negative-money">{{ $invoices->filter(fn($i) => $i->payment_deadline && $i->payment_deadline < now())->count() }}</strong></article>
                <article><small>No deadline set</small><strong>{{ $invoices->whereNull('payment_deadline')->count() }}</strong></article>
            </div>
        </section>

        {{-- AUDIT LOG TAB --}}
        <section id="audit" class="ops-panel">
            <div class="ops-panel-title">
                <div><h2>Audit log</h2><p>All actions on this evaluation.</p></div>
                <a class="button button-secondary button-compact" href="{{ route('admin.evaluations.audit', $quotation->id) }}"><i data-lucide="history"></i>Full audit</a>
            </div>
            <div class="table-wrap"><table class="ops-table"><thead><tr><th>Time</th><th>User</th><th>Action</th><th>Description</th></tr></thead><tbody>
                @forelse($auditLogs as $log)
                <tr><td><small>{{ \Carbon\Carbon::parse($log->created_at)->format('d M H:i') }}</small></td><td>{{ $log->user_name ?: 'System' }}</td><td><span class="ops-pill ops-pill--muted">{{ ucwords(str_replace('_', ' ', $log->action)) }}</span></td><td>{{ $log->description }}</td></tr>
                @empty<tr><td colspan="4" class="empty-cell">No audit entries yet.</td></tr>@endforelse
            </tbody></table></div>
        </section>

        {{-- FINANCE TAB --}}
        <section id="finance" class="ops-panel evaluation-finance-panel">
            <div class="ops-panel-title"><div><h2>Finance handoff</h2><p>Approve and send payable invoices to accounts.</p></div></div>
            <div class="evaluation-finance-list">
                @forelse($invoices as $invoice)
                    <article>
                        <div><strong>{{ $invoice->company_name }}</strong><small>{{ $invoice->invoice_number ?: 'Pending' }} — {{ $invoice->currency }} {{ number_format($invoice->amount, 2) }}</small></div>
                        <form method="POST" action="{{ route('admin.evaluations.invoices.status', $invoice->id) }}">@csrf @method('PUT')
                            <input type="hidden" name="action" value="send_to_finance">
                            <input type="date" name="payment_deadline" value="{{ $invoice->payment_deadline }}" required>
                            <button class="button button-primary button-compact" @disabled(!in_array($invoice->status, ['approved','payment_ready']))><i data-lucide="send"></i>Finance</button>
                        </form>
                        <form method="POST" action="{{ route('admin.evaluations.invoices.status', $invoice->id) }}">@csrf @method('PUT')
                            <input type="hidden" name="action" value="requires_amendment">
                            <input name="issue_notes" value="{{ $invoice->issue_notes }}" placeholder="Reason">
                            <button class="button button-secondary button-compact"><i data-lucide="message-square-warning"></i>Amend</button>
                        </form>
                        @if($invoice->status === 'payment_ready')
                        <form method="POST" action="{{ route('admin.evaluations.invoices.status', $invoice->id) }}">@csrf @method('PUT')
                            <input type="hidden" name="action" value="mark_paid">
                            <button class="button button-secondary button-compact"><i data-lucide="badge-check"></i>Paid</button>
                        </form>
                        @endif
                    </article>
                @empty<div class="empty-cell">Create and verify invoices first.</div>@endforelse
            </div>
        </section>

        {{-- APPROVAL SECTION --}}
        <section class="evaluation-approval">
            <div><i data-lucide="shield-check"></i><span><strong>Final approval</strong><small>Unlocks invoices for payment handoff to accounts.</small></span></div>
            @if(($evaluation->status ?? '') === 'approved')
                <span class="evaluation-approved"><i data-lucide="badge-check"></i>Approved {{ isset($evaluation->approved_at) ? \Carbon\Carbon::parse($evaluation->approved_at)->format('d M Y H:i') : '' }}</span>
            @else
                <form method="POST" action="{{ route('admin.evaluations.approve', $quotation->id) }}">@csrf<button class="button button-primary" @disabled($summary['total'] === 0 || $summary['matched'] !== $summary['total'])><i data-lucide="badge-check"></i>Approve evaluation</button></form>
            @endif
        </section>
    </div>
</div>
<style>
.evaluation-metrics { display:grid; gap:0.75rem; }
.evaluation-check-grid { display:grid; grid-template-columns:1fr 1fr; gap:0.5rem; }
.evaluation-check-grid label { font-size:0.85rem; display:flex; align-items:center; gap:0.35rem; }
.evaluation-check-grid .is-not-applicable { opacity:0.4; pointer-events:none; }
.ops-pill--orange { background:#fef3c7;color:#d97706; }
.item-type { display:inline-block; padding:0.15rem 0.5rem; border-radius:4px; font-size:0.75rem; background:var(--surface); }
</style>
@endsection
